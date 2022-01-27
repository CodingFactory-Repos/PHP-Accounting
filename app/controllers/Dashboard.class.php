<?php

/**
 * Class Dashboard
 */
class Dashboard extends Controller
{
    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        if (!isLoggedIn()) {
            header("Location: " . URL_ROOT . "/users/login");
        }

        // Import SQL commands
        if (!empty(DB_HOST)) {
            $this->dashboardModel = $this->loadModel('DashboardModel');
        }
    }

    /**
     * views/Dashboard/index.php
     */
    public function index()
    {
        $data = [
            'headTitle' => 'Dashboard',
            'title' => 'Dashboard has been generated',
            'cssFile' => 'Dashboard',
            'bankAccounts' => $this->dashboardModel->getAllBanksFromAccount(),
            'bankExempleList' => ['N26', 'LCL', 'Revolut', 'bnpparibas', 'LaBanquePostale', 'lydia-app'],
            'totalBalance' => $this->dashboardModel->getTotalBalance()
        ];

        // Get all transactions of the user
        foreach ($data['bankAccounts'] as $bankAccount) {
            $data['transactions'][intval($bankAccount->id_bank_account)] = $this->dashboardModel->getTransactionsFromBank($bankAccount->id_bank_account);
            if (!empty($data['transactions'][intval($bankAccount->id_bank_account)])) {
                $data['transactions'][intval($bankAccount->id_bank_account)][0]->currency = $bankAccount->currency;
            }
        }

        if (!empty($data['bankAccounts'])) {
            $data['balanceCurrency'] = $this->dashboardModel->getBalanceCurrency()->currency;
        }

        $this->render('dashboard/index', $data);
    }

    public function add_bank()
    {
        $allBankTypes = $this->dashboardModel->getEnum('bank_account', 'type');
        $allCurrencies = $this->dashboardModel->getEnum('bank_account', 'currency');

        if (isset($_POST['account_name']) && isset($_POST['account_type'])) {
            $account_name = $_POST['account_name'];
            $account_type = $_POST['account_type'];
            $account_balance = str_replace(',', '.', $_POST['account_balance']);
            $account_currency = $_POST['account_currency'];

            if (is_numeric($account_balance) == 1) {
                if (in_array($account_type, $allBankTypes) && in_array($account_currency, $allCurrencies)) {
                    if ($this->dashboardModel->getBankNumber() < 10) {
                        if (empty($this->dashboardModel->getBankFromName($account_name))) {
                            if ($this->dashboardModel->addBank($account_name, $account_type, $account_balance, $account_currency)) {
                                header("Location: " . URL_ROOT . "/dashboard");
                            } else {
                                echo "A, Quelque chose vient de se passer";
                            }
                        } else {
                            echo "Il existe déjà un compte à ce nom";
                        }
                    } else {
                        echo "You can only have 10 bank accounts";
                    }
                } else {
                    echo "Invalid account type or currency type";
                }
            } else {
                echo "Invalid account balance";
            }
        } else {
            for ($i = 0; $i < count($allBankTypes); $i++) {
                $allBankTypes[$i] = [
                    'id' => $allBankTypes[$i],
                    'text' => ucfirst(str_replace("_", ' ', $allBankTypes[$i]))
                ];
            }

            $data = [
                'headTitle' => 'Dashboard - Add Bank',
                'title' => 'Add Bank',
                'cssFile' => 'Dashboard',
                'allBankType' => $allBankTypes,
                'allCurrencies' => $allCurrencies
            ];

            $this->render('dashboard/add_bank', $data);
        }
    }

    public function add_transaction()
    {
        $data = [
            'headTitle' => 'Dashboard',
            'title' => 'Dashboard has been generated',
            'cssFile' => 'Dashboard',
            'bankAccounts' => $this->dashboardModel->getAllBanksFromAccount(),
            'allCategory' => $this->dashboardModel->getAllCategory()
        ];

        if (isset($_POST['transaction_name']) && isset($_POST['transaction_amount'])) {
            $transaction_bank = $_POST['transaction_bank'];
            $transaction_name = $_POST['transaction_name'];
            $transaction_category = $_POST['transaction_category'];
            $transaction_amount = str_replace(',', '.', $_POST['transaction_amount']);

            foreach ($data['bankAccounts'] as $bank) {
                $allBanksName[] = $bank->account_name;
            }

            foreach ($data['allCategory'] as $category) {
                $allCategoryName[] = $category->name_category;
            }

            if (is_numeric($transaction_amount) == 1) {
                if (in_array($transaction_bank, $allBanksName) && in_array($transaction_category, $allCategoryName)) {
                    // Get the bank id
                    $bank_id = $data['bankAccounts'][array_search($transaction_bank, $allBanksName)]->id_bank_account;

                    // Get the category id
                    $category_id = $data['allCategory'][array_search($transaction_category, $allCategoryName)]->id_category;
                    $category_type = $data['allCategory'][array_search($transaction_category, $allCategoryName)]->type;

                    if ($this->dashboardModel->addTransaction($bank_id, $transaction_name, intval($category_id), $transaction_amount, $category_type)) {
                        header("Location: " . URL_ROOT . "/dashboard");
                    } else {
                        echo "A, Quelque chose vient de se passer";
                    }
                } else {
                    echo "Désolé, mais il n'y a pas de compte ou de catégorie à ce nom";
                }
            } else {
                echo "Invalid transaction amount";
            }
        }

        $this->render('dashboard/add_transaction', $data);
    }

    public function transaction($transaction_id = null, $action = null)
    {
        if ($transaction_id != null || is_numeric($transaction_id) == 1) {
            $transaction = $this->dashboardModel->getTransactionFromId($transaction_id);
            $bank = $this->dashboardModel->getBankFromId(intval($transaction->id_bank_account));
            $transaction->name_category = $this->dashboardModel->getCategoryFromId(intval($transaction->id_category))->name_category;
            $transaction->type = $this->dashboardModel->getCategoryFromId(intval($transaction->id_category))->type;

            if (empty($bank)) {
                $this->renderError(401, "Unauthorized");
            } else {
                if ($action === "delete") {
                    if ($this->dashboardModel->deleteTransaction($transaction_id, $transaction->id_bank_account, $transaction->type, $transaction->amount)) {
                        header("Location: " . URL_ROOT . "/dashboard");
                    } else {
                        echo "A, Quelque chose vient de se passer";
                    }
                } else if (isset($_POST['transaction_name']) && isset($_POST['transaction_amount'])) {
                    $old_transaction_amount = str_replace(',', '.', $_POST['transaction_amount']);


                    if(is_numeric($old_transaction_amount) == 1){
                        $transaction_name = $_POST['transaction_name'];
                        $transaction_category_id = $_POST['transaction_type'];
                        $transaction_bank = $transaction->id_bank_account;
                        $allCategories = $this->dashboardModel->getAllCategory();

                        $new_transaction_category_type = $this->dashboardModel->getCategoryFromId(intval($transaction_category_id))->type;
                        $old_transaction_category_type = $this->dashboardModel->getCategoryFromId(intval($transaction->id_category))->type;

                        $new_transaction_amount = $old_transaction_amount - $transaction->amount;

                        if($new_transaction_category_type != $old_transaction_category_type){
                            if($new_transaction_category_type == "credit"){
                                $new_transaction_amount = abs($old_transaction_amount) + abs($transaction->amount);
                            } else if($new_transaction_category_type == "debit"){
                                $new_transaction_amount = abs($old_transaction_amount) + abs($transaction->amount);
                            }
                        }

                        foreach ($allCategories as $category) {
                            $allCategoriesId[] = $category->id_category;
                        }

                        if(in_array($transaction_category_id, $allCategoriesId)){
                            if($this->dashboardModel->updateTransaction($transaction_id, $transaction_name, $transaction_category_id, $old_transaction_amount, $new_transaction_amount, $transaction_bank, $new_transaction_category_type)){
                                header("Location: " . URL_ROOT . "/dashboard");
                            } else {
                                echo "A, Quelque chose vient de se passer";
                            }
                        } else {
                            echo "Invalid category";
                        }
                    } else {
                        echo "Invalid transaction amount";
                    }
                } else {
                    $data = [
                        'headTitle' => 'Dashboard',
                        'title' => 'Dashboard has been generated',
                        'cssFile' => 'transaction.Dashboard',
                        'transaction' => $transaction,
                        'bank' => $bank,
                        'allCategory' => $this->dashboardModel->getAllCategory()
                    ];

                    $this->render('dashboard/edit_transaction', $data);
                }
            }
        }
    }

    public function bank($bank_id = null, $action = null)
    {
        $bank = $this->dashboardModel->getBankFromId($bank_id);

        if ($bank_id == null || is_numeric($bank_id) != 1 || empty($bank)) { //bank_id == null
            $this->renderError(401, "Unauthorized");
        }

        if ($action == "delete") {
            if ($this->dashboardModel->deleteBank($bank_id)) {
                header("Location: " . URL_ROOT . "/dashboard");
            } else {
                echo "A, Quelque chose vient de se passer";
            }
        } else if ($action == "edit") {
            $allBankTypes = $this->dashboardModel->getEnum('bank_account', 'type');
            $allCurrencies = $this->dashboardModel->getEnum('bank_account', 'currency');

            if (isset($_POST['account_name']) && isset($_POST['account_type'])) {
                $account_name = $_POST['account_name'];
                $account_type = $_POST['account_type'];
                $account_balance = str_replace(',', '.', $_POST['account_balance']);
                $account_currency = $_POST['account_currency'];

                if (is_numeric($account_balance) == 1) {
                    if (in_array($account_type, $allBankTypes) && in_array($account_currency, $allCurrencies)) {
                        if ($this->dashboardModel->editBank($bank_id, $account_name, $account_type, $account_balance, $account_currency)) {
                            header("Location: " . URL_ROOT . "/dashboard/bank/" . $bank_id);
                        } else {
                            echo "A, Quelque chose vient de se passer";
                        }
                    } else {
                        echo "Invalid account type or currency type";
                    }
                } else {
                    echo "Invalid account balance";
                }
            }

            for ($i = 0; $i < count($allBankTypes); $i++) {
                $allBankTypes[$i] = [
                    'id' => $allBankTypes[$i],
                    'text' => ucfirst(str_replace("_", ' ', $allBankTypes[$i]))
                ];
            }

            $data = [
                'headTitle' => 'Dashboard - Edit',
                'title' => 'Dashboard has been generated',
                'cssFile' => 'bank.dashboard',
                'bankAccount' => $bank[0],
                'allBankType' => $allBankTypes,
                'allCurrencies' => $allCurrencies
            ];

            $this->render('dashboard/edit_bank', $data);
        } else {
            $data = [
                'headTitle' => 'Dashboard - Bank',
                'title' => 'Dashboard has been generated',
                'cssFile' => 'bank.dashboard',
                'bankAccount' => $bank[0]
            ];

            $this->render('dashboard/bank', $data);
        }
    }

    public function moncompte($action = null)
    {
        $data = [
            'headTitle' => 'Dashboard - Mon compte',
            'title' => 'Mon compte',
            'cssFile' => 'moncompte.dashboard'
        ];

        if ($action == "delete") {
            if ($this->dashboardModel->deleteUser()) {
                header("Location: " . URL_ROOT . "/users/logout");
            } else {
                echo "A, Quelque chose vient de se passer";
            }
        }

        $this->render('dashboard/moncompte', $data);
    }
}
