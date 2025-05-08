<?php

namespace stockalignment\Controller;

use PhpParser\PrettyPrinter;
use stockalignment\Model\UserModel;
use stockalignment\Controller;

class RegistrationController extends Controller
{
    private UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }


    public function registration()
    {
        $data['logs'] = $_POST;
        $this->render('Login/register.php', $data);
    }

    public function newRegistration()
    {

        $jsonData = file_get_contents('php://input');
        $dataDecoded = json_decode($jsonData, true);

        $username = $dataDecoded["username"] ?? "";
        $email = $dataDecoded["email"] ?? "";
        $password = $dataDecoded["password"] ?? "";
        $confirmpassword = $dataDecoded["confirm-password"] ?? "";

        echo json_encode($this->register($username, $email, $password, $confirmpassword), JSON_PRETTY_PRINT);
    }

    public function register(string $username, string $email, string $password, string $newPassword): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 400, 'message' => 'Invalid email format!'];
        }

        if (strlen($password) < 6) {
            return ['status' => 400, 'message' => 'Password too shor!t'];
        }

        if ($this->userModel->emailExists($email)) {
            return ['status' => 409, 'message' => 'Email already registered!'];
        }

        if ($password != $newPassword) {
            return ['status' => 400, 'message' => 'Password is not the same!'];
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->userModel->createUser($username, $email, $hash);

        return ['status' => 201, 'message' => 'User registered'];
    }
}
