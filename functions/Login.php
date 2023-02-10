<?php

require_once "Connect.php";

class Login
{
    use Connect;

    function login()
    {
        session_start();
        $userId = $_POST['userId'];
        $password = $_POST['password'];

        $pdo = $this->dbConnect();


        $sql = "SELECT * FROM users WHERE userId = :userId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmt->execute();
        $member = $stmt->fetch();

        $stmt = null;

        if (!isset($member['userId'])) {
            $_SESSION['loginErr'] = "IDが違います";
            header('Location:../views/login.php');
        }
        if (password_verify($password, $member['password'])) {
            $_SESSION['userId'] = $userId;
            $_SESSION['name'] = $member['name'];

            header('Location:../views/index.php');
            print_r($_POST);
        } else {
            $_SESSION['loginErr'] = "パスワードが違います";
            header('Location:../views/login.php');
        }
    }
}

$login = new Login();
$login->login();
