<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\NginxLogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Nginx Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nginx-logs-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'style' => 'font-size:12px;'
        ],
        'columns' => [
            [
                'attribute' => 'time',
                'value' => function($data){
                    return date('d.m.Y H:i:s', $data->time);
                },
                'filter' => \kartik\daterange\DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'time',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => true,
                        'timePickerIncrement' => 5,
                        'locale' => [
                            'format' => 'Y-m-d h:i A'
                        ]
                    ]
                ]),
            ],
            'ip',
            [
              'attribute' => 'file',
                'value' => function($data){
                    return basename($data->file);
                },
                'filter' => \app\models\NginxLogs::getLogFiles()
            ],
            [
                'attribute' => 'request',
                'value' => function($data){
                    return $data->request . ' '
                    . $data->status.' '
                    . $data->sentBytes . ' "'
                    . $data->referer . '" "'
                    . $data->user_agent . '"';
                }
            ],

            //'request',
            //'status',
            //'sentBytes',
            //'referer',
            //'user_agent',
            //'created_at',
            //'updated_at',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
