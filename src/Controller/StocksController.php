<?php


namespace stockalignment\Controller;

use stockalignment\Database;
use stockalignment\Model\DatabaseModel;
use stockalignment\Controller;

use PDO;
// use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;
use Exception;

use stockalignment\Classes\SAP;

class StocksController extends Controller
{

    public $database = null;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function getStocks()
    {

        // echo "ddd" ;
        // print_r($_GET);

        $materialcode = $_GET["materialcode"];


        $SAPPort =  $this->database->getSAPPort();
        $SAPIP =  $this->database->getSAPIP();

        $clsSAP = new SAP();


        $param = "INVENTORY|" . $materialcode . "|" . "";

        // echo $param;

        $SAPUrl =  "" . $this->database->getSAPIP() . ":" . $this->database->getSAPPort() . "/sap/bc/webrfc";

        $params = array('_FUNCTION' => 'Z_FM_GETDATA', '_TCODE' => $param);
        //Execute command
        $sapData = $clsSAP->executeRFCToSAP($this->database->getSAPUser(), $this->database->getSAPPword(), $SAPUrl, $params);
        $sapReturn = $sapData["response"];

        print_r($sapReturn);
    }

    function getItemFromShopee()
    {

        $curl = curl_init();

        https: //intra.uratex.com.ph/?code=4575644f496d61556479566847556f6c&shop_id=322049526
        $shopID = '322049526';
        $accessss_token = '4575644f496d61556479566847556f6c';
        $itemID = '26352917437';
        $partnerID = '2010905';
        $path = "/api/v2/product/get_model_list";
        $partnerKey = "5a7255626646637a4e6751514e43685669684878736a4c465a737a624e564b58";


        $timest = time();
        $baseString = sprintf("%s%s%s", $partnerID, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);


        $URL = 'https://partner.shopeemobile.com/api/v2/product/get_model_list?access_token=' . $accessss_token . '&item_id=' . $itemID . '&partner_id=' . $partnerID . '&shop_id=' . $shopID . '&sign=' . $sign . '&timestamp=' . $timest . '';
        echo $URL;
        exit();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));


        $response = curl_exec($curl);
        // print_r($curl);
        curl_close($curl);
        echo $response;
    }
}
