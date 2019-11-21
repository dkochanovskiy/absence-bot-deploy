<?php

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('@image', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, ['api', 'web', 'images']));
Yii::setAlias('@imageApi', '/api/images');

Yii::setAlias('@file', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, ['api', 'web', 'files']));
Yii::setAlias('@fileApi', '/api/files');
