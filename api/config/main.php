<?php

use yii\web\Response;
use api\components\responses\ErrorResponse;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'api\modules\v1\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                /* @var $response \yii\web\Response */
                $response = $event->sender;
                if ($response->format == Response::FORMAT_HTML) {
                    return;
                }

                $error = $response->statusCode != 200;
                $errorData = null;
                $externalError = false;
                if ($error) {
                    if ($response->data instanceof ErrorResponse) {
                        $errorData = $response->data->getErrorData();
                        $externalError = $response->data->getExternal();
                    } else {
                        $errorData = $response->data['message'] ?? '';
                    }
                }
                $response->data =  [
                    'success' => !$error,
                    'data' => ($error || (is_array($response->data) && count($response->data) == 0)) ? new stdClass() : $response->data
                ];
                if($error){
                    $response->data['message'] = $errorData;
                }
                if($externalError) {
                    $response->data['external'] = true;
                }
            },
        ],
        'request' => [
            'class' => '\yii\web\Request',
            'baseUrl' => '/api',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'parsers' => [
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                'POST v1/telegram/message' => 'v1/telegram/message',
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            'yii\data\Pagination' => [
                'class' => 'api\modules\v1\components\Pagination',
                'validatePage' => false,
            ],
        ],
    ],
    'params' => $params,
];

