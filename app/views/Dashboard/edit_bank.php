<?php
require APP_ROOT . '/views/inc/head.php';
?>
<?php
require APP_ROOT . '/views/inc/nav.php';
?>
<main>
    <h1>Modifier <?= $data['bankAccount']->account_name; ?></h1>
    <form method="post" action="<?= URL_ROOT ?>/Dashboard/bank/<?= $data['bankAccount']->id_bank_account ?>/edit">
        <input type="text" name="account_name" placeholder="Nom du compte" value="<?= $data['bankAccount']->account_name ?>" >
        <select name="account_type">
            <option value="<?= $data['bankAccount']->type ?>"><?= ucfirst(str_replace("_", " ", $data['bankAccount']->type)) ?></option>
            <?php
                foreach ($data['allBankType'] as $account_type):
                    if($data['bankAccount']->type != $account_type['id']):
            ?>
                <option value="<?= $account_type['id'] ?>"><?= $account_type['text'] ?></option>
            <?php endif; endforeach; ?>
        </select>
        <input placeholder="Solde" value="<?= $data['bankAccount']->balance ?>" name="account_balance" />
        <select name="account_currency">
            <option value="<?= $data['bankAccount']->currency ?>"><?= $data['bankAccount']->currency ?></option>
            <?php
                foreach ($data['allCurrencies'] as $currency) :
                    if($data['bankAccount']->currency != $currency):
            ?>
                <option value="<?= $currency ?>"><?= $currency ?></option>
            <?php endif; endforeach; ?>
        </select>
        <button type="submit" id="submit" value="submit">Modifier la banque</button>
    </form>
</main>