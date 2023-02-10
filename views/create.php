<?php
session_start();

$csrf = bin2hex(random_bytes(16));
$_SESSION['csrf'] = $csrf;

$token = uniqid("", true);
$_SESSION['token'] = $token;

?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <h2>物品の追加</h2>

    <?php if (isset($_SESSION['err'])) { ?>
        <p class="err"><?php echo $_SESSION['err'] ?></p>
    <?php
        unset($_SESSION['err']);
    } ?>

    <div class="create">
        <form action="../functions/Post.php" method="POST">
            <input type="hidden" name="token" value=<?php echo $token ?>>
            <input type="hidden" name="csrf" value=<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>>
            <input type="hidden" name="userId" value=<?php echo $_SESSION['userId'] ?>>
            <div class="form-group">
                <label for="material_name">物品名</label>
                <input type="text" name="material_name">
            </div>
            <div class="form-group">
                <label for="place">保管場所</label>
                <input type="text" name="place">
            </div>
            <div class="form-group">
                <label for="stock">初期在庫数</label>
                <input type="number" name="stock">
            </div>

            <input type="submit" value="登録" class="submit-btn">
        </form>
    </div>
</body>

</html>