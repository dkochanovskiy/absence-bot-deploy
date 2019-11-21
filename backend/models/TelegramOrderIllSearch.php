<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TelegramOrderIll;

/**
 * TelegramOrderIllSearch represents the model behind the search form of `common\models\TelegramOrderIll`.
 */
class TelegramOrderIllSearch extends TelegramOrderIll
{
    public $name;
    public $illType;
    public $dueToWhat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telegram_order_ill_id', 'chat_id', 'user_id', 'sick_leave', 'due_to_what'], 'integer'],
            [['planned_date_of_mining', 'created_at', 'name', 'illType', 'dueToWhat'], 'safe'],
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
        $query = TelegramOrderIll::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'telegram_order_ill_id',
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'label' => 'Сотрудник',
                    'default' => SORT_ASC
                ],
                'illType' => [
                    'asc' => ['vacationType' => SORT_ASC],
                    'desc' => ['vacationType' => SORT_DESC],
                    'label' => 'Тип отпуска',
                    'default' => SORT_ASC
                ],
                'dueToWhat' => [
                    'asc' => ['vacationType' => SORT_ASC],
                    'desc' => ['vacationType' => SORT_DESC],
                    'label' => 'Тип отпуска',
                    'default' => SORT_ASC
                ],
                'planned_date_of_mining',
                'created_at',
            ]
        ]);

        $this->load($params);

        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['user']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'telegram_order_ill_id' => $this->telegram_order_ill_id,
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'sick_leave' => $this->sick_leave,
            'due_to_what' => $this->due_to_what,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['ilike', 'planned_date_of_mining', $this->planned_date_of_mining]);

        $query->andFilterWhere(['sick_leave' => $this->illType]);

        $query->andFilterWhere(['due_to_what' => $this->dueToWhat]);

        $query->joinWith(['user' => function ($q) {
            $q->andFilterWhere(['like', 'user.name', $this->name]);
        }]);

        return $dataProvider;
    }
}
