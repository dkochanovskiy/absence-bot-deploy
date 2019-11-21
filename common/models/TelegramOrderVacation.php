<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "telegram_order_vacation".
 *
 * @property int $telegram_order_vacation_id
 * @property int $chat_id
 * @property int $user_id
 * @property int $type_id
 * @property string $vacation_start
 * @property int $number_of_days
 * @property string $created_at
 *
 * @property User $user
 */
class TelegramOrderVacation extends ActiveRecord
{
    const PAID = 1;
    const UNPAID = 2;

    public static function getVacationType()
    {
        return [
            self::PAID => 'Оплачиваемый',
            self::UNPAID => 'Неоплачиваемый'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_order_vacation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id'], 'required'],
            [['chat_id', 'user_id', 'type_id', 'number_of_days'], 'default', 'value' => null],
            [['chat_id', 'user_id', 'type_id', 'number_of_days'], 'integer'],
            [['created_at'], 'safe'],
            [['vacation_start'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'telegram_order_vacation_id' => 'Telegram Order Vacation ID',
            'chat_id' => 'Chat ID',
            'user_id' => 'User ID',
            'type_id' => 'Type ID',
            'name' => 'Сотрудник',
            'vacationType' => 'Тип отпуска',
            'vacation_start' => 'Начало отпуска',
            'number_of_days' => 'Количество дней',
            'created_at' => 'Дата создания',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public function getName() {
        return $this->user->name;
    }
}
