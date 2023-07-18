<?php

namespace app\services\tenderApi;

use app\services\tenderApi\exceptions\ApiException;
use Error;
use Exception;
use yii\httpclient\Request;
use yii\httpclient\Response;
use yii\httpclient\Client;
use yii\httpclient\Exception as ClienException;

class TenderApi
{
    private Client $client;
    private int $limit;
    private float $offset;


    public function __construct()
    {
        $this->client = new Client([
            'baseUrl' => 'https://public.api.openprocurement.org/api/0',
            'requestConfig' => [
                'format' => Client::FORMAT_JSON
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ]
        ]);
        $this->limit = 5;
    }

    /**
     * This method for upload tenders. Also this method use object fields `limit` and `offset`
     *
     * @param integer $pageCount The count page for upload
     * @param bool $descending The sort in descending order
     * @return \Generator<array> The generator with tender data
     */
    public function getTenders(int $pageCount = 10, bool $descending = true)
    {
        $tenderIds = $this->getTendersIds($pageCount, $descending);

        foreach($tenderIds as $tenderId){
            yield $this->getTender($tenderId);
        }
    }

    /**
     * This method for upload array of tenders ids. Also this method use object fields `limit` and `offset`
     *
     * @param integer $pageCount The count page for upload
     * @param boolean $descending The sort in descending order
     * @return array The array of tenders ids
     */
    public function getTendersIds(int $pageCount = 10, bool $descending = true): array
    {
        if($pageCount <= 0){
            throw new Error('page count must be 1 or more');
        }

        $params = [
            'limit' => $this->limit
        ];
        $tendersId = [];
        
        if($this->offset){
            $params['offset'] = $this->offset;
        }
        
        if($descending){
            $params['descending'] = 1;
        }

        while($pageCount>0){
            $response = $this->sendRequest($this->client->get(['tenders', ...$params]));

            if(!$response->isOk){
                throw new ApiException(
                    $response->data['errors'][0]['description'], 
                    $response->statusCode
                );
            }

            foreach($response->data['data'] as $tenderInfo){
                $tendersId[] = $tenderInfo['id'];
            }
            
            $params['offset'] = $response->data['next_page']['offset'];
            $this->setOffset($params['offset']);
            $pageCount--;
        }

        return $tendersId;
    }

    /**
     * Get tender by tender id
     *
     * @param sting $id The tender id
     * @return array The array of tender fields: `tenderId`, `description`, `amount`, `currency`, `dateModified`
     */
    public function getTender(string $id): array
    {
        $response = $this->sendRequest($this->client->get("tenders/{$id}"));

        if(!$response->isOk){
            throw new ApiException(
                "{$response->data['errors'][0]['description']}. tender id: {$id}. ",
                $response->statusCode
            );
        }

        $tenderData = $response->data['data'];
        $descriptionCondition = array_key_exists('description', $tenderData) && strlen($tenderData['description']) > 0;

        return [
            'tenderId' => $tenderData['id'],
            'description' => $descriptionCondition ? $tenderData['description'] : $tenderData['title'],
            'amount' => array_key_exists('value', $tenderData) ? $tenderData['value']['amount'] : 0,
            'currency' => array_key_exists('value', $tenderData) ? $tenderData['value']['currency']: 'UAH',
            'dateModified' => $tenderData['dateModified']
        ];
        
    }
    
    /**
     * Send request to api and if throw `ClientException` convert it to `ApiException` and throw it.
     *
     * @param Request $request
     * @return Response
     */
    private function sendRequest(Request $request): Response
    {
        try{
            return $request->send();
        }
        catch(ClienException $e){
            throw new ApiException($e->getMessage(), 500);
        }
    }

    /**
     * Setter offset
     *
     * @param float|null $timestamp The timestamp for offsetting by change date of tender.
     * @return void
     */
    public function setOffset(float $timestamp = null)
    {
        if(
            $timestamp !== null
            && preg_match('/[^\.\d]+/', (string) $timestamp)
        ){
            throw new Error('The offset must be set to a timestamp no greater than the current time or can be null');
        }

        $this->offset = (float) $timestamp;
    }

    /**
     * Offset getter
     *
     * @return float The timestamp for offsetting by change date of tender.
     */
    public function getOffset(){
        return $this->offset;
    }

    /**
     * Limit setter. Range between from 1 to 50
     *
     * @param integer $limit The limit of tenders on one page
     * @return void
     */
    public function setLimit(int $limit)
    {
        if($limit <= 0){
            throw new Error('limit must be 1 or more');
        }

        if($limit > 50){
            throw new Error('limit cannot exceed 50');
        }

        $this->limit = $limit;
    }
    
    /**
     * Limit getter
     *
     * @return int The limit of tenders on one page
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

}