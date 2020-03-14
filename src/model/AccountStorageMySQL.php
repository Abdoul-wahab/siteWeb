<?php


class AccountStorageMySQL implements AccountStorage
{

    private $db;

    /**
     * AccountStorageMySQL constructor.
     */
    public function __construct(PDO $db)
    {
        $this->db= $db;
    }

    /*
     * est censee renvoyer l'instance de Account correspondant au couple login-mot de passe s'il est correct
     * */
    function checkAuth($login, $password)
    {
        // TODO: Implement checkAuth() method.
        $stmt= $this->db->prepare('SELECT * FROM account WHERE login= :login');
        $stmt->execute(array('login'=> $login));
        $result= $stmt->fetch();
        if(count($result['login'])> 0){
            $hash = $result['password'];
            if (password_verify($password, $hash)) return true;
        }
        return false;
    }

    public function read($id)
    {
        // TODO: Implement read() method.
        $stmt= $this->db->prepare('SELECT * FROM account WHERE id= :id');
        $stmt->execute(array('id'=> $id));
        $result= $stmt->fetch();
        $account= new Account($result['id'], $result['login'], $result['password'], $result['status']);
        return $account;
    }

    public function checkLogin($login)
    {
        // TODO: Implement read() method.
        $stmt= $this->db->prepare('SELECT * FROM account WHERE login like :login');
        $stmt->execute(array('login'=> $login));
        $result= $stmt->fetch();
        if(count($result['login'])> 0){
            return false;
        }
        return true;
    }

    public function readAll()
    {
        // TODO: Implement readAll() method.
        $stmt= $this->db->prepare('SELECT * FROM account');
        $stmt->execute();
        $result= $stmt->fetchAll();
        $data= array();
        foreach ($result as $key => $account){
            $data[$account['id']]= new Account($account['id'], $account['login'], $account['password'], $account['status']);
        }
        return $data;
    }

    public function create($data)
    {
        // TODO: Implement create() method.
        $stmt= $this->db->prepare('INSERT INTO account (login, password) VALUES (:login , :password)');
        $stmt->execute(array('login'=> $this->noScript($data['login']), 'password'=> password_hash($data['login'], PASSWORD_DEFAULT)));
    }

    public function readByLogin($login){
        $stmt= $this->db->prepare('SELECT * FROM account WHERE login like :login');
        $stmt->execute(array('login'=> $login));
        $result= $stmt->fetch();
        if(count($result['login'])> 0){
            return $result;
        }
        return false;
    }


    public function noScript($value){
        $value= htmlentities($value);
        return $value;
    }
}