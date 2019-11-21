<?php

namespace backend\controllers;

use Yii;
use backend\models\TelegramOrderIllSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * TelegramOrderIllController implements the CRUD actions for TelegramOrderIll model.
 */
class TelegramOrderIllController extends Controller
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
     * Lists all TelegramOrderIll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TelegramOrderIllSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
