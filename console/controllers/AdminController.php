<?php

namespace console\controllers;


use common\models\Admin;
use yii\console\Controller;

/**
 * This is the command line tool for admins.
 *
 * You can use this command to create admin:
 *
 * ```
 * $ ./yii admin/create
 * ```
 */
class AdminController extends Controller
{

    public function actionCreate($email, $password)
    {
        $existAdmin = Admin::findOne(['email' =>$email]);
        if($existAdmin){
            echo 'admin already exist';
            return true;
        }
        $admin =  Admin::create($email, $password);
        if(!$admin->validate()) {
            print_r('Not Created');
            print_r($admin->errors);
        } else {
            $admin->save();
        }
    }
}