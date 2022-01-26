<?php
require APP_ROOT . '/views/inc/head.php';
?>
<?php
require APP_ROOT . '/views/inc/nav.php';
?>
<main>
    <h1>Ajouter une Transaction</h1>
    <form method="post" action="<?= URL_ROOT ?>/Dashboard/add_transaction">
        <select name="transaction_bank">
        <?php foreach ($data['bankAccounts'] as $bank): ?>
            <option value="<?= $bank->account_name ?>"><?= $bank->account_name ?></option>
        <?php endforeach; ?>
        </select>

        <input placeholder="Nom de la transaction" name="transaction_name" />
        <select name="transaction_category">
        <?php foreach ($data['allCategory'] as $category): ?>
            <option value="<?= $category->name_category ?>" ><?= $category->name_category ?></option>
        <?php endforeach; ?>
        </select>
        <input placeholder="Montant" name="transaction_amount"/>
        <button type="submit" id="submit" value="submit">Effectuer la transaction</button>
    </form>
</main>
