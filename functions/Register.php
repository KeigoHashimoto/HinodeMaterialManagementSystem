<?php

require_once "Connect.php";

class Register
{
    use Connect;

    function register()
    {
        session_start();
        $name = $_POST['name'];
        $userId = $_POST['userId'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $pdo = $this->dbConnect();

        if ($_POST['password'] != $_POST['confirm']) {
            $_SESSION['registerErr'] = "パスワードと確認が一致しません";
            header('Location:../views/register.php');
            print_r($_SESSION['registerErr']);
        } else {
            $sql = "SELECT * FROM users WHERE userId = :userId;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
            $stmt->execute();
            $member = $stmt->fetch();

            $stmt = null;

            print_r($member);

            if (isset($member['userId'])) {
                if ($member['userId'] == $userId) {
                    $_SESSION['registerErr'] = "すでにこのIDは使われています。";
                    header('Location:../views/register.php');
                    exit;
                    print_r($_SESSION['registerErr']);
                }
            } else {
                $sql = "INSERT INTO users(name,userId,password) VALUE(:name,:userId,:password);";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':name', $name, PDO::PARAM_STR);
                $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
                $stmt->bindValue(':password', $password, PDO::PARAM_STR);
                $stmt->execute();
                $stmt = null;
                $pdo = null;

                header('Location:../views/login.php');
                exit;
            }
        }
    }
}

$register = new Register();
$register->register();
