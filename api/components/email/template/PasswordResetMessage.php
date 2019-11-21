<?php
declare(strict_types=1);

namespace api\components\email\template;

use common\components\email\template\PasswordResetMessage as PRM;
use yii\base\InvalidArgumentException;

/**
 * Required 'token' in params
*/
class PasswordResetMessage extends PRM
{
    public function getMessageParams(): array
    {
        $params = parent::getMessageParams();
        if (empty($params['token'])) {
            throw new InvalidArgumentException('Token required');
        }

        $uri = "/password-restore/$params[token]";
        return ['uri' => $uri] + $params;
    }
}
