<?php
	require APP_ROOT . '/views/inc/head.php';
?>
<body>
    <?php
        require APP_ROOT . '/views/inc/nav.php';
    ?>
    <main>
        <h1>Bonjour <?= $_SESSION['lastname'] ?></h1>
        <?php if(empty($data['bankAccounts'])) : ?>
            <p>Vous n'avez pas encore de compte bancaire, <a href="<?= URL_ROOT ?>/dashboard/add_bank">Ajouter une banque</a></p>
        <?php else: ?>
            <p>Bienvenue sur votre Dashboard.</p>
            <p>Votre solde total est de <?= $data['totalBalance']  . " " .  $data['balanceCurrency'] ?></p>
            <p>Vous avez <?= count($data['bankAccounts']) ?> compte(s) bancaire(s)</p>
            <h1>Mes comptes</h1>

            <div class="my-accounts-list">
                <a href="<?= URL_ROOT ?>/dashboard/add_bank" class="bankAccounts">
                    <img src="https://logo.clearbit.com/<?= $data['bankExempleList'][array_rand($data['bankExempleList'])] ?>.com" alt="" />
                    <h3>+ Ajouter un compte</h3>
                </a>
                <?php foreach($data['bankAccounts'] as $bankAccount): ?>
                    <a href="<?= URL_ROOT ?>/dashboard/bank/<?= $bankAccount->id_bank_account ?>" class="bankAccounts">
                        <img src="https://logo.clearbit.com/<?= str_replace(" ", "", $bankAccount->account_name) ?>.com" alt="" />
                        <h3><?= $bankAccount->account_name ?></h3>
                        <p><?= $bankAccount->balance  . " " .  $bankAccount->currency ?></p>
                    </a>
                <?php endforeach; ?>
            </div>

            <h1>Mes transactions recentes</h1>

            <div class="my-accounts-list">
                <a href="<?= URL_ROOT ?>/dashboard/add_transaction" class="bankAccounts">
                    <h3>+ Ajouter une transaction</h3>
                </a>
                <?php foreach($data['transactions'] as $transactions): ?>
                    <?php if(!empty($transactions)): ?>
                        <?php foreach($transactions as $transaction): ?>
                            <a href="<?= URL_ROOT ?>/dashboard/transaction/<?= $transaction->id_operation ?>" class="bankAccounts">
                                <img src="https://logo.clearbit.com/<?= str_replace(" ", "", $transaction->name) ?>.com" alt="" />
                                <h3><?= ucfirst($transaction->name) ?></h3>
                                <?php
                                    $symbol = ($transaction->type == "debit") ? "-" : "+";
                                ?>
                                <p><?= $symbol . $transaction->amount  . " " .  $transactions[0]->currency ?></p>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    <?php
        require APP_ROOT . '/views/inc/footer.php';
    ?>
</body>