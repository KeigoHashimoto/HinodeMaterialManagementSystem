<?php
require_once "../functions/Material.php";
require_once "../functions/Search.php";
require_once "../functions/Remember.php";
require_once "../commons/rememberAuth.php";
session_start();

/**
 * csrfトークン
 */
$csrf = bin2hex(random_bytes(32));
$_SESSION['csrf'] = $csrf;

/**
 * 二重送信禁止トークン
 */
$token = uniqid("", true);
$_SESSION['token'] = $token;

/**
 * 物品一覧の取得 検索
 * keywordがgetであればSearchへ
 */
if (array_key_exists('keyword', $_GET)) {
    $select = new Search;
    $index = $select->Search();
} elseif (array_key_exists('not', $_GET)) {
    $select = new Search;
    $index = $select->not();
} else {
    $select = new Material();
    $index = $select->index();
}

$type = "replenish";


?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <main>
        <div class="use">

            <!--やり直しボタン-->
            <?php include "../commons/retake.php" ?>

            <h2>物品を補充する</h2>

            <!--検索窓-->
            <?php include "../commons/Search.php" ?>


            <form action="" method="GET" class="not-form">
                <input type="submit" value="在庫0のみ表示" name="not" class="not">
            </form>


            <!-- error -->
            <?php
            if (!empty($_SESSION['err'])) { ?>
                <p class="err"><?php echo $_SESSION['err'] ?></p>
            <?php
                unset($_SESSION['err']);
            } ?>

            <div class="form">
                <form action="../functions/Replenish.php" method="post">
                    <input type="hidden" name="csrf" value=<?php echo $csrf ?>>
                    <input type="hidden" name="token" value=<?php echo $token ?>>

                    <?php foreach ($index as $record) {
                    ?>
                        <div class="wrap">
                            <input type="hidden" name="id[]" value=<?php echo $record['id'] ?>>
                            <input type="hidden" name="stock[]" value=<?php echo $record['stock'] ?>>
                            <div class="form-group">
                                <label for="replenish"><?php echo $record['material_name'] ?></label>
                                <p>在庫数：<?php echo $record['stock']; ?></p>
                                <p>必要数：<?php echo $record['need'] ?></p>
                                <p>補充数：<input type="number" name="replenish[]" value="0"></p>
                            </div>

                            <small>最終補充日時 :
                                <?php echo date('Y/m/d', strtotime($record['updated_at'])) ?>
                            </small>
                            <small>保管場所 :
                                <?php echo $record['place'] ?>
                            </small>
                        </div>
                    <?php } ?>

                    <input type="submit" value="submit" class="submit-btn">
                </form>
            </div>
        </div>
    </main>

    <?php include "../commons/js.php" ?>

</body>

</html>