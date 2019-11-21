<?php

use yii\grid\GridView;
use \yii\widgets\Pjax;
use \common\models\TelegramOrderVacation;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TelegramOrderVacationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на отпуск';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="telegram-order-vacation-index">

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'name',
            [
                'attribute' => 'vacationType',
                'value' => function ($model) {
                    return TelegramOrderVacation::getVacationType()[$model->type_id] ?? 'None';
                },
                'filter'=> TelegramOrderVacation::getVacationType(),
            ],
            'vacation_start',
            'number_of_days',
            'created_at',

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
