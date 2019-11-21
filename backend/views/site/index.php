<?php

use \yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */

$this->title = 'Заявки';

?>
<div class="site-index">

    <?= Html::a('Заявки на работу из дома', ['telegram-order-home/index']) ?><br><br>
    <?= Html::a('Заявки по болезни', ['telegram-order-ill/index']) ?><br><br>
    <?= Html::a('Заявки на отпуск', ['telegram-order-vacation/index']) ?><br><br>
    <?= Html::a('Заявки на отгул', ['telegram-order-dayoff/index']) ?>

</div>