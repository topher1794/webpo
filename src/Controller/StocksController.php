<?php


namespace stockalignment\Controller;

use stockalignment\Core\Database;
use stockalignment\Model\DatabaseModel;
use stockalignment\Controller;
use Lazada\LazopClient;
use Lazada\LazopRequest;

use PDO;
// use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;
use Exception;
use PhpParser\Node\Stmt\Echo_;
use stockalignment\Controller\AuthenticationController;

use stockalignment\Classes\SAP;


class StocksController extends Controller
{

    public $database = null;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }


    public function dashboard()
    {
        $data['logs'] = $_POST;
        $this->render('Template/header.php', $data);
        $this->render('Template/sidebar.php', $data);
        $this->render('Dashboard/dashboard.php', $data);
        $this->render('Template/footer.php', $data);
    }

    
    public function transactionlogs()
    {
        $data['logs'] = $_POST;
        $data['controller'] = "stocks";
        $data['action'] = "logs";

        $this->render('Template/header.php', $data);
        $this->render('Template/sidebar.php', $data);
        $this->render('Transaction/Lists.php', $data);
        $this->render('Template/footer.php', $data);
    }

    
    public function newsync()
    {
        $data['logs'] = $_POST;
        $data['controller'] = "stocks";
        $data['action'] = "newsync";


        $this->render('Template/header.php', $data);
        $this->render('Template/sidebar.php', $data);
        $this->render('Transaction/New.php', $data);
        $this->render('Template/footer.php', $data);
    }


    public function syncapi()
    {
        // get Bearer

        if (array_key_exists("Authorization", getallheaders())) {

            if (strpos(getallheaders()["Authorization"], "Bearer") === FALSE) {
                http_response_code(400);
                echo json_encode(["message" => "Bearer is required."]);
                exit();
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Authentication is required."]);
            exit();
        }

        $bearerToken = getallheaders()["Authorization"] ?? "";
        $bearerToken = str_replace("Bearer ", "", $bearerToken);
    }

    public function syncviaform()
    {


        $materialcode = $_POST["materialcode"] ?? "";
        $company = $_POST["company"] ??"";
        $userid = $_SESSION["userno"];
        $empname = $_SESSION["empname"];


        $stockQty = $this->getStocks($materialcode);

        if(empty($stockQty)){
            echo json_encode(array("result"=> "error", "message" => "Material code not found"));
            exit();
        }

        $stockArr = explode("<br>", $stockQty);
        $sQty = 0.0;
        foreach($stockArr as $arr){
            $arrData = explode("|", $arr);
            $qty = $arrData[2];
            $qty = trim($qty);
            $sQty += (double) $qty;
        }
        if(empty($stockQty)){
            echo json_encode(array("result"=> "error", "message" => "No quantity found"));
            exit();
        }


        $shopeeQty = $sQty * .60;
        $shopeeQty =  ceil($shopeeQty);
        $lazadaQty =  $sQty - $shopeeQty;
        $lazadaQty =  ceil($lazadaQty);



        $data = array(
            "userid" => $userid,
            "empname" => $empname,
            "materialcode" => $materialcode,
            "company" => $company,
            "shopee" => $shopeeQty,
            "lazada" => $lazadaQty,
        );

        $this->syncStock($data);


    }



    public function syncStock(array $data)
    {

        $pdo = $this->database->getPdo();

        /**
         * 
         * get user id who requested the sync and empname
         * 
         */

        $userid = $data["userid"];
        $empname = $data["empname"];
        $source = $data["source"];
        $materialcode = $data["materialcode"];
        $company = $data["company"];
        $shopeeQty = $data["shopee"];
        $lazadaQty = $data["lazada"];

        /**
         * insert into logs
        */
        $stmtUuid = $pdo->prepare("SELECT uuid() as uuid");
        $stmtUuid->execute();
        $uuid = $stmtUuid->fetch();

        $sql = "INSERT INTO StockAlignTransact(transactno, inputdate, materialcode, company, userid, status)VALUES(?, CURRENT_TIMESTAMP(), ?, ?, ? , ?) ";
        $statement = $pdo->prepare($sql);
        $statement->execute([$uuid["uuid"], $materialcode, $company, $userid, "OPEN"]);
        

        $stmtShopee = $pdo->prepare("SELECT productid FROM StockAlignSku WHERE accttype='SHOPEE' AND company = ? AND COALESCE(sku, parentsku) = ?");
        $stmtShopee->execute([ $materialcode, $company]);
        $shopee = $stmtShopee->fetch();
        
        $shopeeID = $shopee["productid"];

        if(!empty($shopeeID)) {
             //shopee
            $sql = "INSERT INTO StockAlignSync(transactno, syncno , materialcode, accttype, productid, qty, syncstatus)VALUES(
                ?, uuid(), ?, ?, ?, ?, ? ) ";
                $statement = $pdo->prepare($sql);
                $statement->execute([
                    $uuid["uuid"]
                    , $materialcode
                    , "SHOPEE"
                    , $shopeeID
                    , $shopeeQty
                    ,"OPEN"
                ]);
        }

        $stmtLazada = $pdo->prepare("SELECT productid FROM StockAlignSku WHERE accttype='LAZADA' AND company = ? AND COALESCE(sku, parentsku) = ?");
        $stmtLazada->execute([ $materialcode, $company]);
        $lazada = $stmtLazada->fetch();

        $lazadaID = $lazada["productid"];
        
        if(!empty($lazadaID)) {
             //shopee
            $sql = "INSERT INTO StockAlignSync(transactno, syncno , materialcode, accttype, productid, qty, syncstatus)VALUES(
                ?, uuid(), ?, ?, ?, ?, ? ) ";
                $statement = $pdo->prepare($sql);
                $statement->execute([
                    $uuid["uuid"]
                    , $materialcode
                    , "LAZADA"
                    , $lazadaID
                    , $lazadaQty
                    ,"OPEN"
                ]);
        }

    }

    public function syncEcomStock(string $user, int $shopeeQty, int $lazadaQty): bool
    {

        return true;
    }

    public function syncLazadaStock(string $transactId, int $qty): bool
    {
        return true;
    }

    public function syncShopeeStock(string $transactId, int $qty): bool
    {
        return true;
    }

    public function getStocks(string $materialcode)
    {

        // echo "ddd" ;
        // print_r($_GET);

        if(empty($materialcode)) {
            $materialcode = $_GET["materialcode"];
        }


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

        return $sapReturn;
    }


    public function stocktransaction() {
        $pdo = $this->database->getPdo();

        
        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 1;
        $length = $_POST['length'] ?? 1;

        $sql = "SELECT transactno, syncno, accttype, materialcode, productid, qty, syncstatus, payload, response, synctime 
        FROM 
        StockAlignSync 
        ";
        $stmt = $pdo->query($sql );
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $rowCount = count($data);
        $json_data = array(
          "draw"            =>  $draw,
          "recordsTotal"    => intval($rowCount),
          "recordsFiltered" => intval($rowCount),
          "data"            => $data
        );
        echo json_encode($json_data, JSON_PRETTY_PRINT);
    
    }


    function getAccessToken()
    {
        $host = "https://partner.shopeemobile.com";
        $path = "/api/v2/auth/token/get";

        $code = "4c644953694743467a56714d63625a51";
        $shopId = "322049526";
        $partnerId = "2010905";
        $partnerKey = "5a7255626646637a4e6751514e43685669684878736a4c465a737a624e564b58";

        // {
        //     "refresh_token": "424e446b6e6c6d725443766961697764",
        //     "access_token": "444d4f6b425770514a78626668477652",
        //     "expire_in": 14258,
        //     "request_id": "e3e3e7f3326413861e2351dcc9f24e00",
        //     "error": "",
        //     "message": ""
        //}

        // &shop_id=322049526

        $timest = time();
        $body = array("code" => $code,  "shop_id" => $shopId, "partner_id" => $partnerId);
        $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $host, $path, $partnerId, $timest, $sign);

        echo $url;

        echo json_encode($body);

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($c);
        echo "raw result: $resp";

        $ret = json_decode($resp, true);
        $accessToken = $ret["access_token"];
        $newRefreshToken = $ret["refresh_token"];
        echo "\naccess_token: $accessToken, refresh_token: $newRefreshToken raw: $ret" . "\n";
        return $ret;
    }

    function getItemFromShopee()
    {

        $curl = curl_init();

        //https: //intra.uratex.com.ph/?code=4575644f496d61556479566847556f6c&shop_id=322049526
        $shopID = '322049526';
        $code = '4575644f496d61556479566847556f6c';


        // {"shop_id":38862,
        //     "code":"5a5477794a55537954697169514f4653",
        //     "partner_id":1001141
        // }



        $itemID = '26352917437';
        $partnerID = '2010905';
        $path = "/api/v2/product/get_model_list";
        $partnerKey = "5a7255626646637a4e6751514e43685669684878736a4c465a737a624e564b58";


        $timest = time();
        $baseString = sprintf("%s%s%s%s%s", $partnerID, $path, $timest, $access_token, $shopID);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);


        $URL = 'curl --location --request GET \'https://partner.shopeemobile.com/api/v2/product/get_model_list?access_token=' . $access_token . '&item_id=' . $itemID . '&partner_id=' . $partnerID . '&shop_id=' . $shopID . '&sign=' . $sign . '&timestamp=' . $timest . '\'';
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
    function getAccessTokenLazada()
    {
        $url = "https://api.lazada.com/rest";
        $appkey = "133053";
        $appSecret = 'M4V8k89Nv5pYtT7eafdFlxfDZsDOSaBY';
        $code = '0_133053_BSLOsztup8ptwkoufwMNaYZD4348';

        $c = new LazopClient($url,  $appkey, $appSecret);
        $request = new LazopRequest('/auth/token/create');
        $request->addApiParam('code', $code);
        // $request->addApiParam('uuid', 'This field is currently invalid,  do not use this field please');
        var_dump($c->execute($request));

        print_r("sfsf ");
    }
}
