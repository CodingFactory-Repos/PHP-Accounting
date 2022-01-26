<?php


class UserModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function login($email, $password)
    {
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->fetch();
        $hashedPassword = $row->password;

        if(password_verify($password, $hashedPassword)){
            return $row;
        } else {
            return false;
        }
    }

    public function register($data)
    {
        $this->db->query('INSERT INTO user (email, password, lastname) VALUES (:email, :password, :lastname)');
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':lastname', $data['lastname']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
        if(count($this->db->fetchAll()) > 0){
            return true;
        } else {
            return false;
        }
    }
}