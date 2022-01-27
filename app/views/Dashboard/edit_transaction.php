<?php
require APP_ROOT . '/views/inc/head.php';
?>
<?php
require APP_ROOT . '/views/inc/nav.php';
?>
<main>
    <img src="https://logo.clearbit.com/<?= str_replace(" ", "", $data['transaction']->name) ?>.com" alt="" />
    <h1>Modifier <?= $data['transaction']->name; ?></h1>
    <form method="post" action="<?= URL_ROOT ?>/Dashboard/transaction/<?= $data['transaction']->id_operation ?>/edit">
        <select disabled="disabled">
            <option value="<?= $data['transaction']->id_operation ?>"><?= $data['bank'][0]->account_name ?></option>
        </select>
        <input type="text" name="transaction_name" placeholder="Nom du compte" value="<?= $data['transaction']->name ?>" >
        <select name="transaction_type">
            <option value="<?= $data['transaction']->id_category ?>"><?= $data['transaction']->name_category ?></option>
            <?php foreach ($data['allCategory'] as $category): ?>
                <?php if($data['transaction']->name_category != $category->name_category): ?>
                    <option value="<?= $category->id_category ?>"><?= $category->name_category ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <input type="text" name="transaction_amount" placeholder="Montant" value="<?= $data['transaction']->amount ?>" >
        <select disabled="disabled">
            <option value="<?= $data['bank'][0]->currency; ?>"><?= $data['bank'][0]->currency; ?></option>
        </select>
        <button type="submit" id="submit" value="submit">Modifier ma transaction</button>
    </form>

    <a href="<?= URL_ROOT ?>/dashboard/transaction/<?= $data['transaction']->id_operation ?>/delete" class="danger">Supprimer cette transaction</a>
</main>