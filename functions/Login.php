<?php

require_once "Connect.php";

/**
 * ログイン
 */

class Login
{
    use Connect;

    function login()
    {
        session_start();

        if (array_key_exists('userId', $_POST)) {
            $userId = $_POST['userId'];
            $pass = $_POST['password'];


            $pdo = $this->dbConnect();
            $sql = "SELECT * FROM users WHERE userId = :userId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();
            $stmt = null;

            if (isset($_POST['remember_token'])) {
                $remember = bin2hex(random_bytes(32));

                $options = [
                    'expires' => time() + 60 * 60 * 24 * 365 * 3,
                    'path' => '/',
                    'httponly' => true,
                ];

                setcookie('remember_token', $remember, $options);

                $sql = "UPDATE users SET remember_token = :remember_token WHERE userId = :userId;";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':remember_token', $remember, PDO::PARAM_STR);
                $stmt->bindValue(':userId', $user['userId'], PDO::PARAM_STR);
                $stmt->execute();
                $stmt = null;
                $pdo = null;
            }


            if (password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['name'] = $user['name'];

                header('Location:../views/index.php');
                exit;
            } else {
                $_SESSION['loginErr'] = "登録情報が間違えています";
                header('Location:../views/login.php');
                exit;
            }
        }
    }
}

$login = new Login();
$login->login();
