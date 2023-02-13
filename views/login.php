<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <main>
        <?php
        session_start();
        if (isset($_SESSION['loginErr'])) { ?>
            <p class="err"><?php echo $_SESSION['loginErr'] ?></p>
        <?php
            unset($_SESSION['loginErr']);
        } ?>

        <div class="input-form">
            <form action="../functions/Login.php" method="POST">
                <div class="form-group">
                    <label for="userId">Id</label>
                    <input type="text" name="userId">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password">
                </div>
                <div class="form-group">
                    <label for="remember_token">ログインを保持する</label>
                    <input type="checkbox" name="remember_token">
                </div>

                <input type="submit" value="submit" class="submit-btn">
            </form>
        </div>
    </main>
</body>