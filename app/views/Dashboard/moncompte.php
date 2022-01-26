<?php
	require APP_ROOT . '/views/inc/head.php';
?>
<body>
    <?php
        require APP_ROOT . '/views/inc/nav.php';
    ?>
    <main>
        <h1>Votre compte</h1>
        <h2>Vos informations personnels</h2>
        <input type="text" value="<?= $_SESSION['lastname'] ?>" disabled />
        <input type="text" value="<?= $_SESSION['email'] ?>" disabled />
        <input type="text" value="Your password is crypted" disabled />

        <h2>Zone de Danger</h2>
        <a href="<?= URL_ROOT ?>/users/logout">Se déconnecter</a>
        <a href="<?= URL_ROOT ?>/dashboard/moncompte/delete" class="danger">Supprimer mon compte</a>
    </main>
    <?php
        require APP_ROOT . '/views/inc/footer.php';
    ?>
</body>