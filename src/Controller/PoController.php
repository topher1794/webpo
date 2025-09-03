<?php 

namespace webpo\Controller;

use webpo\Core\Database;
use webpo\Controller;
use PDO;
use Firebase\JWT\Key;
use InvalidArgumentException;
use Exception;

class PoController extends Controller {


   
    public $database = null;


    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function dashboard(){
         $data['logs'] = $_POST;

        $this->render('Template/header.php', $data);
        $this->render('Template/sidebar.php', $data);
        $this->render('dashboard/dashboard.php', $data);
        $this->render('Template/footer.php', $data);
    }


    public function newPo(){
         $data['logs'] = $_POST;
         $data['controller'] = "po";
         $data['action'] = "newpo";



        $pdo = $this->database->getPdo();
         
        $concat = "";
        if(substr($_SESSION["plantcode"], 0, 1) == "6") {
            $concat = " AND accttype = 'ECOMMERCE-ROBERTS'";
        }

        $deptName = $_SESSION["mysesDept"];

        if( $deptName == "ACCOUNTING" ) {
            $concat .= " AND accttype IN('CONSIGNMENT LIQUIDATION','CONSIGNMENT ADJUSTMENT')";
        }
        $sql = "SELECT * FROM po_accttype WHERE COALESCE(accttype, '') <> '' ".$concat."  ORDER BY 1 ASC";
        $data["acct"] = $this->database->getSQLAllRows($sql, array());

        $this->render('Template/header.php', $data);
        $this->render('Template/sidebar.php', $data);
        $this->render('NewPO/New.php', $data);
        $this->render('Template/footer.php', $data);

    }

    public function getAcctName(){
        $accttype = $_POST["accttype"];
        $result = $this->database->getSQLAllRows("SELECT acctname, string_agg(files, ',') as files FROM po_acctname WHERE accttype = ? GROUP BY 1 ORDER BY 1 ASC"
        , array($accttype)
        );
        echo json_encode($result);
    }

    public function uploadNew() {
        

    }



}