<?php

namespace api\components\responses;

/**
 * Класс для обработки ошибок
 */
class StringErrorResponse extends ErrorResponse
{
    public static function handle(string $error = '', int $status = 400): ErrorResponse
    {
        \Yii::$app->getResponse()->setStatusCode($status);
        return new self($error);
    }
}