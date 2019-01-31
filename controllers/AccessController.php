<?php

namespace app\vendor\dmsylvio\sso\controllers;

use yii\web\Controller;
use app\vendor\dmsylvio\sso\behaviors\AccessRule;

/**
 * Default controller for the `sso` module
 */
class AccessController extends Controller{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}