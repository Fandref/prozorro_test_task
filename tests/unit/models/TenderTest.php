<?php

namespace tests\unit\models;

use app\models\Tender;
use Yii;

class TenderTest extends \Codeception\Test\Unit
{
    private $model;

    public function testValidationSave()
    {
        $this->model = new Tender();
        $tender_id = 'wef9wef9we329m3209rm43';
        $this->model->tender_id = $tender_id;
        $this->model->description = 'test test test test';
        $this->model->amount = 3333.22;
        $this->model->date_modified = '2023-07-15T14:21:01.746421';

        verify($this->model->save())->false();
        verify($this->model->errors)->notEmpty();
        verify(Tender::find()->where(['tender_id' => $tender_id])->exists())->false();
    }

    public function testSave()
    {
        $this->model = new Tender();
        $tender_id = 'wef9wef9we329m3209r434ks32ds34s3';
        $this->model->tender_id = $tender_id;
        $this->model->description = 'test test test test';
        $this->model->amount = 3333.22;
        $this->model->date_modified = Yii::$app->formatter->asDatetime('2023-07-15T14:21:01.746421', 'php:Y-m-d H:i:s');


        verify($this->model->save())->true();
        verify($this->model->errors)->empty();
        verify(Tender::find()->where(['tender_id' => $tender_id])->exists())->true();
    }

}
