<?php
namespace app\console\controllers;

use Yii;
use app\models\NginxLogs;
use yii\console\Controller;
use app\components\logparser\KnuckleLog;
use yii\console\Exception;
use yii\helpers\FileHelper;

/**
 * Парсинг лога
*/

Class LogParserController extends Controller {

    public $logs_dir = '/var/log/nginx/domains/';
    public $only_mask = [];
    public $except_mask = [];
    public $format = '%a %l %u %t "%r" %>s %O "%{Referer}i" \"%{User-Agent}i"';

    /**
     * Запустить парсинг лога
     */
    public function actionRun(){
        $logs = $this->getLogFiles();
        if(!count($logs)){
            throw new Exception('Лог файлы не найдены');
        }

        foreach ($logs as $log_file){
            echo 'Лог файл: ' . $log_file . "\n";
            $offset = 0;
            $limit = 10;
            do {
                $data = new KnuckleLog($log_file, $this->format, $offset, $limit);
                //$data = new KnuckleLog('/data/wwwlogs/muzlan.ru_access.log', $this->format, $offset, $limit);
                $array = $data->worker();
                if(count($array['data'])){
                    $this->saveToDb($log_file, $array['data']);
                }
                $offset = $offset + $limit;
                if($array['totalLines'] < $limit){
                    $limit = $array['totalLines'];
                }

                echo $offset . "\n";
                echo $limit . "\n";
                echo $array['totalLines'] . "\n";

            } while(isset($array['totalLines']) && $array['totalLines']);
        }
    }

    public function getLogFiles(){
        if(!file_exists($this->logs_dir)){
            throw new Exception('Директория с файлами логов не найдена. Проверьте параметр logs_dir');
        }
        return FileHelper::findFiles($this->logs_dir, [
            'only' => $this->only_mask,
            'except' => $this->except_mask
        ]);
    }

    public function saveToDb($log_file, $data){
        foreach ($data as $item) {
            $model = new NginxLogs();
            $model->file = $log_file;
            $model->ip = $item->remoteIp;
            $model->time = $item->stamp;
            $model->request = $item->request;
            $model->status = $item->status;
            $model->sentBytes = $item->sentBytes;
            $model->referer = $item->HeaderReferer;
            $model->user_agent = $item->HeaderUserAgent;
            if(!$model->save()){
                //print_r($model->getErrors());
                //die();
            }
        }
    }
}
