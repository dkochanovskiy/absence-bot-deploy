<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "telegram_order_ill".
 *
 * @property int $telegram_order_ill_id
 * @property int $chat_id
 * @property int $user_id
 * @property int $sick_leave
 * @property int $due_to_what
 * @property string $planned_date_of_mining
 * @property string $created_at
 *
 * @property User $user
 */
class TelegramOrderIll extends ActiveRecord
{
    const WITHOUT_SICK_LEAVE = 0;
    const WITH_SICK_LEAVE = 1;

    const I_WILL_WORK_FROM_HOME = 1;
    const NO_CONTENT = 2;
    const IN_VACATION = 3;
    const ILL_WORK_AT_THE_WEEKEND = 4;

    public static function getIllType()
    {
        return [
            self::WITHOUT_SICK_LEAVE => 'Без больничного листа',
            self::WITH_SICK_LEAVE => 'С больничным листом'
        ];
    }

    public static function getDueToWhat()
    {
        return [
            self::I_WILL_WORK_FROM_HOME => 'Буду работать из дома',
            self::NO_CONTENT => 'Без содержания',
            self::IN_VACATION => 'За счет отпуска',
            self::ILL_WORK_AT_THE_WEEKEND => 'Отработаю в выходной',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_order_ill';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id'], 'required'],
            [['chat_id', 'user_id', 'sick_leave', 'due_to_what'], 'default', 'value' => null],
            [['chat_id', 'user_id', 'sick_leave', 'due_to_what'], 'integer'],
            [['created_at'], 'safe'],
            [['planned_date_of_mining'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'telegram_order_ill_id' => 'Telegram Order Ill ID',
            'chat_id' => 'Chat ID',
            'user_id' => 'User ID',
            'name' => 'Сотрудник',
            'illType' => 'Наличие больничного листа',
            'due_to_what' => 'За счет чего',
            'dueToWhat' => 'За счет чего',
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
