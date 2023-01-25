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
            if (array_key_exists('previous_id', $_POST)) {
                $id = $_POST['previous_id'];
                $stock = $_POST['previous_stock'];

                /**
                 * 二重送信禁止
                 */
                $token = isset($_POST['token']) ? $_POST['token'] : "";
                $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : "";

                if ($token != "" && $token == $session_token) {
                    $pdo = $this->dbConnect();
                    $sql = "UPDATE materials SET stock = :stock WHERE id = :id;";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    $pdo = null;
                    $stmt = null;
                }
            } else {
                $_SESSION['err'] = "履歴がありません";
            }
        }

        if (array_key_exists('type', $_POST)) {
            if ($_POST['type'] == "use") {
                header('Location:../views/use.php');
            } elseif ($_POST['type'] == "replenish") {
                header('Location:../views/replenish.php');
            }
        }
        exit;
    }
}

$retake = new Retake();
$retake->retake();
