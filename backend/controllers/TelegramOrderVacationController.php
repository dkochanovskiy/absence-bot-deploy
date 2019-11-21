<?php

namespace backend\controllers;

use Yii;
use backend\models\TelegramOrderVacationSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * TelegramOrderVacationController implements the CRUD actions for TelegramOrderVacation model.
 */
class TelegramOrderVacationController extends Controller
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
     * Lists all TelegramOrderVacation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TelegramOrderVacationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
