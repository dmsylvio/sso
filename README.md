yii2 SSO
========
Connect Yii 2 application to a Identity Provider for Single Sign On

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist dmsylvio/sso "dev-master"
```

or add

```
"dmsylvio/sso": "dev-master"
```

to the require section of your `composer.json` file.


Database Migration
------------

Check your database settings and run migration from your console:

    php yii migrate --migrationPath=@vendor/dmsylvio/sso/migrations

For more informations see [Database Migration Documentation](http://www.yiiframework.com/doc-2.0/guide-console-migrate.html#applying-migrations)

Configuration
------------

To access the module, you need to add this to your application configuration:

    ......
    'modules' => [
        'sso' => [
            'class' => 'app\vendor\dmsylvio\sso\Access',
        ],
    ],
    ......
    
 Configure System Id in config/params.php
    
    return [
      'adminEmail' => 'admin@example.com',
      'systemId'	=>	'2',
      'systemName' => 'teste',
      'systemDesc' => 'Sistema de teste',
    ];
    
   Add the new menu item to your navbar: [not necessary] ~ only to verify that sso is installed

    ......
    ['label' => 'sso', 'url' => ['/sso']],
    ......
    
    You may have to customize the user rights for the access log view. You could do it by editing ```behaviors/AccessRule.php```.

Example manual usage
------------

This is an example in the login method from the module dmsylvio/yii2-accounts.

    use app\vendor\dmsylvio\sso\behaviors\AccessRule;

    ......
     /**
     * {@inheritdoc}
     */
    public function behaviors(){

        $controllername  = Yii::$app->controller->id;

        $behaviors = [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => AccessRule::getActions($controllername),
                'rules' => AccessRule::getRoles($controllername),
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];

        return $behaviors;
    }
    ......
