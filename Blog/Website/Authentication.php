<?php
namespace Website;
class Authentication{

    public function __construct(
        private DatabaseTable $user,
        private string $usernameColumn,
        private string $passwordColumn
    ){
        session_start();
    }

    public function getUser(): ?object{
        if ($this->isLoggedIn()){
            return $this->user->find($this->usernameColumn, strtolower($_SESSION['username']))[0];
        }
        else{
            return null;
        }
    }
    public function login(string $username, string $password): bool
    {
        $user = $this->user->find($this->usernameColumn, strtolower($username));

        if (!empty($user) && password_verify($password, $user[0]->{$this->passwordColumn})){
            session_regenerate_id();
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $user[0]->{$this->passwordColumn};
            return true;
        }
        else{
            return false;
        }
    }

    public function isLoggedIn(): bool
    {
        if (empty($_SESSION['username'])){
            return false;
        }

        $user = $this->user->find($this->usernameColumn, strtolower($_SESSION['username']));

        if (!empty($user) && $user[0]->{$this->passwordColumn} === $_SESSION['password']){
            return true;
        }
        else {
            return false;
        }
    }

    public function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        session_regenerate_id();
    }

}