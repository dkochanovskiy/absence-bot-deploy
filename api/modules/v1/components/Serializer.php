<?php

namespace api\modules\v1\components;

use yii\base\Model;
use yii\data\Pagination;

/**
 * Class Serializer
 * @package api\modules\v1\components
 */
class Serializer extends \yii\rest\Serializer
{
    /**
     * @var string
     */
    public $collectionEnvelope = 'items';
    /**
     * @var string
     */
    public $totalCountHeader = 'X-Pagination-Total';
    /**
     * @var string
     */
    public $offsetHeader = 'X-Pagination-Offset';
    /**
     * @var string
     */
    public $limitHeader = 'X-Pagination-Limit';

    /**
     * Serializes a pagination into an array.
     * @param Pagination $pagination
     * @return array the array representation of the pagination
     */
    protected function serializePagination($pagination)
    {
        return [
            'total' => $pagination->totalCount,
            'offset' => $pagination->getOffset(),
            'limit' => $pagination->getLimit(),
        ];
    }

    /**
     * Adds HTTP headers about the pagination to the response.
     * @param Pagination $pagination
     */
    protected function addPaginationHeaders($pagination)
    {
        $this->response->getHeaders()
            ->set($this->totalCountHeader, $pagination->totalCount)
            ->set($this->offsetHeader, $pagination->getOffset())
            ->set($this->limitHeader, $pagination->getLimit());
    }

    /**
     * Serializes the validation errors in a model.
     * @param Model $model
     * @return array the array representation of the errors
     */
    protected function serializeModelErrors($model)
    {
        $this->response->setStatusCode(422, 'Data Validation Failed');
        $result = [];
        foreach ($model->getFirstErrors() as $name => $message) {
            $result[] = $message;
        }
        return $result;
    }
}