<?php


class AuthenticationManager
{

    public function connectUser($login, $password){
        $utilisateur= new Account();
        return false;
    }

    public function isUserConnected(){
        if(isset($_SESSION["utilisateur"])) return true;
        return false;
    }

    public function isAdminConnected(){

    }

    public function getUserName(){

    }

    public function disconnectUser(){

    }

}