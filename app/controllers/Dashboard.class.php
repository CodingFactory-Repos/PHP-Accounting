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
                'cssFile' => 'Dashboard'
			];

			$this->render('dashboard/index', $data);
		}

        public function add_bank() {
            $allEnum = $this->dashboardModel->getBankAccountAllType()[0]->AllPossibleEnumValues;

            $allEnum = explode("enum('", $allEnum);
            $allEnum = explode("')", $allEnum[1]); // Remove the last "')"
            $allEnum = explode("','", $allEnum[0]);

            $data = [
                'headTitle' => 'Dashboard - Add Bank',
                'title' => 'Add Bank',
                'cssFile' => 'Dashboard',
                'allBankType' => $allEnum
            ];

            $this->render('dashboard/add_bank', $data);
        }
	}