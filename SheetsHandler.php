<?php

namespace Sheets;

require_once __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;
use Google\Service\Exception;
use Google\Service\Sheets as GoogleSheets;
use GuzzleHttp\Client as HttpHandler;


/**
 * Enviroment variable, necessary to authorize the API acess.
 */

putenv('GOOGLE_APPLICATION_CREDENTIALS=config.json');

class SheetsHandler
{

    /**
     * The sheet id that you can get from the URL.
     * 
     * @param string
     */

    private string $sheetId;

    /**
     * The sheet cell or column interval. Defines the region that the values are gonna be obtained.
     * 
     * @param string
     */
    private string $rangeSheet;



    /**
     * Contructor
     * 
     * @param string
     * @param string
     * 
     */
    public function __construct(string $sheetId, string $rangeSheet)
    {
        $this->sheetId = $sheetId;
        $this->rangeSheet = $rangeSheet;
    }

    /**
     * Makes the connection with the Google API.
     * 
     * @return Client|Exception
     */

    public function connectToGoogle(): Client|Exception
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

    /**
     * Get the given cell or column interval
     * 
     * @return Object|bool
     */

    public function getRowsFromSheet(): Object|bool
    {
        $service = new GoogleSheets($this->connectToGoogle());
        $result = $service->spreadsheets_values->get($this->sheetId, $this->rangeSheet);

        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * Handle the received results
     *
     * @return array
     */

    public function handleRowsResult()
    {
        $result = $this->getRowsFromSheet();

        if (!$result) {
            return "Ocorreu um erro ao obter os valores!";
        }

        return $result->values;
    }
}
