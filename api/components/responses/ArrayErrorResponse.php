<?php
declare(strict_types=1);

namespace api\components\responses;

/**
 * Класс для обработки ошибок
*/
class ArrayErrorResponse extends ErrorResponse
{
    /**
     * Получает на вход ассоциативный массив с ошибками
     * Изменяет статус ответа и возвращает сгенерированный ответ
     * @param int $status
     * @param array $errors
     * @return ErrorResponse
    */
    public static function handle(array $errors = [], int $status = 400): ErrorResponse
    {
        if(!is_array($errors)){
            die('no');
        }
        \Yii::$app->getResponse()->setStatusCode($status);
        return new self($errors);
    }
}
