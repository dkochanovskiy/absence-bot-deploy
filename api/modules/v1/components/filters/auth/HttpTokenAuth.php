<?php
declare(strict_types=1);

namespace api\modules\v1\components\filters\auth;

use Exception;
use yii\filters\auth\AuthMethod;

/**
 * Авторизация по бессрочному токену Auth-Token
 */
class HttpTokenAuth extends AuthMethod
{
    const TOKEN_NAME = 'Auth-Token';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        try {
            $token = $request->getHeaders()->get(self::TOKEN_NAME);
            if (!is_null($token)) {
                return $user->loginByAccessToken($token, get_class($this));
            }
        } catch (Exception $e) {

        }
        return null;
    }

}
