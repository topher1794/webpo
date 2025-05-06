<?php

namespace stockalignment\Controller;

use stockalignment\Core\Database;
use stockalignment\Controller;
use stockalignment\Jwt;
use PDO;
// use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;
use Exception;

class AuthenticationController extends Controller
{

    public $database = null;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function dashboard()
    {
        $data['logs'] = $_POST;

        $this->render('templates/header.php', $data);
        $this->render('dashboard/dashboard.php', $data);
        $this->render('templates/footer.php', $data);
    }


    public function index()
    {
        $data['title'] = "LOGIN";
        $haveSession = $_SESSION["userno"] ?? "";
        if (!empty($haveSession)) {
            // $controller = new AuthenticationController();
            $this->dashboard();
            exit();
        }
        $this->render('Login/login.php', $data);
    }

    private function base64URLEncode(string $text): string
    {

        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
    }

    private function base64URLDecode(string $text): string
    {
        return base64_decode(
            str_replace(
                ["-", "_"],
                ["+", "/"],
                $text
            )
        );
    }

    public function decode(string $token): array
    {
        if (preg_match(
            "/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/",
            $token,
            $matches
        ) !== 1) {

            throw new InvalidArgumentException("invalid token format");
        }

        $signature = hash_hmac(
            "sha256",
            $matches["header"] . "." . $matches["payload"],
            $this->key,
            true
        );

        $signature_from_token = $this->base64urlDecode($matches["signature"]);

        if (! hash_equals($signature, $signature_from_token)) {

            // throw new InvalidSignatureException;
        }

        $payload = json_decode($this->base64urlDecode($matches["payload"]), true);

        if ($payload["exp"] < time()) {

            // throw new TokenExpiredException;
        }

        return $payload;
    }

