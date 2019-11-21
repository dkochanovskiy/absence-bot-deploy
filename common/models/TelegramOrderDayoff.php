<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "telegram_order_dayoff".
 *
 * @property int $telegram_order_dayoff_id
 * @property int $chat_id
 * @property int $user_id
 * @property int $type_id
 * @property string $dayoff_date
 * @property string $planned_date_of_mining
 * @property string $created_at
 *
 * @property User $user
 */
class TelegramOrderDayoff extends ActiveRecord
{
    const ON_VACATION = 1;
    const AT_OWN_EXPENSE = 2;
    const WORK_LATER = 3;

    public static function getDayoffType()
    {
        return [
            self::ON_VACATION => 'За счет отпуска',
            self::AT_OWN_EXPENSE => 'За свой счет',
            self::WORK_LATER => 'Отработаю позднее',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_order_dayoff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id'], 'required'],
            [['chat_id', 'user_id', 'type_id'], 'default', 'value' => null],
            [['chat_id', 'user_id', 'type_id'], 'integer'],
            [['created_at'], 'safe'],
            [['dayoff_date', 'planned_date_of_mining'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'telegram_order_dayoff_id' => 'Telegram Order Dayoff ID',
            'chat_id' => 'Chat ID',
            'user_id' => 'User ID',
            'name' => 'Сотрудник',
            'type_id' => 'Type Dayoff',
            'dayoffType' => 'Тип отгула',
            'dayoff_date' => 'Дата отсутствия',
            'planned_date_of_mining' => 'Планируемая дата отработки',
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
