<?php
require APP_ROOT . '/views/inc/head.php';
?>
<?php
require APP_ROOT . '/views/inc/nav.php';
?>
<main>
    <h1>Créé un compte bancaire virtuel</h1>
    <form method="post" action="<?= URL_ROOT ?>/Dashboard/add_bank">
        <input type="text" name="account_name" placeholder="Nom du compte">
        <select name="account_type">
            <?php foreach ($data['allBankType'] as $account_type) : ?>
                <option value="<?= $account_type['id'] ?>"><?= $account_type['text'] ?></option>
            <?php endforeach; ?>
        </select>
        <input placeholder="Solde" name="account_balance">
        <select name="account_currency">
            <?php foreach ($data['allCurrencies'] as $currency) : ?>
                <option value="<?= $currency ?>"><?= $currency ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" id="submit" value="submit">Créer la banque</button>
    </form>
</main>