    function generateJWT($payload, $secret, $alg = 'HS256')
    {
        // $header = ['typ' => 'JWT', 'alg' => $alg];

        // Set expiration time 3 seconds from now
        $payload['exp'] = time() + 3;  // Current time + 3 seconds

        $header = json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]);

        $header = $this->base64URLEncode($header);
        $payload = json_encode($payload);
        $payload = $this->base64URLEncode($payload);

        $signature = hash_hmac("sha256", $header . "." . $payload, $secret, true);
        $signature = $this->base64URLEncode($signature);
        return $header . "." . $payload . "." . $signature;
    }


    public function listsq()
    {
        $pdo = $this->database->getPdo();


        // po_apirefresh_tokens
        // bearer

        if (array_key_exists("Authorization", getallheaders())) {

            // print_r(getallheaders());

            // echo getallheaders()["Authorization"];

            // ECHO strpos( getallheaders()["Authorization"], "Bearer" ) ;

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

        $sqlToken = "SELECT base64text  FROM po_apiusers where base64text =:base64text";
        $statement = $pdo->prepare($sqlToken);
        $statement->bindParam(":base64text", $bearerToken, PDO::PARAM_STR);
        $statement->execute();
        $tokenInfo = $statement->fetch(PDO::FETCH_ASSOC);

        if (!is_array($tokenInfo)) {
            http_response_code(400);
            echo json_encode(["message" => "Token is not valid."]);
            exit();
        }

        if ($tokenInfo["base64text"] == $bearerToken) {

            $sha1Token = sha1(rand());

            $payload = [
                "token" => $bearerToken,
                "sha1Token" => $sha1Token,
                "exp" => time() + (15 * 60) //FOR 15 minutes
            ];

            $SECRET_KEY = "API_UX1968";

            /**
             * CHECK IF access_token is exists
             */

            if (strpos($_SERVER["REQUEST_URI"], "getToken") !== false) {

                $JwtController = new Jwt($SECRET_KEY);

                $access_token = $JwtController->encode($payload);

                // $refresh_token_expiry = time() + 432000;

                // $refresh_token = $JwtController->encode([
                //     "sub" => $user["id"],
                //     "exp" => $refresh_token_expiry
                // ]);


                $encoded  = $JwtController->decode($access_token); //token 

                $newToken =  json_encode([
                    "access_token" => $access_token,
                    "expires_at" => $encoded["exp"],
                ]);



                $sqlApi = "INSERT INTO po_apitokens(apikey, token_hash, expires_at) VALUES(:apikey, :token_hash, :expires_at)";
                $statement = $pdo->prepare($sqlApi);
                $statement->bindParam(":apikey", $bearerToken, PDO::PARAM_STR);
                $statement->bindParam(":token_hash", $encoded["sha1Token"], PDO::PARAM_STR);
                $statement->bindParam(":expires_at", $encoded["exp"], PDO::PARAM_INT);
                $statement->execute();
                $rowCount = $statement->rowCount();
                if ($rowCount == 0) {
                    http_response_code(400);
                    echo json_encode(["message" => "invalid token"]);
                    exit;
                }
                /**
                 * RESPONSE TOKEN
                 */
                echo $newToken;
                exit();

                exit();
            }

            if (isset($_POST["access_token"])) {


                $access_token = $_POST["access_token"];

                $JwtController = new Jwt($SECRET_KEY);
                $payload = null;
                try {
                    $payload = $JwtController->decode($access_token); //token 
                } catch (Exception $e) {
                    http_response_code(400);
                    echo json_encode(["message" => "invalid token"]);
                    exit;
                }


                $sql = "SELECT token_hash, apikey, expires_at FROM po_apitokens WHERE apikey = :apikey AND token_hash = :token_hash ";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(":apikey", $bearerToken, PDO::PARAM_STR);
                $statement->bindParam(":token_hash", $payload["sha1Token"], PDO::PARAM_STR);
                $statement->execute();
                $rowCount = $statement->rowCount();
                $apiInfo  = $statement->fetch(PDO::FETCH_ASSOC);


                if ($rowCount == 0) {
                    http_response_code(400);
                    echo json_encode(["message" => "invalid token"]);
                }
                if ($apiInfo["expires_at"] < time()) {
                    http_response_code(400);
                    echo json_encode(["message" => "Token expired."]);
                    exit;
                }
            }





            /*
            $JwtController = new Jwt($_ENV["SECRET_KEY"]);

            try {
                $payload = $JwtController->decode($data["token"]);
                
            } catch (Exception) {
                
                http_response_code(400);
                echo json_encode(["message" => "invalid token"]);
                exit;
            }
            
            $user_id = $payload["sub"];
            
            
            $refresh_token_gateway = new RefreshTokenGateway($database, $_ENV["SECRET_KEY"]);
            
            $refresh_token = $refresh_token_gateway->getByToken($data["token"]);
            */
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Authorization required. "]);
            exit;
        }


        http_response_code(200);
        $acctNbr = htmlspecialchars($_POST['acctnbr'], ENT_QUOTES);


        if (empty($acctNbr)) {
            http_response_code(400);
            echo json_encode(["message" => "Account number is required. "]);
            exit;
        }

        $sql = "SELECT * FROM po_stpconfig WHERE stp = :acctnbr AND acctname NOT like '%RTV%' AND accttype NOT like '%LIQUIDATION%'  ";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":acctnbr", $acctNbr, PDO::PARAM_STR);
        $statement->execute();
        $rowCount = $statement->rowCount();
        $acctInfo = $statement->fetch(PDO::FETCH_ASSOC);

        if ($rowCount == 0) {
            http_response_code(400);
            echo json_encode(["message" => "No data found. "]);
            exit;
        }


        $acctType = $acctInfo["accttype"];
        $acctName = $acctInfo["acctname"];
        $pref_acct = $acctInfo["pref_acct"];
        $sapplantno = $acctInfo["sapplantno"];

        $sqlSku = "SELECT DISTINCT matlcode AS material_code , utxdesc as material_description
        , companyname, accttype, acctname, companyshrt, plantno 
        FROM po_sapmatcode
        WHERE 
        coalesce(matlcode , '') <> ''
        AND coalesce(matlcode , '') <> ''
        AND accttype = :accttype AND acctname = :acctname AND companyshrt = :pref_acct AND plantno = :plantno
        ORDER BY matlcode ASC
         ";

        $statement = $pdo->prepare($sqlSku);
        $statement->bindParam(":accttype", $acctType, PDO::PARAM_STR);
        $statement->bindParam(":acctname", $acctName, PDO::PARAM_STR);
        $statement->bindParam(":pref_acct", $pref_acct, PDO::PARAM_STR);
        $statement->bindParam(":plantno", $sapplantno, PDO::PARAM_STR);
        $statement->execute();
        $skuArr = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (count($skuArr) > 0) {
            echo json_encode(array("skus" => $skuArr));
        } else {
        }


        // $data['sku'] = $skuArr ;
        // $this->render('testpage/index.php', $data);
        // Perform database operations (if needed)
        // $users = []; // Example: Fetch users from database

        // Pass data to the view (if using a template engine)
        // return $this->render('user/index', ['users' => $users]);

        // Or directly include the view (if not using a template engine)
        // require_once 'app/views/user/index.php';
    }

    public function userAuthenticate()
    {

        $dbPDO = new Database();
        $email = $_POST['email'] ?? "";
        $password = $_POST['password'] ?? "";


        $Sql = "SELECT api_role FROM stocksapi_creds WHERE api_emailadd = '" . $email . "'";
        $this->database->getPdo();
    }
}
