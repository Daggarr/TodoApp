<?php
namespace App\Controllers;

use App\Models\User;
use App\Repositories\MysqlUsersRepository;

class UsersController
{
    private MysqlUsersRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new MysqlUsersRepository();
    }

    public function register()
    {
        require_once 'app/Views/users/register.template.php';
    }

    public function verify()
    {
        $user = new User($_POST['username'],$_POST['password']);
        $verification = $this->usersRepository->verifyPassword($user);

        if ($verification === false || $verification === null)
        {
            header('Location: /');
        }
        else
        {
            $_SESSION['username'] = $_POST['username'];
            header('Location: /tasks');
        }
    }

    public function login()
    {
        require_once 'app/Views/users/login.template.php';
    }

    public function store()
    {
        $user = new User($_POST['username'], $_POST['password']);

        $this->usersRepository->save($user);

        header('Location: /');
    }
}