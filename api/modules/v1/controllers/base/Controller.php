<?php

namespace api\modules\v1\controllers\base;

use yii\filters\Cors;
use yii\helpers\ArrayHelper;

/**
 * Контроллер родитель для контроллеров апи требующих авторизацию
 */
class Controller extends \yii\rest\Controller
{
    public $serializer = 'api\modules\v1\components\Serializer';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'corsFilter'    => [
                'class' => Cors::class,
            ],
        ]);
    }
}
