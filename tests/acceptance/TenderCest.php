<?php

use app\models\Tender;
use yii\helpers\Url;

class TenderCest
{
    private function fillModel(){
        for($i=0; $i<10; $i++){
            $model = new Tender();
            $model->tender_id = "wef9wef9we329m3209r434ks32ds34s{$i}";
            $model->description = 'test test test test';
            $model->amount = 3333.22;
            $model->date_modified = Yii::$app->formatter->asDatetime('2023-07-15T14:21:01.746421', 'php:Y-m-d H:i:s');

            $model->save();
        }
    }
    public function _before(AcceptanceTester $I){
        $this->fillModel();

        $I->amOnPage(Url::toRoute('/site/index'));  
        
        $I->fillField('input[name="LoginForm[username]"]', 'admin');
        $I->fillField('input[name="LoginForm[password]"]', 'admin');
        $I->click('login-button');

        $I->wait(2);
    }

    public function _after(){
        Tender::deleteAll();
    }

    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {        
        $I->see('Tender', 'h1');
        $I->see('');

        $I->click('2');
        return true;
    }
}
