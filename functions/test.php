<?php
$user = "root";
$pass = "root";
$db = "mysql:dbname=MMS;host=localhost;char=utf8";


try {
    $pdo = new PDO($db, $user, $pass);
} catch (PDOException $e) {
    echo $e->getMessage();
}

session_start();
$data = array();

for ($i = 0; $i < count($_POST['use']); $i++) {

    $id = $_POST['id'][$i];
    $use = $_POST['use'][$i];

    $data[] = [$id => $use];

    foreach ($data[$i] as $key => $value) {


        $sql = "SELECT stock FROM materials WHERE id = :id;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $key, PDO::PARAM_INT);
        $stmt->execute();
        $stock = $stmt->fetch();

        echo nl2br($stock['stock'] - $value . "\n");
    }
}
