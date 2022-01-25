<?php
class DashboardModel
{
    private Database $db;
    public function __construct()
    {
        $this->db = new Database();
    }
    /* === Exemple Code ===
    
    public function updateExemple($user_id, $username)
    {
        $this->db->query('UPDATE users SET username = :username WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':username', $username);
    
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
    
    public function fetchExemple($user_id)
    {
        $this->db->query('SELECT * FROM users WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->fetch();
    }
    
    public function fetchAllExemple($search)
    {
        $this->db->query('SELECT * FROM posts WHERE post_name LIKE :search');
        $this->db->bind(':search', '%'.$search.'%');
        return $this->db->fetchAll();
    }
    
    */

    public function getBankAccountAllType()
    {
        $this->db->query('SELECT COLUMN_TYPE as AllPossibleEnumValues FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :tablename AND COLUMN_NAME = :columnname');
        $this->db->bind(':tablename', 'bank_account');
        $this->db->bind(':columnname', 'type');
        return $this->db->fetchAll();
    }
}
