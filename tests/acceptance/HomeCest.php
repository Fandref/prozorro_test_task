<?php

use yii\helpers\Url;

class HomeCest
{

    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));  

        $I->amGoingTo('try to login with correct credentials');
        $I->fillField('input[name="LoginForm[username]"]', 'admin');
        $I->fillField('input[name="LoginForm[password]"]', 'admin');
        $I->click('login-button');

        $I->wait(2); // wait for page to be opened
        
        $I->see('Tender', 'h1');
        return true;
    }
}
