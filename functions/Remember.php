<?php

require_once "Connect.php";

class Remember
{
    use Connect;

    function remember_user($cookieToken)
    {
        $pdo = $this->dbConnect();
        $sql = "SELECT * FROM users WHERE remember_token = :remember_token;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':remember_token', $cookieToken, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();
        $stmt = null;

        //remember_tokenが一致しているかどうか
        if ($user['remember_token'] == $cookieToken) {
            return $user;
        } else {
            header('Location:../views/login.php');
        }
    }
}
