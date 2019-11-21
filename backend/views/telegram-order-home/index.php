<?php

use yii\grid\GridView;
use \yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TelegramOrderHomeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на работу из дома';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="telegram-order-home-index">

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'name',
            'date_absence',
            'reason',
            'created_at',

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
