<?php

use app\models\Tender;
use yii\helpers\Url;

class TenderCest
{
    public function _before(\FunctionalTester $I){
        for($i=0; $i<10; $i++){
            $model = new Tender();
            $model->tender_id = "wef9wef9we329m3209r434ks32ds34s{$i}";
            $model->description = 'test test test test';
            $model->amount = 3333.22;
            $model->date_modified = Yii::$app->formatter->asDatetime('2023-07-15T14:21:01.746421', 'php:Y-m-d H:i:s');

            $model->save();
        }
        
        
        $I->amLoggedInAs(100);
        $I->amOnPage('/');
        
    }

    public function _after(\FunctionalTester $I){
        $I->amLoggedInAs(100);
        $I->amOnPage('/');
        
        Tender::deleteAll();
        
    }

    public function ensureThatTenderPageWorks(\FunctionalTester $I)
    {
        $I->see('Tender', 'h1');

        $I->see('Showing 1-5 of 10 items.');

        $I->see('IdTender IdDescriptionAmountDate Modified');
        
    }
}
