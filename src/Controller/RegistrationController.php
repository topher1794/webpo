<?php

namespace stockalignment\Controller;

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


    public function register(string $email, string $password): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 400, 'message' => 'Invalid email format'];
        }

        if (strlen($password) < 6) {
            return ['status' => 400, 'message' => 'Password too short'];
        }

        if ($this->userModel->emailExists($email)) {
            return ['status' => 409, 'message' => 'Email already registered'];
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->userModel->createUser($email, $hash);

        return ['status' => 201, 'message' => 'User registered'];
    }
}