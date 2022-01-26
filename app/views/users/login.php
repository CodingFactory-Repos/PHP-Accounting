<?php
require APP_ROOT . '/views/inc/head.php';
?>
<?php
require APP_ROOT . '/views/inc/nav.php';
?>

<main>
    <h2>Signin</h2>
    <form method="post" action="<?= URL_ROOT ?>/users/login">
        <input type="text" name="email" placeholder="Email">
        <span class="invalidFeedback">
            <?= $data['emailError'] ?>
        </span>

        <input type="password" name="password" placeholder="Password">
        <span class="invalidFeedback">
            <?= $data['passwordError'] ?>
        </span>

        <button type="submit" id="submit" value="submit">Sign In</button>

        <p class="options">Not registered yet? <a id="linkcreate" href="<?= URL_ROOT ?>/users/register">Create an account</a></p>
    </form>
</main>