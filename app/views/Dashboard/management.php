<?php
	require APP_ROOT . '/views/inc/head.php';
?>
<body>
    <?php
        require APP_ROOT . '/views/inc/nav.php';
    ?>
    <main>
        <img src="https://logo.clearbit.com/<?= str_replace(" ", "", $data['bankAccount']->account_name) ?>.com" alt="" />
        <h1>Gérer <?= $data['bankAccount']->account_name ?></h1>
        <h2>Il vous reste <?= $data['bankAccount']->balance ?> <?= $data['bankAccount']->currency ?></h2>


        <a href="<?= URL_ROOT ?>/dashboard/management/<?= $data['bankAccount']->id_bank_account ?>/delete">Supprimer ce compte</a>
    </main>

    <?php
        require APP_ROOT . '/views/inc/footer.php';
    ?>
</body>