<nav>
    <h1><?= SITE_NAME ?></h1>
    <ul>
        <?php if(isLoggedIn()): ?>

            <li><a id="nav_dash" href="<?= URL_ROOT ?>/dashboard">Dashboard</a></li>
            <li><a id="nav_trans" href="<?= URL_ROOT ?>/dashboard/add_transaction">Ajouter une transaction</a></li>
            <li><a id="nav_account" href="<?= URL_ROOT ?>/dashboard/moncompte">Mon Compte</a></li>
        <?php else: ?>
            <li><a id="nav_connect" href="<?= URL_ROOT ?>/users/login">Se connecter</a></li>
            <li><a id="nav_create" href="<?= URL_ROOT ?>/users/register">Se creer un compte</a></li>

        <?php endif; ?>
    </ul>
</nav>
