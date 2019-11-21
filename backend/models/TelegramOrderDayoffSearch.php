<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TelegramOrderDayoff;

/**
 * TelegramOrderDayoffSearch represents the model behind the search form of `common\models\TelegramOrderDayoff`.
 */
class TelegramOrderDayoffSearch extends TelegramOrderDayoff
{
    public $name;
    public $dayoffType;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telegram_order_dayoff_id', 'chat_id', 'user_id', 'type_id'], 'integer'],
            [['dayoff_date', 'planned_date_of_mining', 'created_at', 'name', 'dayoffType'], 'safe'],
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
        $query = TelegramOrderDayoff::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'label' => 'Сотрудник',
                    'default' => SORT_ASC
                ],
                'dayoff_date',
                'dayoffType' => [
                    'asc' => ['dayoffType' => SORT_ASC],
                    'desc' => ['dayoffType' => SORT_DESC],
                    'label' => 'Тип отгула',
                    'default' => SORT_ASC
                ],
                'planned_date_of_mining',
                'created_at',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['user']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'telegram_order_dayoff_id' => $this->telegram_order_dayoff_id,
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['ilike', 'dayoff_date', $this->dayoff_date])
            ->andFilterWhere(['ilike', 'planned_date_of_mining', $this->planned_date_of_mining]);

        $query->andFilterWhere(['type_id' => $this->dayoffType]);

        $query->joinWith(['user' => function ($q) {
            $q->andFilterWhere(['like', 'user.name', $this->name]);
        }]);

        return $dataProvider;
    }
}
