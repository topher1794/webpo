<?php 

use PHPUnit\Framework\TestCase;
use stockalignment\Core\Database;
use stockalignment\Model\UserModel;
use stockalignment\Controller\RegistrationController;

require_once __DIR__ . '/../vendor/autoload.php';

class RegistrationIntegrationTest extends TestCase
{
    private $pdo;
    private $controller;

    private $database;
    protected function setUp(): void
    {

        $this->database = Database::getInstance();

        $this->pdo =  $this->database->getPdo();
        $this->pdo->exec("DELETE FROM StockAlignUsers");

        $model = new UserModel($this->pdo);
        $this->controller = new RegistrationController($model);
    }

    public function testInvalidEmail()
    {
        $res = $this->controller->register('bademail', 'secret');
        $this->assertEquals(400, $res['status']);
    }

    public function testShortPassword()
    {
        $res = $this->controller->register('user@example.com', '123');
        $this->assertEquals(400, $res['status']);
    }

    public function testSuccessRegistration()
    {
        $res = $this->controller->register('user@example.com', 'secret123');
        $this->assertEquals(201, $res['status']);
    }
    public function testDuplicateEmail()
    {
        $this->controller->register('user@example.com', 'secret123');
        $res = $this->controller->register('user@example.com', 'secret456');
        $this->assertEquals(409, $res['status']);
    }
}