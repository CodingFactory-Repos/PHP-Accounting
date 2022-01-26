<?php
class DashboardModel
{
    private Database $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getEnum($table, $column)
    {
        $this->db->query('SELECT COLUMN_TYPE as AllPossibleEnumValues FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :tablename AND COLUMN_NAME = :columnname');
        $this->db->bind(':tablename', $table);
        $this->db->bind(':columnname', $column);

        return explode("','", explode("')", explode("enum('", $this->db->fetchAll()[0]->AllPossibleEnumValues)[1])[0]); // Remove the first part of the string
    }

    public function getBankNumber()
    {
        $this->db->query('SELECT COUNT(*) FROM bank_account WHERE id_user = :id_user');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        return ((array) $this->db->fetchAll()[0])["COUNT(*)"];
    }

    public function getAllBanksFromAccount()
    {
        $this->db->query('SELECT * FROM bank_account WHERE id_user = :id_user');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        return $this->db->fetchAll();
    }

    public function getTransactionsFromBank($bank_id)
    {
        $this->db->query('SELECT DISTINCT operation.name, operation.amount, category.name_category, category.type FROM operation LEFT JOIN category ON operation.id_category = category.id_category WHERE id_bank_account = :id_bank_account ORDER BY operation.id_operation DESC;');
        $this->db->bind(':id_bank_account', $bank_id);
        return $this->db->fetchAll();
    }

    public function getAllCategory()
    {
        $this->db->query('SELECT * FROM category');
        return $this->db->fetchAll();
    }

    public function addBank($account_name, $account_type, $account_balance, $account_currency)
    {
        $this->db->query('INSERT INTO bank_account (id_user, account_name, type, balance, currency) VALUES (:id_user, :account_name, :account_type, :balance, :currency)');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        $this->db->bind(':account_name', $account_name);
        $this->db->bind(':account_type', $account_type);
        $this->db->bind(':balance', $account_balance);
        $this->db->bind(':currency', $account_currency);
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function editBank($id_bank_account, $account_name, $account_type, $account_balance, $account_currency)
    {
        $this->db->query('UPDATE bank_account SET account_name = :account_name, type = :account_type, balance = :balance, currency = :currency WHERE id_bank_account = :id_bank_account AND id_user = :id_user');
        $this->db->bind(':id_bank_account', $id_bank_account);
        $this->db->bind(':id_user', $_SESSION['user_id']);
        $this->db->bind(':account_name', $account_name);
        $this->db->bind(':account_type', $account_type);
        $this->db->bind(':balance', $account_balance);
        $this->db->bind(':currency', $account_currency);
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function getBankFromId($bank_id)
    {
        $this->db->query('SELECT * FROM bank_account WHERE id_user = :id_user AND id_bank_account = :id_bank_account');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        $this->db->bind(':id_bank_account', $bank_id);
        return $this->db->fetchAll();
    }

    public function getBankFromName($bank_name)
    {
        $this->db->query('SELECT * FROM bank_account WHERE id_user = :id_user AND account_name = :account_name');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        $this->db->bind(':account_name', $bank_name);
        return $this->db->fetchAll();
    }

    public function deleteBank($bank_id)
    {
        $this->db->query('DELETE FROM bank_account WHERE id_user = :id_user AND id_bank_account = :id_bank_account');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        $this->db->bind(':id_bank_account', $bank_id);
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function getTotalBalance()
    {
        $this->db->query('SELECT SUM(balance) FROM bank_account WHERE id_user = :id_user');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        return ((array) $this->db->fetchAll()[0])["SUM(balance)"];
    }

    public function getBalanceCurrency()
    {
        $this->db->query('SELECT currency FROM bank_account WHERE id_user = :id_user GROUP BY currency LIMIT 1');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        return $this->db->fetch();
    }

    public function deleteUser(){
        // Delete users and foreign keys from other tables
        $this->db->query('DELETE FROM user WHERE id_user = :id_user');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function updateBank($bank_id, $balance, $type)
    {
        // Get actual balance from bank account and sustract the amount of the transaction
        $this->db->query('SELECT balance FROM bank_account WHERE id_user = :id_user AND id_bank_account = :id_bank_account');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        $this->db->bind(':id_bank_account', $bank_id);
        $actual_balance = ((array) $this->db->fetchAll()[0])["balance"];

        if ($type == "debit") {
            $new_balance = $actual_balance - $balance;
        } else if ($type == "credit") {
            $new_balance = $actual_balance + $balance;
        }

        // Update balance in bank account
        $this->db->query('UPDATE bank_account SET balance = :balance WHERE id_user = :id_user AND id_bank_account = :id_bank_account');
        $this->db->bind(':id_user', $_SESSION['user_id']);
        $this->db->bind(':id_bank_account', $bank_id);
        $this->db->bind(':balance', $new_balance);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function addTransaction($bank_id, $transaction_name, $category_id, $transaction_amount, $category_type){
        $this->db->query('INSERT INTO operation (id_category, id_bank_account, name, amount, date) VALUES (:id_category, :id_bank_account, :name, :amount, :date)');

        $this->db->bind(':id_bank_account', $bank_id);
        $this->db->bind(':name', $transaction_name);
        $this->db->bind(':id_category', $category_id);
        $this->db->bind(':amount', $transaction_amount);
        $this->db->bind(':date', date('Y-m-d'));

        if($this->db->execute()){
            return $this->updateBank($bank_id, $transaction_amount, $category_type);
        } else {
            return false;
        }
    }
}
