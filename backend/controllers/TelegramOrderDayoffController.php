<?php

namespace backend\controllers;

use Yii;
use backend\models\TelegramOrderDayoffSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * TelegramOrderDayoffController implements the CRUD actions for TelegramOrderDayoff model.
 */
class TelegramOrderDayoffController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TelegramOrderDayoff models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TelegramOrderDayoffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
