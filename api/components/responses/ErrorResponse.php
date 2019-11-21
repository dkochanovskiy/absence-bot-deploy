<?php
declare(strict_types=1);

namespace api\components\responses;

/**
 * Класс - ошибочный ответ
 */
class ErrorResponse
{
    protected $data;
    protected $external;

    public function __construct($data, $external = false)
    {
        $this->data = $data;
        $this->external = $external;
    }

    public function getErrorData()
    {
        return $this->data;
    }

    public function getExternal()
    {
        return $this->external;
    }
}
