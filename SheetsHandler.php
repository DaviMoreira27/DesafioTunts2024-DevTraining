<?php

namespace Sheets;

require_once __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;
use Google\Service\Exception;
use Google\Service\Sheets as GoogleSheets;
use GuzzleHttp\Client as HttpHandler;

putenv('GOOGLE_APPLICATION_CREDENTIALS=config.json');

class SheetsHandler
{

    private string $sheetId;
    private string $rangeSheet;

    public function __construct(string $sheetId, string $rangeSheet)
    {
        $this->sheetId = $sheetId;
        $this->rangeSheet = $rangeSheet;
    }

    public function connectToGoogle(): Client
    {
        $client = new Client();

        try {
            $client->useApplicationDefaultCredentials();
            $guzzleClient = new HttpHandler(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));
            $client->setHttpClient($guzzleClient);
            $client->addScope(Drive::DRIVE);
            $client->setApprovalPrompt('force');
        } catch (Exception $e) {
            return $e->getMessage();
        }


        return $client;
    }


    public function getRowsFromSheet()
    {
        $service = new GoogleSheets($this->connectToGoogle());
        $result = $service->spreadsheets_values->get($this->sheetId, $this->rangeSheet);
        
        if($result){
            return $result;
        }

        return false;
    }


    public function handleRowsResult(){
        $result = $this->getRowsFromSheet();

        if(!$result){
            return "Ocorreu um erro ao obter os valores!";
        }

        return $result->values;
    }
}
