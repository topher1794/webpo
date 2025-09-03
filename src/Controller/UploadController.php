<?php

namespace stockalignment\Controller;

use stockalignment\Core\Database;
use stockalignment\Controller;
// use stockalignment\Jwt;
use PDO;
use Firebase\JWT\Key;
use InvalidArgumentException;
use Exception;


session_start();


class UploadController extends Controller
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

}
