<?php
namespace stockalignment\Controller;

use stockalignment\Core\Database;
use PhpParser\PrettyPrinter;
use stockalignment\Model\UserModel;
use stockalignment\Controller;

class SapController  extends Controller
{

    public $database = null;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function getSap() {
        
        $username = "";
        $password = "";
        $host = "";
        $port = "";
        

    }



}