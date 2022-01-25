<?php
require APP_ROOT . '/views/inc/head.php';
?>

<div class="navbar dark">
    <?php
    require APP_ROOT . '/views/inc/nav.php';
    ?>
</div>

<div class="container-login">
    <div class="wrapper-login">
        <h2>Signin</h2>
        <form method="post" action="<?= URL_ROOT ?>/Dashboard/add_bank">
            <input type="text" name="account_name" placeholder="Nom du compte">
            <select name="account_type">
                <?php foreach ($data['allBankType'] as $accountType): ?>
                    <option value="<?= $accountType ?>"><?= $accountType ?></option>
                <?php endforeach; ?>
            <button type="submit" id="submit" value="submit">Sign In</button>
        </form>
    </div>
</div>