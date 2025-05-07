<?php 

namespace stockalignment\Classes;

use stockalignment\Database;
use stockalignment\Model\DatabaseModel;
use stockalignment\Controller;
// use stockalignment\Jwt;
use PDO;
// use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;
use Exception;

class SAP {

    public function executeRFCToSAP(String $sap_user = "", String $sap_password = "", String $url = "", ?array $params = null): array
    {

        $url = $url . '?' . http_build_query($params);
        //Initiate cURL.
        $ch = curl_init($url);
        // pass encoded JSON string to the POST fields 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        //Specify the username and password using the CURLOPT_USERPWD option.
        curl_setopt($ch,  CURLOPT_USERPWD, ($sap_user . ":". $sap_password) );

        //Tell cURL to return the output as a string instead
        //of dumping it to the browser.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Execute the cURL request.
        $response = curl_exec($ch);
        curl_close($ch);


        return array("response" => $response, "url" => $url);
    }

    /**
     * 
     * GET SAP TOKEN
     * Function to use for getting SAP Token as parameters required.
     * 
     */

    public function getSAPToken(String $http, String $username, String $password, String $SAPIP, String $SAPPort, String $sapodsrv, String $sapeset): array
    {

        $serverloc = explode("/", ($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']));

        $SAPPort = "";
        $SAPPtl = $http;
        $SAPUser = $username;
        $SAPPass = $password;
        $SAPIP = "";
       
        $SAPClient = "888";
        $SAPOdataSvc = '/sap/opu/odata/sap/';

        $_CONFIG['rgc_ptl'] = $SAPPtl;
        $_CONFIG['rgc_host'] = $SAPIP;
        $_CONFIG['rgc_port'] = $SAPPort;
        $_CONFIG['rgc_client'] = $SAPClient;
        $_CONFIG['rgc_user'] = $SAPUser;
        $_CONFIG['rgc_pass'] = $SAPPass;
        $_CONFIG['rgc_odloc'] = $SAPOdataSvc;

        $postparam = $_CONFIG['rgc_ptl'] . "://" . $_CONFIG['rgc_host'] . ":" . $_CONFIG['rgc_port'] . $_CONFIG['rgc_odloc'] . $sapodsrv . "/" . $sapeset . "?sap-user=" . $_CONFIG['rgc_user'] . "&sap-password=" . $_CONFIG['rgc_pass'] . "&SAP-CLIENT=" . $_CONFIG['rgc_client'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $postparam,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HEADER => 1,
            CURLOPT_NOBODY => 1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-csrf-token: Fetch"
            ),
        ));
        $response = curl_exec($curl);


        $headers = array();
        $output = rtrim($response);
        $data = explode("\n", $output);
        $headers['status'] = $data[0];
        $arrind = 0;
        foreach ($data as $part) {
            $arrind++;
            //some headers will contain ":" character (Location for example), and the part after ":" will be lost
            $middle = explode(":", $part);

            //Supress warning message if $middle[1] does not exist
            if (!isset($middle[1])) {
                $middle[1] = null;
            }

            if (trim($middle[0]) == "set-cookie") {
                // $headers['set-cookie'] = $headers['set-cookie'] . ";" . trim($middle[1]);
                $headers['set-cookie'] =  trim($middle[1]);
            } else {
                $headers[trim($middle[0])] = trim((string)$middle[1]);
            }
        }

        $response = json_encode($headers);
        $response = json_decode($response, true);

        try {
            $mytoken = "x-csrf-token: " . $response['x-csrf-token'];
            $mycookie = "Cookie: " . $response['set-cookie'];
            $response = "success";
        } catch (Exception $exception) {
            $response = "error";
        }

        curl_close($curl);
        return array("response" => $response,  "postparam" => $postparam,  "mytoken" => $mytoken, "mycookie" => $mycookie);
    }

    /**
     * Executing command to SAP using curl
     * return String type
     */




    public function executeToSAP(String $postparam, String $json, String $myToken, String $myCookie): array
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $postparam,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                $myToken,
                // "Content-Type: application/xml",
                "Content-Type: application/json",
                $myCookie,
                "Accept: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return array("response" => $response);
    }

    public function executeToSAPXml(String $postparam, String $json, String $myToken, String $myCookie): array
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $postparam,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                $myToken,
                "Content-Type: application/xml",
                // "Content-Type: application/json",
                $myCookie,
                // "Accept: application/json"
                "Accept: application/xml"

            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return array("response" => $response);
    }


}