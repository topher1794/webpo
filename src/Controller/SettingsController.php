<?php

namespace stockalignment\Controller;

use stockalignment\Core\Database;
use PhpParser\PrettyPrinter;
use stockalignment\Model\UserModel;
use stockalignment\Controller;
use PDO;

class SettingsController  extends Controller
{

    public $database = null;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }


    
    public function settings()
    {
        $data['logs'] = $_POST;
        $data['controller'] = "settings";
        $data['action'] = "settings";

        $this->render('Template/header.php', $data);
        $this->render('Template/sidebar.php', $data);
        $this->render('Master/Sku.php', $data);
        $this->render('Template/footer.php', $data);
    }


    public function getSettings()
    {

        $draw = "";
        $concat = "";
        $pdo = $this->database->getPdo();

        $sql = "SELECT settingstype, attributes FROM StockAlignSettings ORDER BY 1 ASC";

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
