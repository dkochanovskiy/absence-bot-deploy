<?php

namespace common\models;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $name
 *
 * @property TelegramOrder[] $telegramOrders
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramOrders()
    {
        return $this->hasMany(TelegramOrder::class, ['user_id' => 'user_id']);
    }
}
