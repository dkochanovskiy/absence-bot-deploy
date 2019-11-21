<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "telegram_order_home".
 *
 * @property int $telegram_order_home_id
 * @property int $chat_id
 * @property int $user_id
 * @property string $date_absence
 * @property string $reason
 * @property string $created_at
 *
 * @property User $user
 */
class TelegramOrderHome extends ActiveRecord
{
    const HOME = 1;
    const ILL = 2;
    const VACATION = 3;
    const DAY_OFF = 4;

    public static function getAbsenceType()
    {
        return [
            self::HOME => 'Поработаю из дома',
            self::ILL => 'Заболел и не могу работать',
            self::VACATION => 'Хочу в отпуск',
            self::DAY_OFF => 'Хочу взять отгул',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_order_home';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id'], 'required'],
            [['chat_id', 'user_id'], 'default', 'value' => null],
            [['chat_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['date_absence', 'reason'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'name' => 'Сотрудник',
            'type_id' => 'Тип отсутствия',
            'absenceType' => 'Тип отсутствия',
            'date_absence' => 'Дата отсутствия',
            'reason' => 'Причина',
            'created_at' => 'Дата создания'
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
