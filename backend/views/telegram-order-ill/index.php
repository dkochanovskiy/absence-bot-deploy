<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use \common\models\TelegramOrderIll;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TelegramOrderIllSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки по болезни';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="telegram-order-ill-index">

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => [

            'name',
            [
                'attribute' => 'illType',
                'value' => function ($model) {
                    return TelegramOrderIll::getIllType()[$model->sick_leave] ?? '';
                },
                'filter'=> TelegramOrderIll::getIllType(),
            ],
            [
                'attribute' => 'dueToWhat',
                'value' => function ($model) {
                    return TelegramOrderIll::getDueToWhat()[$model->due_to_what] ?? '';
                },
                'filter'=> TelegramOrderIll::getDueToWhat(),
            ],
            'planned_date_of_mining',
            'created_at',

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
