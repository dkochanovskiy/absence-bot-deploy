<?php
declare(strict_types=1);

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\db\Expression;

class ServiceController extends Controller
{
    /**
     * Метод удаляет просроченных гостей
     * @throws \yii\db\Exception
     */
    public function actionDeleteExpGuest()
    {
        $subQuery = User::find()->select('user.user_id')
            ->where(['role' => User::ROLE_GUEST])
            ->innerJoinWith(['sessions' => function ($query) {
                return $query->onCondition(['<', 'session.expired_at', new Expression('now()')]);
            }])->limit(1000);

        $result = Yii::$app->db->createCommand()->delete('user', ['in', 'user.user_id', $subQuery])->execute();
        echo $result . "\n";
    }
}
