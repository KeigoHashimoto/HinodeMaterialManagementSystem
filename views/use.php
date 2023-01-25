<?php
require_once "../functions/Material.php";
session_start();

$select = new Material();
$index = $select->index();

$csrf = bin2hex(random_bytes(32));
$_SESSION['csrf'] = $csrf;

$token = uniqid("", true);
$_SESSION['token'] = $token;


?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <main>
        <div class="container use">
            <h2>物品を使う</h2>

            <!-- 取り消しボタン -->
            <div class="back-btn">
                <?php
                if (isset($_SESSION['previous_id']) && isset($_SESSION['previous_stock'])) {
                    $previousId = $_SESSION['previous_id'];
                    unset($_SESSION['previous_id']);
                    $previousStock = $_SESSION['previous_stock'];
                    unset($_SESSION['previous_stock']);
                ?>
                    <form action="../functions/Retake.php" method="POST">
                        <input type="hidden" name="previous_id" value=<?php echo $previousId; ?>>
                        <input type="hidden" name="type" value="use">
                        <input type="hidden" name="previous_stock" value=<?php echo $previousStock; ?>>
                        <input type="hidden" name="csrf" value=<?php echo $csrf; ?>>
                        <input type="hidden" name="token" value=<?php echo $token; ?>>
                        <p>操作を取り消しますか？<br><input type="submit" value="取り消す"></p>
                    </form>
                <?php } ?>
            </div>

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


            <?php foreach ($index as $record) { ?>
                <?php
                if ($record['stock'] > 0) { ?>
                    <form action="../functions/Use.php" method="post">

                        <input type="hidden" name="csrf" value=<?php echo $csrf ?>>
                        <input type="hidden" name="token" value=<?php echo $token ?>>
                        <input type="hidden" name="id" value=<?php echo $record['id'] ?>>
                        <input type="hidden" name="stock" value=<?php echo $record['stock'] ?>>
                        <div class="form-group">
                            <p><?php echo $record['material_name'] ?> |</p>
                            <p>在庫：<?php echo $record['stock'] ?></p>
                            <p>持ち出し数： <input type="number" name="use"></p>
                            <button type="submit" value="submit">USE</button>
                        </div>

                        <small>最終持ち出し日時　<br>
                            <?php echo date('Y/m/d', strtotime($record['updated_at'])) ?>
                        </small>
                    </form>

                <?php } ?>
            <?php } ?>
        </div>
    </main>
</body>

</html>