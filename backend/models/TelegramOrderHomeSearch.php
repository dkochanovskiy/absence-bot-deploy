<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TelegramOrderHome;

/**
 * TelegramOrderHomeSearch represents the model behind the search form of `common\models\TelegramOrderHome`.
 */
class TelegramOrderHomeSearch extends TelegramOrderHome
{
    public $name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telegram_order_home_id', 'chat_id', 'user_id'], 'integer'],
            [['date_absence', 'reason', 'created_at', 'name'], 'safe'],
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
        $query = TelegramOrderHome::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'telegram_order_home_id',
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'label' => 'Сотрудник',
                    'default' => SORT_ASC
                ],
                'date_absence',
                'reason',
                'created_at',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['user']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'telegram_order_home_id' => $this->telegram_order_home_id,
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['ilike', 'date_absence', $this->date_absence])
            ->andFilterWhere(['ilike', 'reason', $this->reason]);

        $query->joinWith(['user' => function ($q) {
            $q->andFilterWhere(['like', 'user.name', $this->name]);
        }]);

        return $dataProvider;
    }
}
