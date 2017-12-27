<?php
namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\web\BadRequestHttpException;
use app\models\search\NginxLogsSearch;

Class ApiController extends ActiveController
{
    public $modelClass = 'app\models\NginxLogs';
    public $reservedParams = ['sort'];
    public $extendParams = ['etime', 'stime'];

    /*
     * /api?stime=1495641258&etime=1495645658&sort=ip&format=json&page=20&per-page=10&token=6WTbqlEU2LM7JbA4ZesIkdYKH2SMdGKh
     * token - access token, auth_key из таблицы пользователей
     * ip - ip адрес
     * stime - начальная дата timestamp
     * etime - конечная дата timestamp
     * sort - сортировать по полю
     * format - формат [json, xml]
     * file - имя лог файла, можно указать имя хоста(like)
     * request - поиск по коду ответа сервера, размеру данных, uri, referer, user agent
     * page - страница
     * per-page - результатов на странице
     */

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['class'] = QueryParamAuth::className();
        $behaviors['authenticator']['tokenParam'] = 'token';
        $behaviors['contentNegotiator']['formatParam'] = 'format'; //api?format=json
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];
        return $actions;
    }

    public function indexDataProvider() {
        $params = \Yii::$app->request->queryParams;
        $model = new $this->modelClass;
        $modelAttr = $model->attributes;
        $search = [];
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if(!is_scalar($key) or !is_scalar($value)) {
                    throw new BadRequestHttpException('Bad Request');
                }
                if (!in_array(strtolower($key), $this->reservedParams)
                    && (ArrayHelper::keyExists($key, $modelAttr, false) || in_array($key, $this->extendParams)) ) {
                    $search[$key] = $value;
                }
            }
        }
        $searchByAttr['NginxLogsSearch'] = $search;
        $searchModel = new \app\models\search\NginxLogsSearch();
        return $searchModel->search($searchByAttr);
    }
}
