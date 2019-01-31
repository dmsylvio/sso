<?php

namespace app\vendor\dmsylvio\sso;

use Yii;

/**
 * sso module definition class
 */
class Access extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'access';

    /**
     * @inheritdoc
     */
    public function init(){

        parent::init();
        Yii::setAlias('@sso', $this->getBasePath());
        //$this->registerTranslations();
    }
}