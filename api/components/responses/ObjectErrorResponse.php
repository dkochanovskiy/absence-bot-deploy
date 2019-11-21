<?php
declare(strict_types=1);

namespace api\components\responses;

use Yii;
/**
 * Класс для обработки ошибок
*/
class ObjectErrorResponse extends ErrorResponse
{
    /**
     * Получает на вход ассоциативный массив с ошибками
     * Изменяет статус ответа и возвращает сгенерированный ответ
     * @param int $status
     * @param array $error
     * @return ErrorResponse
    */
    public static function handle($error, int $status = 400): ErrorResponse
    {
        if (Yii::$app instanceof \yii\web\Application){
            Yii::$app->getResponse()->setStatusCode($status);
        }
        return new self($error, true);
    }
}
