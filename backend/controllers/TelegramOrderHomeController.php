<?php

namespace backend\controllers;

use Yii;
use backend\models\TelegramOrderHomeSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * TelegramOrderHomeController implements the CRUD actions for TelegramOrderHome model.
 */
class TelegramOrderHomeController extends Controller
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
     * Lists all TelegramOrderHome models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TelegramOrderHomeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
