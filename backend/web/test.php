<?php
/*
 * включает/отключает показ подробной информации ошибок
 * либо показывает стандартный вид view для ошибок
 */
defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_DEBUG') or define('YII_DEBUG', false);

defined('YII_ENV') or define('YII_ENV', 'dev');
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

//defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';
// файл со строками сообщений/предупреждений
require __DIR__ . '/../../backend/components/mess.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

use backend\controllers\MainController as d;

d::pre('ha ha');
