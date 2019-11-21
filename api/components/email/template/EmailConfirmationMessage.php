<?php
declare(strict_types=1);

namespace api\components\email\template;

use common\components\email\template\EmailConfirmationMessage as ECM;
use yii\base\InvalidArgumentException;

class EmailConfirmationMessage extends ECM
{
    public function getMessageParams(): array
    {
        $params = parent::getMessageParams();
        if (empty($params['token'])) {
            throw new InvalidArgumentException('Token required');
        }

        $uri = "/service/email/carrier/$params[token]";
        unset($params['token']);
        return ['uri' => $uri] + $params;
    }
}
