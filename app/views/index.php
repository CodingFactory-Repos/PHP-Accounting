<?php
	require APP_ROOT . '/views/inc/head.php';
?>
<body>
    <?php
        require APP_ROOT . '/views/inc/nav.php';
    ?>

    <header>
        <h1>Welcome to <?= SITE_NAME ?> <?php if(isset($_SESSION['email'])){ echo $_SESSION['email']; } ?> !</h1>
        <h1>Go to 'app/views/index.php' to edit your site</h1>
        <h1>Generate you files on https://mvc-generator.herokuapp.com/</h1>
        <a href="<?= URL_ROOT ?>/users/register">Create account</a>
        <a href="<?= URL_ROOT ?>/users/login">Login</a>

        <?php if(isLoggedIn()): ?>
            <a href="<?= URL_ROOT ?>/users/logout">Logout</a>
        <?php endif; ?>
    </header>

    <main>
        <!-- Generate you files on https://mvc-generator.herokuapp.com/ -->
    </main>
    <?php
        require APP_ROOT . '/views/inc/footer.php';
    ?>
</body>