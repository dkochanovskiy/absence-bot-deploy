<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use \common\models\TelegramOrderDayoff;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TelegramOrderDayoffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на отгул';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="telegram-order-dayoff-index">

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            [
                'attribute' => 'dayoffType',
                'value' => function ($model) {
                    return TelegramOrderDayoff::getDayoffType()[$model->type_id] ?? 'None';
                },
                'filter'=> TelegramOrderDayoff::getDayoffType(),
            ],
            'dayoff_date',
            'planned_date_of_mining',
            'created_at',
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
