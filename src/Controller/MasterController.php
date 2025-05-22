<?php

namespace stockalignment\Controller;

use stockalignment\Controller;
use stockalignment\Core\Database;
use PDO;
use PDOException;



class MasterController extends Controller
{


    public $database = null;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function sku()
    {
        $data['logs'] = $_POST;
        $data['controller'] = "master";
        $data['action'] = "sku";

        $this->render('Template/header.php', $data);
        $this->render('Template/sidebar.php', $data);
        $this->render('Master/Sku.php', $data);
        $this->render('Template/footer.php', $data);
    }

    public function uploadmaster()
    {

        $pdo = $this->database->getPdo();
        $csv = $_FILES['file']['tmp_name'];
        $fh = fopen($csv, 'r');

        $result = "success";
        $message = "Successfully inserted";

        $pdo->beginTransaction();   // Turns off autocommit

        try {

            $rowno = 0;
            while ($line = fgets($fh)) {
                $row = explode("\t", $line);
                if ($rowno > 0) {
                    $company = $row[0];
                    $accttype = $row[1];
                    $parentsku = $row[2];
                    $sku = $row[3];
                    $productid = $row[4];
                    $productname = $row[5];
                    $skuid = $row[6];
                    $shopsku = $row[7];


                    $company = trim($company);
                    $accttype = trim($accttype);
                    $parentsku = trim($parentsku);
                    $sku = trim($sku);

                    if (empty($sku)) {
                        $sku = null;
                    }
                    if (empty($parentsku)) {
                        $parentsku = null;
                    }
                    $productid = trim($productid);
                    $productname = trim($productname);
                    $skuid = trim($skuid);
                    $shopsku = trim($shopsku);

                    if ($rowno == 1) {
                        /**
                         * truncate first
                         */
                        $sql = "DELETE FROM StockAlignSku WHERE company = ? AND accttype = ? ";
                        $statement = $pdo->prepare($sql);
                        $statement->execute([$company, $accttype]);
                    }

                    $sqlArr = [
                        $accttype,
                        $parentsku,
                        $sku,
                        $productid,
                        $productname,
                        $skuid,
                        $shopsku,
                        $company
                    ];

                    $sql = "INSERT INTO StockAlignSku (accttype, parentsku, sku, productid, productname, skuid, shopsku, company) 
                VALUES(?, ? ,? ,? ,?, ?, ?, ?);";
                    $statement = $pdo->prepare($sql);
                    $statement->execute($sqlArr);
                }
                $rowno++;
            }

            $pdo->commit();         // Commit both queries
        } catch (PDOException $e) {
            $pdo->rollBack();       // Rollback on error

            $message =  $e->getMessage();
            $result = "error";
        }
        echo json_encode(array("result" => $result, "message" => $message), JSON_PRETTY_PRINT);
    }

    public function getSkus()
    {
        $pdo = $this->database->getPdo();


        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 1;
        $length = $_POST['length'] ?? 1;

        $company = $_POST['company'];
        $source = $_POST['source'];

        if (!empty($company) || !empty($source)) {
            $concat = "accttype = '" . $source . "' AND company = '" . $company . "'";
        } else {
            $concat = "accttype is not null";
        }



        $sql = "SELECT accttype, parentsku, sku, productid, productname, skuid, shopsku, company 
                FROM StockAlignSku 
                WHERE " . $concat . "
        ";

        // echo $sql;
        // exit();
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



    public function userlists()
    {
        $data['logs'] = $_POST;
        $data['controller'] = "master";
        $data['action'] = "users";

        $this->render('Template/header.php', $data);
        $this->render('Template/sidebar.php', $data);
        $this->render('Users/Lists.php', $data);
        $this->render('Template/footer.php', $data);
    }
    public function  getUsers()
    {
        $pdo = $this->database->getPdo();


        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 1;
        $length = $_POST['length'] ?? 1;

        $sql = "select id, CONCAT(firstname, ' ',lastname) as name, username, emailadd, token, token_expiration,  status from StockAlignUsers ORDER BY firstname, lastname asc    ";
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
}
