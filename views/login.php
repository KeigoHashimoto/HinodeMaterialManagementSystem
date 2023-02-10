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
            <form action="../functions/Login.php" method="post">
                <div class="form-group">
                    <label for="userId">Id</label>
                    <input type="text" name="userId">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password">
                </div>

                <input type="submit" value="submit" class="submit-btn">
            </form>
        </div>
    </main>
</body>