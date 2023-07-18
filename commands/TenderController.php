<?php

namespace app\commands;

use app\models\Tender;
use app\services\tenderApi\exceptions\ApiException;
use yii\console\Controller;
use yii\console\ExitCode;

use app\services\tenderApi\TenderApi;
use Yii;
use yii\helpers\Console;


class TenderController extends Controller
{
    protected $tenderApi;
    protected $logCategory;

    public function __construct($id, $module, TenderApi $tenderApi, $config = [])
    {
        $this->tenderApi = $tenderApi;
        $this->logCategory = 'tender';
        parent::__construct($id, $module, $config);
    }

    /**
     * This command upload tenders data to local db.
     * @param int $pages the count of fetched page.
     * @param int $limit the max number of tenders per page
     * @return int Exit code
     */
    public function actionIndex(int $pages = 10, int $limit = null): int
    {
        $tendersCount = 0;

        Yii::info('Started uploading tenders', $this->logCategory);

        if($limit){
            $this->tenderApi->setLimit($limit);
        }

        $this->setupTenderOffser();

        try{
            foreach($this->tenderApi->getTenders($pages, false) as $tender){
                try {
                    $this->saveTender($tender);
                    $tendersCount++;
                } catch (\Exception $saveException) {
                    Yii::error('Failed to save tender: ' . $saveException->getMessage(), $this->logCategory);
                }
            }

            Yii::$app->cache->set('tender_api_offset', $this->tenderApi->getOffset());
        }
        catch(ApiException $apiException){
            Yii::error('Failed to load tenders: ' . $apiException->getMessage(), $this->logCategory);

            return ExitCode::UNAVAILABLE;
        }
        finally{
            $calculatedCountTenders = $pages * $this->tenderApi->getLimit();
            $finallyMessage = "Upload {$tendersCount} out of {$calculatedCountTenders} tender(s).\n";
            
            $this->stdout("\n{$finallyMessage}", Console::FG_GREEN);
            Yii::info($finallyMessage, $this->logCategory);
        }

        return ExitCode::OK;
    }

    /**
     * Setting starting offset by cache value if exists or by default value
     *
     * @return void
     */
    private function setupTenderOffser()
    {
        $offset = Yii::$app->cache->getOrSet('tender_api_offset', function(){
            $secondsOfDay = 84000;
            return microtime(true) - $secondsOfDay;
        });

        $this->tenderApi->setOffset($offset);
    }

    /**
     * Save tender to tender model
     *
     * @param array $tender The array of tender data
     * @return void
     */
    private function saveTender(array $tender)
    {
        $tenderModel = new Tender();
        $tenderModel->tender_id = $tender['tenderId'];
        $tenderModel->description = $tender['description'];
        $tenderModel->amount = $tender['amount'];
        $tenderModel->date_modified = Yii::$app->formatter->asDatetime($tender['dateModified'], 'php:Y-m-d H:i:s');

        return $tenderModel->save();
    }
}
