<?php

namespace stockalignment\Controller;

use stockalignment\Core\Database;
use stockalignment\Controller;
// use stockalignment\Jwt;
use PDO;
// use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;
use Exception;
use stockalignment\Controller\StocksController;
use stockalignment\Classes\Jwt;

session_start();


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
        // echo $haveSession;
        // exit();
        if (!empty($haveSession)) {
            $stkController = new StocksController();
            $stkController->dashboard();
            exit();
        }
        $this->render('Login/login.php', $data);
    }
   
    public function userAuthenticate()
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        $email = $data['InputEmail'] ?? "";
        $password = $data['InputPassword'] ?? "";

        $message = "";

        $pdo = $this->database->getPdo();


        $stmt = $pdo->prepare("SELECT id, bcrypt_pass as password, firstname, lastname, company, access_role FROM StockAlignUsers WHERE username = :username");
        $stmt->bindParam(':username', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stored_hash = $user['password'];

            if (password_verify($password, $stored_hash)) {
                // Process $results
                $_SESSION["userno"] = $user['id'];
                $_SESSION["company"] = $user['company'];
                $_SESSION["firstname"] = $user['firstname'];
                $_SESSION["lastname"] = $user['lastname'];
                $_SESSION["role"] = $user['access_role'];
                $response = ['status' => 'success', 'message' => 'Login successful'];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } else {
                // Password does not match.
                $message = "Invalid password.";
                $status = "error";
            }
        } else {
            $message = "Email Address not found";
            $status = "error";
        }

        echo json_encode(["message" => $message, "status" => $status]);
    }

    public function logOut()
    {
        session_destroy();
        $this->render('Login/login.php');
    }


    public function generateToken()
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

        $stmt = $pdo->prepare("SELECT id, bcrypt_pass as password FROM StockAlignUsers WHERE username = :username");
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
        if (empty($userid)) {
            http_response_code(400);
            echo json_encode(["message" => "User not found."], JSON_PRETTY_PRINT);
            exit();
        }
        $sha1Token = sha1(rand());
        $expToken = sha1(rand());


        $payload = [
            "token" => $bearerToken,
            "sha1Token" => $sha1Token,
            "expToken" => $expToken,
            "exp" => time() + (720 * 60) //FOR 30 dayss
        ];

        $SECRET_KEY = "STOCKSAPI_UX1968";
        $JwtController = new Jwt($SECRET_KEY);

        $access_token = $JwtController->encode($payload);



        $encoded  = $JwtController->decode($access_token); //token 

        $newToken =  json_encode([
            "access_token" => $access_token,
            "refresh_token" => $expToken,
            "expires_at" => $encoded["exp"],
        ]);

        /**
         * CHECK IF access_token is exists
         */

        $sqlApi = "INSERT INTO StockAlignTokens(userid, createddate, expireddate, access_token, refresh_token, token_expiration, status)
                 VALUES(?, CURRENT_TIMESTAMP(), DATE_ADD(CURRENT_DATE() , INTERVAL 30 DAY) , ?, ?, ?, ?)";
        $statement = $pdo->prepare($sqlApi);
        $statement->execute([
            $userid,
            $encoded["sha1Token"],
            $encoded["expToken"],
            $encoded["exp"],
            true
        ]);

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
    }


    public function refreshToken()
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

        $stmt = $pdo->prepare("SELECT id, bcrypt_pass as password FROM StockAlignUsers WHERE username = :username");
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
        if (empty($userid)) {
            http_response_code(400);
            echo json_encode(["message" => "User not found."], JSON_PRETTY_PRINT);
            exit();
        }

        $token = $_POST["refresh_token"];
        if (empty($token)) {
            http_response_code(400);
            echo json_encode(["message" => "Access Token not found."], JSON_PRETTY_PRINT);
            exit();
        }


        $sqlApi = "UPDATE StockAlignTokens  set status = false WHERE userid = ? and access_token = ? and true";
        $statement = $pdo->prepare($sqlApi);
        $statement->execute([
            $userid,
            $token
        ]);

        $rowCount = $statement->rowCount();

        if ($rowCount == 0) {
            http_response_code(400);
            echo json_encode(["message" => "Access Token not found."], JSON_PRETTY_PRINT);
            exit();
        }



        $sha1Token = sha1(rand());
        $expToken = sha1(rand());


        $payload = [
            "token" => $bearerToken,
            "sha1Token" => $sha1Token,
            "expToken" => $expToken,
            "exp" => time() + (720 * 60) //FOR 30 dayss
        ];

        $SECRET_KEY = "STOCKSAPI_UX1968";
        $JwtController = new Jwt($SECRET_KEY);

        $access_token = $JwtController->encode($payload);



        $encoded  = $JwtController->decode($access_token); //token 

        $newToken =  json_encode([
            "access_token" => $access_token,
            "refresh_token" => $expToken,
            "expires_at" => $encoded["exp"],
        ]);

        /**
         * CHECK IF access_token is exists
         */

        $sqlApi = "INSERT INTO StockAlignTokens(userid, createddate, expireddate, access_token, refresh_token, token_expiration, status)
                 VALUES(?, CURRENT_TIMESTAMP(), DATE_ADD(CURRENT_DATE() , INTERVAL 30 DAY) , ?, ?, ?, ?)";
        $statement = $pdo->prepare($sqlApi);
        $statement->execute([
            $userid,
            $encoded["sha1Token"],
            $encoded["expToken"],
            $encoded["exp"],
            true
        ]);

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
    }
}
