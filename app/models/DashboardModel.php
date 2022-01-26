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

    public function getBankAccounts()
    {
        $this->db->query('SELECT * FROM bank_account WHERE id_user = :id_user');
        $this->db->bind(':id_user', $_SESSION['user_id']);
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
}
