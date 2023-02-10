<?php
?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <main>
        <?php
        if (isset($_SESSION['registerErr'])) { ?>
            <p class="err"><?php echo $_SESSION['registerErr'] ?></p>
        <?php } ?>


        <div class="input-form">
            <form action="../functions/Register.php" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name">
                </div>
                <div class="form-group">
                    <label for="userId">Id</label>
                    <input type="text" name="userId">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password">
                </div>
                <div class="form-group">
                    <label for="confirm">confirm</label>
                    <input type="password" name="confirm">
                </div>
                <input type="submit" value="submit" class="submit-btn">
                <a href="./login.php">ログインs</a>
            </form>
        </div>
    </main>
</body>