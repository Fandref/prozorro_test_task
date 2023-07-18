<?php

use tests\_support\ConsoleInterceptor;
use yii\console\Application;
use yii\console\ExitCode;

class TenderCommandCest
{

    public function ensureThatTenderCommadWorks(ConsoleInterceptor $console)
    {
        $config = yii\helpers\ArrayHelper::merge(
            require Yii::getAlias('@app/config/console.php'),
            [
                'components' => [
                    'db' => require Yii::getAlias('@app/config/test_db.php')
                ],
            ]
        );

        $app = new Application($config);

        $params = [10, 5];
        $calcCountTender = $params[0] * $params[1];

        $console->startWatch();

        verify($app->runAction('tender'), [10, 5])->equals(ExitCode::OK);
        verify($console::$value)->stringContainsString("Upload {$calcCountTender} out of {$calcCountTender} tender(s).");

        $console->stopWatch();

        
        
    }
}
    