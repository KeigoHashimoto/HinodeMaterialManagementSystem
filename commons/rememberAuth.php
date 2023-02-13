<?php
//セッションにuserIdがなくて、クッキーにremember_tokenもないとき
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['remember_token'])) {
    header('Location:login.php');
    exit;
    //セッションにuserIdがないが、remember_tokenがクッキーに保存されている時
} elseif (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $remember = new Remember();
    $user = $remember->remember_user($_COOKIE['remember_token']);
    $_SESSION['user_id'] = $user['userId'];
}
