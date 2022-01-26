<?php
	/**
	 * Class Dashboard
	 */
	class Dashboard extends Controller {
		/**
		 * Dashboard constructor.
		 */
		public function __construct() {
            if (!isLoggedIn()) {
                header("Location: " . URL_ROOT . "/users/login");
            }

		    // Import SQL commands
			if(!empty(DB_HOST)){
			    $this->dashboardModel = $this->loadModel('DashboardModel');
			}
		}

		/**
		 * views/Dashboard/index.php
		 */
		public function index() {
			$data = [
                'headTitle' => 'Dashboard',
				'title' => 'Dashboard has been generated',
                'cssFile' => 'Dashboard',
                'bankAccounts' => $this->dashboardModel->getBankAccounts(),
                'bankExempleList' => ['N26', 'LCL', 'Revolut', 'bnpparibas', 'LaBanquePostale', 'lydia-app'],
                'totalBalance' => $this->dashboardModel->getTotalBalance(),
                'balanceCurrency' => $this->dashboardModel->getBalanceCurrency()->currency
            ];

			$this->render('dashboard/index', $data);
		}

        public function add_bank() {
            $allBankTypes = $this->dashboardModel->getEnum('bank_account', 'type');
            $allCurrencies = $this->dashboardModel->getEnum('bank_account', 'currency');

            if(isset($_POST['account_name']) && isset($_POST['account_type'])) {
                $account_name = $_POST['account_name'];
                $account_type = $_POST['account_type'];
                $account_balance = str_replace(',', '.', $_POST['account_balance']);
                $account_currency = $_POST['account_currency'];

                if(is_numeric($account_balance) == 1){
                    if(in_array($account_type, $allBankTypes) && in_array($account_currency, $allCurrencies)) {
                        if($this->dashboardModel->getBankNumber() < 10) {
                            if (empty($this->dashboardModel->getBankFromName($account_name))) {
                                if($this->dashboardModel->addBank($account_name, $account_type, $account_balance, $account_currency)) {
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
                for($i = 0; $i < count($allBankTypes); $i++) {
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

        public function management($bank_id = null, $action = null) {
            $bank = $this->dashboardModel->getBankFromId($bank_id);

            if ($bank_id == null || is_numeric($bank_id) != 1 || empty($bank)) { //bank_id == null
                $this->renderError(401, "Unauthorized");
            }

            if ($action == "delete") {
                if($this->dashboardModel->deleteBank($bank_id)){
                    header("Location: " . URL_ROOT . "/dashboard");
                } else {
                    echo "A, Quelque chose vient de se passer";
                }
            }

			$data = [
                'headTitle' => 'Dashboard - Management',
				'title' => 'Dashboard has been generated',
                'cssFile' => 'management.dashboard',
                'bankAccount' => $bank[0]
			];

			$this->render('dashboard/management', $data);
		}

        public function moncompte($action = null)
        {
            $data = [
                'headTitle' => 'Dashboard - Mon compte',
                'title' => 'Mon compte',
                'cssFile' => 'moncompte.dashboard'
            ];

            if($action == "delete"){
                if($this->dashboardModel->deleteUser($_SESSION['user_id'])){
                    header("Location: " . URL_ROOT . "/users/logout");
                } else {
                    echo "A, Quelque chose vient de se passer";
                }
            }

            $this->render('dashboard/moncompte', $data);
        }
	}