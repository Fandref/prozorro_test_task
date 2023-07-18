<?php

use yii\helpers\Url;

class HomeCest
{
    public function _before(\FunctionalTester $I){
        $I->amOnRoute('site/index');
    }

    public function ensureThatHomePageWorks(\FunctionalTester $I)
    {
        $I->see('Login', 'h1');

        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin',
        ]);

        $I->see('Tender', 'h1');
    }
}
