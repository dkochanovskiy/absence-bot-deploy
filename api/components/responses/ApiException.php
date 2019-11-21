<?php
declare(strict_types=1);

namespace api\components\responses;
use yii\web\HttpException;

class ApiException extends HttpException
{
    const ERROR_DOMAIN_NOT_FOUND = 100;
    const ERROR_DOMAIN_NOT_ACTIVE = 101;

    const ERROR_BAD_INPUT_DATA = 400;
    const ERROR_TOKEN_IS_MISSING = 401;
    const ERROR_TOKEN_IS_INVALID = 401;

    const ERROR_UNKNOWN = 403;

    public function getDefaultErrorMessages()
    {
        return [
            self::ERROR_DOMAIN_NOT_FOUND => 'Домен не существует',
            self::ERROR_DOMAIN_NOT_ACTIVE => 'Домен был отключен администратором',

            self::ERROR_BAD_INPUT_DATA => 'Неверные данные',

            self::ERROR_TOKEN_IS_MISSING => 'Отсутствует токен доступа',
            self::ERROR_TOKEN_IS_INVALID => 'Неверный токен доступа',

            self::ERROR_UNKNOWN => 'Неизвестная ошибка',
        ];
    }

    public function getDefaultErrorMessage($code)
    {
        $messages = $this->getDefaultErrorMessages();
        return isset($messages[$code]) ? $messages[$code] : $messages[self::ERROR_UNKNOWN];
    }

    public function __construct($code = 406, $message = null, \Exception $previous = null)
    {
//        if ($message === null) {
//            $message = $this->getDefaultErrorMessage($code);
//        }
        parent::__construct($code, $message, $previous);
    }
}