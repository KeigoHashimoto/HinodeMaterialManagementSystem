<?php
require_once "Connect.php";

class Logout
{
    use Connect;

    function logout()
    {
        session_start();
        if (array_key_exists('logout', $_POST)) {
            unset($_SESSION);
            header('Location:../views/login.php');
        } else {
            $_SESSION['err'] = "Logout ERR!";
        }
    }
}

$logout = new Logout();
$logout->logout();
