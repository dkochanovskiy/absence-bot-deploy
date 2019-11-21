<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TelegramOrderVacation;

/**
 * TelegramOrderVacationSearch represents the model behind the search form of `common\models\TelegramOrderVacation`.
 */
class TelegramOrderVacationSearch extends TelegramOrderVacation
{
    public $name;
    public $vacationType;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telegram_order_vacation_id', 'chat_id', 'user_id', 'type_id', 'number_of_days'], 'integer'],
            [['vacation_start', 'created_at', 'name', 'vacationType'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TelegramOrderVacation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'telegram_order_vacation_id',
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'label' => 'Сотрудник',
                    'default' => SORT_ASC
                ],
                'vacationType' => [
                    'asc' => ['vacationType' => SORT_ASC],
                    'desc' => ['vacationType' => SORT_DESC],
                    'label' => 'Тип отпуска',
                    'default' => SORT_ASC
                ],
                'vacation_start',
                'number_of_days',
                'created_at',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['user']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'telegram_order_vacation_id' => $this->telegram_order_vacation_id,
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'type_id' => $this->type_id,
            'number_of_days' => $this->number_of_days,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['ilike', 'vacation_start', $this->vacation_start]);

        $query->andFilterWhere(['type_id' => $this->vacationType]);

        $query->joinWith(['user' => function ($q) {
            $q->andFilterWhere(['like', 'user.name', $this->name]);
        }]);

        return $dataProvider;
    }
}
