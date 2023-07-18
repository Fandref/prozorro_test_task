<?php

namespace tests\unit\services;

use app\services\tenderApi\exceptions\ApiException;
use app\services\tenderApi\TenderApi;

class TenderApiTest extends \Codeception\Test\Unit
{
    private $tenderApi;
    private $offset;
    private $limit;

    protected function _before()
    {
        $this->tenderApi = new TenderApi();
        $this->offset = 1689183474.225;
        $this->limit = 1;
        $this->tenderApi->setOffset($this->offset);
        $this->tenderApi->setLimit($this->limit);

    }

    public function testGetTendersIds()
    {
        $tendersIds = [
            '8d7c56c5b42f455e8f2286c7c1d866f8',
            '8e907c66cbe044cb83d76c1ae6864cb0',
            'e54fc8a2b6c54336a720f189598bfc31',
            'c77ce1dd39224adba7f1e28427425d5c',
            'f778376e4cb34591bbf5cf5b223626d4',
            '879444743f864ec299d94f7abb2923cf',
            '945c573a2c3f4049b41742e51754e149',
            '8ea877dd2a2248feb724d56c7fcd0011',
            'eae4fa398e2c46e7aef28796330d7920',
            '02d1a026335745ca8b03f70bc2c026e8'
        ];

        verify($this->tenderApi->getTendersIds(10, false))->equals($tendersIds);
        verify($this->tenderApi->getOffset())->equals(1689183717.278);
    }

    public function testGetTenderByCorrectId(){
        $tender = [
            'tenderId' => "8d7c56c5b42f455e8f2286c7c1d866f8",
            'description' => "Код ДК 021-2015-45450000-6 - Інші завершальні будівельні роботи (Поточний ремонт електроосвітлення приміщень майстерень в Комунальному закладі «Ліцей «Лідер» Кропивницької міської ради», за адресою: пров. Фортечний, 7, м. Кропивницький)",
            'amount' => 476000.0,
            'currency' => "UAH",
            'dateModified' => "2023-07-12T20:38:19.741964+03:00"
        ];

        verify($this->tenderApi->getTender($tender['tenderId']))->equals($tender);
    }

    public function testGetTenderByUncorrectId(){
        $tenderId = 'wefewfwewefwe';
        $this->expectException(ApiException::class);
        $this->tenderApi->getTender($tenderId);
    }

    public function testGetLimit(){
        verify($this->tenderApi->getLimit())->equals($this->limit);
    }

    public function testGetOffset(){
        verify($this->tenderApi->getOffset())->equals($this->offset);
    }

    public function testSetOffsetUncorrect(){
        $this->expectError();
        $this->tenderApi->setOffset('d');
    }

    public function testGetTenders(){
        $tenders = $this->tenderApi->getTenders(1);
        $result = iterator_to_array($tenders);
        

        verify(count($result))->equals(1);

        verify($result[0])->arrayHasKey('tenderId');
        verify($result[0])->arrayHasKey('description');
        verify($result[0])->arrayHasKey('amount');
        verify($result[0])->arrayHasKey('currency');
        verify($result[0])->arrayHasKey('dateModified');
    }
}
