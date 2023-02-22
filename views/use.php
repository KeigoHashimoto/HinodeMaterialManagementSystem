<?php
require_once "../functions/Material.php";
require_once "../functions/Search.php";
require_once "../functions/Remember.php";
require_once "../commons/rememberAuth.php";
session_start();

if (array_key_exists('keyword', $_GET)) {
    $select = new Search;
    $index = $select->Search();
} else {
    $select = new Material();
    $index = $select->index();
}

$csrf = bin2hex(random_bytes(32));
$_SESSION['csrf'] = $csrf;

$token = uniqid("", true);
$_SESSION['token'] = $token;

$type = "use";


?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <main>
        <div class="use">
            <h2>物品を使う</h2>

            <?php include "../commons/Search.php" ?>

            <!-- 取り消しボタン -->
            <?php include "../commons/retake.php" ?>

            <!-- error -->
            <?php
            if (!empty($_SESSION['err'])) { ?>
                <p class="err">
                    <?php echo $_SESSION['err'];
                    unset($_SESSION['err']); ?>
                </p>
            <?php
                unset($_SESSION['err']);
            } ?>

            <div class="form">
                <form action=" ../functions/Use.php" method="post">
                    <input type="hidden" name="csrf" value=<?php echo $csrf ?>>
                    <input type="hidden" name="token" value=<?php echo $token ?>>

                    <?php foreach ($index as $record) { ?>

                        <?php
                        if ($record['stock'] > 0) { ?>
                            <div class="wrap">
                                <input type="hidden" name="id[]" value=<?php echo $record['id'] ?>>
                                <input type="hidden" name="stock[]" value=<?php echo $record['stock'] ?>>
                                <input type="hidden" name="material_name[]" value=<?php echo $record['material_name'] ?>>
                                <div class="form-group">
                                    <p><?php echo $record['material_name'] ?> |</p>
                                    <p>在庫：<?php echo $record['stock'] ?></p>
                                    <p>持ち出し数： <input type="number" name="use[]" value="0"></p>
                                </div>

                                <small>最終持ち出し日時 :
                                    <?php echo date('Y/m/d', strtotime($record['updated_at'])) ?>
                                </small>
                                <small>保管場所 :
                                    <?php echo $record['place'] ?>
                                </small>
                            </div>
                        <?php } ?>

                    <?php } ?>
                    <input type="submit" value="submit" class="submit-btn">
                </form>
            </div>


        </div>
    </main>

    <?php include "../commons/js.php" ?>

</body>

</html>