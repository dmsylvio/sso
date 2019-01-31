yii2-sso
========
Connect Yii 2 application to a Identity Provider for Single Sign On

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist dmsylvio/yii2-soo "*"
```

or add

```
"dmsylvio/yii2-soo": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \app\vendor\dmsylvio\sso\AutoloadExample::widget(); ?>```