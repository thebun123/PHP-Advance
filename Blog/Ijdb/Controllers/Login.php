<?php
namespace Ijdb\Controllers;

use Website\Authentication;

class Login {

    public function __construct(private Authentication $authentication){
    }

    public function login(){
        return array(
            'title'=> 'Log in',
            'template'=> 'loginForm.html.php'
        );
    }

    public function loginSubmit(){
        $success = $this->authentication->login($_POST['email'], $_POST['password']);
        if ($success){
            return array(
                'template'=> 'loginSuccess.html.php',
                'title'=> 'Log In Successful'
            );
        }
        else {
            return array(
                'title'=> 'Log in',
                'template'=> 'loginForm.html.php',
                'variables'=> [
                    'errorMessage'=> true
                ]
            );
        }
    }

    public function logout(){
        $this->authentication->logout();
        header('location: /');
    }
}