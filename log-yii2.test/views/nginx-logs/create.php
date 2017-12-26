<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NginxLogs */

$this->title = Yii::t('app', 'Create Nginx Logs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nginx Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nginx-logs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
