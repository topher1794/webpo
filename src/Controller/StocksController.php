<?php


namespace stockalignment\Controller;

use stockalignment\Core\Database;
use stockalignment\Model\DatabaseModel;
use stockalignment\Controller;
use Lazada\LazopClient;
use Lazada\LazopRequest;
use DateTime;

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

        $pdo = $this->database->getPdo();

        $bearerToken = getallheaders()["Authorization"] ?? "";
        $bearerToken = str_replace("Bearer ", "", $bearerToken);

        // print_r($bearerToken);

        $decodeToken = base64_decode($bearerToken);

        $explode2 = explode(":", $decodeToken, 2);
        $param1 = $explode2[0];
        $password = $explode2[1];

        $stmt = $pdo->prepare("SELECT id, bcrypt_pass as password, empname FROM StockAlignUsers WHERE username = :username");
        $stmt->bindParam(':username', $param1);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $stored_hash = $user['password'];


        if (!password_verify($password, $stored_hash)) {
            http_response_code(400);
            echo json_encode(["message" => "Invalid authentication."], JSON_PRETTY_PRINT);
            exit();
        }

        $userid = $user['id'];
        $empname = $user['empname'];
        if (empty($userid)) {
            http_response_code(400);
            echo json_encode(["message" => "User not found."], JSON_PRETTY_PRINT);
            exit();
        }
        $access_token = $_POST["access_token"];
        if (empty($access_token)) {
            http_response_code(400);
            echo json_encode(["message" => "Access Token not found."], JSON_PRETTY_PRINT);
            exit();
        }


        $stmt = $pdo->prepare("SELECT date(expireddate) as expireddate , access_token FROM StockAlignTokens WHERE userid = :userid AND access_token = :access_token AND status ");
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':access_token', $access_token);
        $stmt->execute();
        $arrayToken = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($arrayToken)) {
            http_response_code(400);
            echo json_encode(["message" => "Access token not found."], JSON_PRETTY_PRINT);
            exit();
        }

        $today = strtotime(date("Y-m-d"));
        $convExpired = strtotime($arrayToken["expireddate"]);

        if ((int)$today > (int)$convExpired) {
            http_response_code(400);
            echo json_encode(["message" => "Token is expired."], JSON_PRETTY_PRINT);
            exit();
        }

        $materialcode = $_POST["materialcode"] ?? "";
        $company = $_POST["company"] ?? "";

        $arrayData = array(
            "materialcode" => $materialcode,
            "company" => $company,
            "userid" => $userid,
            "source" => "api",
            "empname" => $empname
        );
        $this->syncStockQty($arrayData);
    }

    public function syncviaform()
    {
        $materialcode = $_POST["materialcode"] ?? "";
        $company = $_POST["company"] ?? "";
        $userid = $_SESSION["userno"];
        $empname = $_SESSION["empname"];

        $arrayData = array(
            "materialcode" => $materialcode,
            "company" => $company,
            "userid" => $userid,
            "source" => "form",
            "empname" => $empname
        );

        $this->syncStockQty($arrayData);
    }


    public function syncStockQty(array $arrayData)
    {
        $materialcode = $arrayData["materialcode"];
        $userid = $arrayData["userid"];
        $empname = $arrayData["empname"];
        $company = $arrayData["company"];

        print_r($materialcode);
        if (empty($materialcode)) {
            echo json_encode(array("result" => "error", "message" => "Material code is empty."));
            exit();
        }

        $stockQty = $this->getStocks($materialcode);
        $stockQty = $this->getStocks($materialcode);

        if (empty($stockQty)) {
            echo json_encode(array("result" => "error", "message" => "Material code not found"));
            exit();
        }

        $stockArr = explode("<br>", $stockQty);
        $sQty = 0.0;
        foreach ($stockArr as $arr) {
            $arrData = explode("|", $arr);
            $qty = $arrData[2];
            $qty = trim($qty);
            $sQty += (float) $qty;
        }
        if (empty($stockQty)) {
            echo json_encode(array("result" => "error", "message" => "No quantity found"));
            exit();
        }


        $shopeeQty = $sQty * .65;
        $shopeeQty =  ceil($shopeeQty);
        $lazadaQty =  $sQty - $shopeeQty;


        // $data = array(
        //     "userid" => $userid,
        //     "empname" => $empname,
        //     "materialcode" => $materialcode,
        //     "company" => $company,
        //     "shopee" => $shopeeQty,
        //     "lazada" => $lazadaQty,
        // );

        $arrayData["shopee"] = $shopeeQty;
        $arrayData["lazada"] = $lazadaQty;
        $this->syncStock($arrayData);
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

        $sql = "INSERT INTO StockAlignTransact(transactno, inputdate, materialcode, company, userid, status, source)VALUES(?, CURRENT_TIMESTAMP(), ?, ?, ? , ?, ?) ";
        $statement = $pdo->prepare($sql);
        $statement->execute([$uuid["uuid"], $materialcode, $company, $userid, "OPEN", $source]);

        try {
            $stmtShopee = $pdo->prepare("SELECT productid, skuid FROM StockAlignSku WHERE accttype='SHOPEE' AND company = ? AND COALESCE(sku, parentsku) = ?");
            $stmtShopee->execute([$company, $materialcode]);
            $stmtShopee->execute();
            $shopee = $stmtShopee->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            print_r($e);
        }


        $shopeeID = $shopee["productid"];

        if (!empty($shopeeID)) {
            //shopee
            $modelID = $shopee["skuid"];

            $sql = "INSERT INTO StockAlignSync(transactno, syncno , materialcode, accttype, productid, qty, syncstatus, modelid)VALUES(
                ?, uuid(), ?, ?, ?, ?, ? , ?) ";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                $uuid["uuid"],
                $materialcode,
                "SHOPEE",
                $shopeeID,
                $shopeeQty,
                "OPEN",
                $modelID
            ]);
        }

        $stmtLazada = $pdo->prepare("SELECT productid FROM StockAlignSku WHERE accttype='LAZADA' AND company = ? AND COALESCE(sku, parentsku) = ?");
        $stmtLazada->execute([$company, $materialcode]);
        $lazada = $stmtLazada->fetch(PDO::FETCH_ASSOC);

        $lazadaID = $lazada["productid"];



        if (!empty($lazadaID)) {
            //shopee
            $sql = "INSERT INTO StockAlignSync(transactno, syncno , materialcode, accttype, productid, qty, syncstatus)VALUES(
                ?, uuid(), ?, ?, ?, ?, ? ) ";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                $uuid["uuid"],
                $materialcode,
                "LAZADA",
                $lazadaID,
                $lazadaQty,
                "OPEN"
            ]);
        }

        // check shopee token | qty
        $shopeeStock = 0;
        $jsonQty = $this->getStocksFromShopee($shopeeID);
        $jsonDecodeShopee = json_decode($jsonQty, true);
        if (isset($jsonDecodeShopee["message"])) {
            if (strpos($jsonDecodeShopee["message"], "Invalid access_token") !== false) {
                $this->refreshShopeeToken();
                //set when expired
                $jsonQty = $this->getStocksFromShopee($shopeeID);
                $jsonDecodeShopee = json_decode($jsonQty, true);
            }
            $model = $jsonDecodeShopee["response"]["model"];
            foreach ($model as $i => $val) {
                $valModelId = $val["model_id"];
                if ($valModelId == $shopee["skuid"]) {
                    $shopeeStock = $val["stock_info_v2"]["summary_info"]["total_available_stock"];
                    $sql = "UPDATE StockAlignSync SET orig_qty = ? WHERE transactno= ? and accttype = 'SHOPEE'";
                    $statement = $pdo->prepare($sql);
                    $statement->execute([
                        $shopeeStock,
                        $uuid["uuid"]
                    ]);
                    break;
                }
            }
        }

        //check lazada token | qty
        $lazadaStock = 0;
        $jsonDecodeLazada = $this->getLazadaItem($lazadaID, $materialcode);
        if (isset($jsonDecodeLazada)) {
            if (isset($jsonDecodeLazada["message"]) && strpos($jsonDecodeLazada["message"], "A facade root has not been set") !== false) {
                $this->refreshLazadaToken();
                $jsonDecodeLazada = $this->getLazadaItem($lazadaID, $materialcode);
            }
            $qty = $jsonDecodeLazada['data']['skus'];
            for ($x = 0; $x < count($qty); $x++) {
                if ($qty[$x]['SellerSku'] == $materialcode) {
                    $lazadaStock = $qty[$x]['multiWarehouseInventories'][0]['sellableQuantity'];
                    break;
                }
            }
            $sql = "UPDATE StockAlignSync SET orig_qty = ?  WHERE transactno = ? AND accttype = ?";
            $sql = $pdo->prepare($sql);
            $sql->execute([$lazadaStock,  $uuid["uuid"], 'LAZADA']);
        }




        $shopeeQty = 7; //ORIGINAL
        $lazadaQty = 3; //ORIGINAL
        $this->syncShopeeStock($uuid["uuid"], $shopeeQty);
        $this->syncLazadaStock($uuid["uuid"], $lazadaQty);
    }

    public function syncEcomStock(string $user, int $shopeeQty, int $lazadaQty): bool
    {

        return true;
    }

    public function syncLazadaStock(string $transactId, int $qty): bool
    {
        $pdo = $this->database->getPdo();
        $response = "";

        $lazadaVal = $this->selectValues('lazada'); // get shopee requirements
        $url = "https://api.lazada.com.ph/rest"; // ORIG

        $sql = "SELECT productid, materialcode FROM StockAlignSync WHERE transactno = ? AND accttype = ?";
        $sql = $pdo->prepare($sql);
        $sql->execute([$transactId, 'LAZADA']);
        $productID = $sql->fetch();




        $getLazadaRequirements = "SELECT skuid, sku FROM StockAlignSku WHERE productid = ? AND sku = ?";
        $getLazadaRequirements = $pdo->prepare($getLazadaRequirements);
        $getLazadaRequirements->execute([$productID['productid'], $productID['materialcode']]);
        $lazadaValues = $getLazadaRequirements->fetch();

        //  payload
        $xml = "
            <Request>   
                <Product>      
                    <Skus>   
                        <!--single warehouse demo-->  
                        <Sku>         
                            <ItemId>" . $productID['productid'] . "</ItemId>         
                            <SkuId>" . $lazadaValues['skuid'] . "</SkuId>         
                            <SellerSku>" . $lazadaValues['sku'] . "</SellerSku>                                     
                            <SellableQuantity>" . $qty . "</SellableQuantity>    
                        </Sku>   
                        <!--multi warehouse demo-->   
                        <Sku>         
                            <ItemId></ItemId>         
                            <SkuId></SkuId>         
                            <SellerSku></SellerSku>                
                            <MultiWarehouseInventories>
                                <MultiWarehouseInventory>             
                                    <WarehouseCode></WarehouseCode>             
                                    <SellableQuantity></SellableQuantity>           
                                </MultiWarehouseInventory>           
                                <MultiWarehouseInventory>             
                                    <WarehouseCode></WarehouseCode>             
                                    <SellableQuantity></SellableQuantity>           
                                </MultiWarehouseInventory>          
                            </MultiWarehouseInventories>        
                        </Sku>   
                    </Skus>   
                </Product> 
            </Request>
        ";

        $sql = "UPDATE StockAlignSync SET payload = ? WHERE transactno = ? AND accttype = ?";
        $sql = $pdo->prepare($sql);
        $sql->execute([$xml, $transactId, 'LAZADA']);

        try {
            $c = new LazopClient($url, $lazadaVal['appkey'], $lazadaVal['appSecret']);
            $request = new LazopRequest('/product/stock/sellable/update');
            $request->addApiParam('payload', $xml);
            $response = $c->execute($request, $lazadaVal['access_token']);
            // $response = $c->execute($request, $oldAccessToken);

            // $sql = "UPDATE StockAlignSync SET response = ?, synctime = current_timestamp, syncstatus = ? WHERE transactno = ? AND accttype = ?";
            $sql = "UPDATE 
                        StockAlignSync sas 
                    INNER JOIN 
                        StockAlignTransact sat 
                    ON 
                        sas.transactno = sat.transactno 
                    SET 
                        sas.response = ?
                        ,sas.synctime = current_timestamp
                        ,sas.syncstatus = ?
                        ,sat.completedate = current_timestamp
                        ,sat.status = ? 
                    WHERE 
                        sas.transactno = ? 
                    AND 
                        sas.accttype = ?
            ";
            $sql = $pdo->prepare($sql);
            $sql->execute([$response, 'CLOSED', 'CLOSED', $transactId, 'LAZADA']);
        } catch (\Exception $e) {
            print_r($e);
        }


        return true;
    }

    public function getLazadaItem($productID, $sku)
    {

        $pdo = $this->database->getPdo();

        $lazadaVal = $this->selectValues('lazada');

        $url = "https://api.lazada.com.ph/rest";
        $app_key = $lazadaVal['appkey'];
        $appSecret = $lazadaVal['appSecret'];
        $access_token = $lazadaVal['access_token'];
        $origQty = 0;
        $json = null;
        try {
            $c = new LazopClient($url, $app_key, $appSecret);
            $request = new LazopRequest('/product/item/get', 'GET');
            $request->addApiParam('item_id', $productID);
            $request->addApiParam('seller_sku', $sku);
            $result = $c->execute($request, $access_token);
            $json = json_decode($result, true);



            // return $origQty;


        } catch (\Exception $e) {
            // print_r($e->getMessage());
            // print_r($e->getTraceAsString()[0]);
            // print_r($e[0]["message"]);-
            $json = array("message" => "A facade root has not been set");
        }
        return $json;
    }

    public function refreshLazadaToken()
    {

        $pdo = $this->database->getPdo();
        $lazadaVal = $this->selectValues('lazada');

        $url = "https://api.lazada.com.ph/rest";
        $appkey = $lazadaVal['appkey'];
        $appSecret = $lazadaVal['appSecret'];

        try {
            $c = new LazopClient($url, $appkey, $appSecret);
            $request = new LazopRequest('/auth/token/refresh');
            $request->addApiParam('refresh_token', $lazadaVal['refresh_token']);
            $response = json_decode($c->execute($request), true);

            $sql = $pdo->prepare("UPDATE StockAlignSettings SET `attributes` = JSON_SET(`attributes`, '$.access_token', ?, '$.refresh_token', ?) WHERE settingstype = ?");
            $sql->execute([$response['access_token'], $response['refresh_token'], 'rob_lazada_value']);
        } catch (\Exception $e) {
            print_r($e);
        }
    }


    public function syncShopeeStock(string $transactId, int $qty): bool
    {

        $pdo = $this->database->getPdo();

        $shopeeVal = $this->selectValues('shopee'); // get shopee requirements

        $path = "/api/v2/product/update_stock";
        $partnerId = $shopeeVal['partnerID'];
        $partnerKey = $shopeeVal['partnerKey'];
        $access_token = $shopeeVal['access_token'];
        $shopid = $shopeeVal['shopID'];

        $timest = time();
        $baseString = sprintf("%s%s%s%s%s", $partnerId, $path, $timest, $access_token, $shopid);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);

        // get product ID
        $getProductId = "SELECT productid, modelid FROM StockAlignSync WHERE transactno = ?";
        $getProductId = $pdo->prepare($getProductId);
        $getProductId->execute([$transactId]);
        $productId = $getProductId->fetch();

        $payload = '
        {
            "item_id": ' . $productId['productid'] . ',
            "stock_list": [
                {
                    "model_id": ' . $productId['modelid'] . ',
            
                    "seller_stock": [
                        {
                            "stock": ' . $qty . '
                        }
                    ]
                }
            ]
        }';




        try {
            // payload
            $curl = curl_init();
            $url = 'https://partner.shopeemobile.com/' . $path . '?access_token=' . $shopeeVal['access_token'] . '&partner_id=' . $shopeeVal['partnerID'] . '&shop_id=' . (int)$shopeeVal['shopID'] . '&sign=' . $sign . '&timestamp=' . $timest . '';
            echo $url;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));


            $sql = "UPDATE StockAlignSync SET payload = ? WHERE transactno = ? AND accttype = ?";
            $sql = $pdo->prepare($sql);
            $sql->execute([$payload, $transactId, 'SHOPEE']);


            // $response = curl_exec($curl);
            $response = curl_exec($curl);

            curl_close($curl);

            $sql = "UPDATE StockAlignSync SET response = ?, synctime = current_timestamp WHERE transactno = ? AND accttype = ?";
            $sql = $pdo->prepare($sql);
            $sql->execute([$response, $transactId, 'SHOPEE']);
        } catch (\Exception $e) {
            print_r($e);
        }




        return true;
    }

    public function getStocksFromShopee($itemID)
    {


        $pdo = $this->database->getPdo();

        $shopeeVal = $this->selectValues('shopee'); // get shopee requirements

        $path = "/api/v2/product/get_model_list";
        $partnerId = $shopeeVal['partnerID'];
        $partnerKey = $shopeeVal['partnerKey'];
        $access_token = $shopeeVal['access_token'];
        $shopid = $shopeeVal['shopID'];


        $timest = time();
        $baseString = sprintf("%s%s%s%s%s", $partnerId, $path, $timest, $access_token, $shopid);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);


        $url = "https://partner.shopeemobile.com$path";
        $query = http_build_query([
            'partner_id'   => $partnerId,
            'timestamp'    => $timest,
            'access_token' => urlencode($access_token),
            'shop_id'      => $shopid,
            'sign'         => $sign,
            'item_id' => $itemID
        ]);


        try {

            $ch = curl_init();

            // Setup cURL options for GET
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // to skip permission
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                echo "cURL Error: $error";
                return $error;
            } else {
                return $response;
            }
        } catch (\Exception $e) {
            // print_r($e);
        }
    }

    public function refreshShopeeToken()
    {
        $pdo = $this->database->getPdo();

        $shopeeVal = $this->selectValues('shopee'); // get shopee requirements

        $path = "/api/v2/auth/access_token/get";
        $partnerId = $shopeeVal['partnerID'];
        $partnerKey = $shopeeVal['partnerKey'];
        $access_token = $shopeeVal['access_token'];
        $refresh_token = $shopeeVal['refresh_token'];
        $shopid = $shopeeVal['shopID'];

        // $refresh_token = "74706e756e6f5a674259496a46545a50";


        $timestamp = time();
        $baseString = sprintf("%s%s%s%s", $partnerId, $path, $timestamp, $shopid);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);

        $url = "https://partner.shopeemobile.com$path";
        $query = http_build_query([
            'partner_id' => $partnerId,
            'timestamp' => $timestamp,
            'shop_id' => $shopid,
            'sign' => $sign
        ]);

        $body = json_encode([
            'shop_id' =>  (int)$shopid,
            'refresh_token' =>  $refresh_token,
            'partner_id' =>  (int)$partnerId,

        ]);

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . "?" .  $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // to skip permission

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);


            $response = json_decode($response, true);


            $sql = $pdo->prepare("UPDATE StockAlignSettings SET `attributes` = JSON_SET(`attributes`, '$.access_token', ?, '$.refresh_token', ?) WHERE settingstype = ?");
            $sql->execute([$response['access_token'], $response['refresh_token'], 'rob_shopee_value']);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    public function getStocks(string $materialcode)
    {

        // echo "ddd" ;
        // print_r($_GET);

        if (empty($materialcode)) {
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


    public function stocktransaction()
    {
        $pdo = $this->database->getPdo();


        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 1;
        $length = $_POST['length'] ?? 1;
        $concat = "";

        $from = $_POST['from'] ?? "";
        $to = $_POST['to'] ?? "";

        $page = $_POST['page'] ?? "";

        if (!empty($from) && !empty($to)) {
            $concat = "AND (DATE(inputdate) BETWEEN '" . $from . "' AND '" . $to . "')";
        }

        $sql = "SELECT 
                    inputdate
                    ,transactno
                    ,materialcode
                    ,company
                    ,source
                    ,(SELECT 
                        CONCAT(firstname, ' ',lastname) as Name 
                    FROM 
                        StockAlignUsers 
                    WHERE 
                        id = '" . $_SESSION['userno'] . "'
                    ) as user
                    ,completedate 
                FROM 
                    StockAlignTransact 
                WHERE 
                    status = '" . $page . "'
                    " . $concat . "
                ORDER BY
                    inputdate DESC
                    ";
        $stmt = $pdo->query($sql);
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


    public function swaggerapi()
    {
        $data['logs'] = $_POST;
        $data['controller'] = "stocks";
        $data['action'] = "api";

        $this->render('Swagger/api.php', $data);
    }


    function getAccessToken()
    {
        $host = "https://partner.shopeemobile.com";
        $path = "/api/v2/auth/token/get";

        // $code = "4c644953694743467a56714d63625a51";
        $code = "53624d53714f7251525a694c55796565";
        $shopId = 322049526;
        $partnerId = 2010905;
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
        $url = "https://api.lazada.com.ph/rest";
        $appkey = "133053";
        $appSecret = 'M4V8k89Nv5pYtT7eafdFlxfDZsDOSaBY';
        $code = '0_133053_5DO2OQvhWFXJAWSRvtMeMf2l7432';

        $c = new LazopClient($url,  $appkey, $appSecret);
        $request = new LazopRequest('/auth/token/create');
        $request->addApiParam('code', $code);
        // $request->addApiParam('uuid', 'This field is currently invalid,  do not use this field please');
        var_dump($c->execute($request));

        print_r("sfsf ");
    }

    public function selectValues(String $settingsVal)
    {

        $pdo = $this->database->getPdo();

        $settingsVal = ($settingsVal == 'shopee' ? 'rob_shopee_value' : 'rob_lazada_value');

        $sql = "SELECT attributes FROM StockAlignSettings WHERE settingstype = '" . $settingsVal . "'";

        $sql = $pdo->prepare($sql);
        $sql->execute();
        $values = $sql->fetch();
        return json_decode($values['attributes'], true);
    }

    public function getDetails()
    {
        $pdo = $this->database->getPdo();

        $transactNo = $_POST['transactNo'] ?? "";
        $sku = $_POST['sku'] ?? "";
        $draw = $_POST['draw'] ?? 1;


        $sql = "SELECT 
                    accttype
                    ,productid
                    ,materialcode
                    ,modelid
                    ,(SELECT 
                        productname 
                    FROM 
                        StockAlignSku 
                    WHERE 
                        sku = ? 
                    LIMIT 1
                    ) as productname
                    ,qty
                    ,orig_qty 
                FROM
                    StockAlignSync
                WHERE
                    transactno = ?";

        $sql = $pdo->prepare($sql);
        $sql->execute([$sku, $transactNo]);
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);


        $rowCount = count($data);
        $json_data = array(
            "draw"            =>  $draw,
            "recordsTotal"    => intval($rowCount),
            "recordsFiltered" => intval($rowCount),
            "data"            => $data
        );
        echo json_encode($json_data, JSON_PRETTY_PRINT);
    }


    //  function getAccessToken()
    // {
    //     $host = "https://partner.shopeemobile.com";
    //     $path = "/api/v2/auth/token/get";

    //     $code = "4c644953694743467a56714d63625a51";
    //     $shopId = 322049526;
    //     $partnerId = 2010905;
    //     $partnerKey = "5a7255626646637a4e6751514e43685669684878736a4c465a737a624e564b58";

    //     // {
    //     //     "refresh_token": "424e446b6e6c6d725443766961697764",
    //     //     "access_token": "444d4f6b425770514a78626668477652",
    //     //     "expire_in": 14258,
    //     //     "request_id": "e3e3e7f3326413861e2351dcc9f24e00",
    //     //     "error": "",
    //     //     "message": ""
    //     //}

    //     // &shop_id=322049526

    //     $timest = time();
    //     $body = array("code" => $code,  "shop_id" => $shopId, "partner_id" => $partnerId);
    //     $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
    //     $sign = hash_hmac('sha256', $baseString, $partnerKey);
    //     $url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $host, $path, $partnerId, $timest, $sign);

    //     echo $url;

    //     echo json_encode($body);

    //     $c = curl_init($url);
    //     curl_setopt($c, CURLOPT_POST, 1);
    //     curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
    //     curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    //     curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    //     $resp = curl_exec($c);
    //     echo "raw result: $resp";

    //     $ret = json_decode($resp, true);
    //     $accessToken = $ret["access_token"];
    //     $newRefreshToken = $ret["refresh_token"];
    //     echo "\naccess_token: $accessToken, refresh_token: $newRefreshToken raw: $ret" . "\n";
    //     return $ret;
    // }

}
