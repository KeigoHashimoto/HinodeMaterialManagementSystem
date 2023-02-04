<?php
require_once "Connect.php";

class Retake
{
    use Connect;

    function retake()
    {
        session_start();

        /**
         * csrf対策
         */
        $csrf = isset($_POST['csrf']) ? $_POST['csrf'] : "";
        $session_csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : "";

        if ($csrf != "" && $csrf == $session_csrf) {
            /**
             * postの確認
             */
            print_r($_POST);
            if (array_key_exists('previous_id', $_POST)) {
                /**
                 * 二重送信禁止
                 */
                $token = isset($_POST['token']) ? $_POST['token'] : "";
                $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : "";

                if ($token != "" && $token == $session_token) {

                    for ($i = 0; $i < count($_POST['previous_id']); $i++) {

                        $id = $_POST['previous_id'][$i];
                        $stock = $_POST['previous_stock'][$i];

                        $data = [$id => $stock];

                        foreach ($data as $id => $stock) {
                            $pdo = $this->dbConnect();
                            $sql = "UPDATE materials SET stock = :stock WHERE id = :id;";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();

                            $pdo = null;
                            $stmt = null;
                        }
                    }
                }
            } else {
                $_SESSION['err'] = "履歴がありません";
            }
        }

        // if (array_key_exists('type', $_POST)) {
        //     if ($_POST['type'][0] == "use") {
        //         header('Location:../views/use.php');
        //     } elseif ($_POST['type'][0] == "replenish") {
        //         header('Location:../views/replenish.php');
        //     }
        // }
        exit;
    }
}

$retake = new Retake();
$retake->retake();
