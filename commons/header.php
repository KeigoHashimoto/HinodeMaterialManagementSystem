<header>
    <div class="container">
        <div class="logo">
            <h1>
                <a href="../views/index.php">HinodeMaterialManagementSystem</a>
            </h1>
        </div>

        <?php
        if (isset($_SESSION['userId'])) { ?>

            <div class="nav-wrap">
                <nav class="header-nav">
                    <a href="index.php">home</a>
                    <a href="create.php">物品の追加</a>
                    <a href="use.php">物品を使う</a>
                    <a href="replenish.php">物品を補充する</a>
                    <form action="../functions/Logout.php" method="post">
                        <input type="submit" value="ログアウト" name="logout" class="logout">
                    </form>
                </nav>
                <div class="login-user">
                    <p>Login : <?php echo $_SESSION['name'] ?></p>
                </div>
            </div>

        <?php } ?>
    </div>
</header>