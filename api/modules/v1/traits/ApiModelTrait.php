<?php
declare(strict_types=1);

namespace api\modules\v1\traits;

use yii\helpers\Inflector;

trait ApiModelTrait
{
    /**
     * @return array
     */
    public function getFirstErrors()
    {
        $errors = parent::getFirstErrors();
        $result = [];
        foreach ($errors as $attribute => $error) {
            $result[Inflector::variablize($attribute)] = $error;
        }
        unset($errors);
        return $result;
    }

    /**
     * @param $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true)
    {
        $result = [];
        foreach ($values as $key => $value) {
            $result[Inflector::variablize($key)] = $value;
        }
        unset($values);
        parent::setAttributes($result, $safeOnly);
    }
}
