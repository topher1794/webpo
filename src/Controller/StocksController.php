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

class StocksController extends Controller {

    public $database = null;

    public function __construct() {
        $this->database = Database::getInstance();       
    }

    public function getStocks(){

        // echo "ddd" ;
        // print_r($_GET);

        $materialcode = $_GET["materialcode"];


        $SAPPort =  $this->database->getSAPPort();
        $SAPIP =  $this->database->getSAPIP();

        $clsSAP = new SAP();


        $param = "INVENTORY|".$materialcode ."|"."" ;

        // echo $param;

        $SAPUrl =  "" . $this->database->getSAPIP() . ":" . $this->database->getSAPPort() . "/sap/bc/webrfc";

        $params = array('_FUNCTION' => 'Z_FM_GETDATA', '_TCODE' => $param);
        //Execute command
        $sapData = $clsSAP->executeRFCToSAP($this->database->getSAPUser(), $this->database->getSAPPword(), $SAPUrl, $params);
        $sapReturn = $sapData["response"];

        print_r($sapReturn ) ;
    }




}