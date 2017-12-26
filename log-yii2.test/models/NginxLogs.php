<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "nginx_logs".
 *
 * @property int $id
 * @property string $ip
 * @property string $file
 * @property int $time
 * @property string $request
 * @property int $status
 * @property int $sentBytes
 * @property string $referer
 * @property string $user_agent
 * @property int $created_at
 * @property int $updated_at
 */
class NginxLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nginx_logs';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'file', 'time', 'request', 'status', 'sentBytes'], 'required'],
            [['time', 'status', 'sentBytes', 'created_at', 'updated_at'], 'integer'],
            [['ip', 'file', 'request', 'referer', 'user_agent'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'file' => 'File',
            'time' => 'Time',
            'request' => 'Request',
            'status' => 'Status',
            'sentBytes' => 'Sent Bytes',
            'referer' => 'Referer',
            'user_agent' => 'User Agent',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getLogFiles(){
        $files = Yii::$app->cache->get('all_log_files');
        if(!$files){
            $files = NginxLogs::find()
                ->groupBy('file')
                ->select('file')
                ->column();
            $files = array_map(function($item){
                return basename($item);
            }, $files);
            $files = array_combine($files, $files);
            Yii::$app->cache->set('all_log_files', $files , 300);
        }
        return $files;
    }
}
