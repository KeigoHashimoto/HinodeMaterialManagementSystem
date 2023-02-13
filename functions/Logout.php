<?php
require_once "Connect.php";

/**
 * ログアウト
 */

class Logout
{
    use Connect;

    function logout()
    {
        session_start();
        if (array_key_exists('logout', $_POST)) {
            //userのremember_tokenをNULLにする
            $pdo = $this->dbConnect();
            $sql = "UPDATE users SET remember_token = NULL WHERE userId = :userId;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_STR);
            $stmt->execute();


            //セッションを空にする
            $_SESSION  = [];

            setcookie('PHPSESSID', '', time() - 6000, '/');

            //cookie削除
            setcookie('remember_token', '', time() - 6000, '/');

            header('Location:../views/login.php');
            exit;
        } else {
            $_SESSION['err'] = "Logout ERR!";
        }
    }
}

$logout = new Logout();
$logout->logout();
