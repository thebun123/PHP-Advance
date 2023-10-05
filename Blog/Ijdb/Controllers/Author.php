<?php

namespace Ijdb\Controllers;
use \Website\DatabaseTable;

class Author
{
    public function __construct(private DatabaseTable $authorTable){
    }

    public function checkExistAccount($author)
    {
        return count($this->authorTable->find('email', $author['email'])) > 0;
    }

    public function addBlog(array $blog){
        return $this->authorTable->save($blog);
    }

    public function registrationFormSubmit(){
        $author = $_POST['author'];
        $errors = [];

        if (empty($author['name'])){
            $errors[] = 'Name cannot be blank';
        }

        if (empty($author['email'])){
            $errors[] = 'Email cannot be blank';
        }
        else if (!filter_var($author['email'], FILTER_VALIDATE_EMAIL)){
            $errors[] = 'Invalid email address';
        }
        else if ($this->checkExistAccount($author)){
            $errors[] = 'That email address is already registered';
        }

        if (empty($author['password'])){
            $errors[] = 'Password cannot be blank';
        }

        if (empty($errors)){
            // hash the password before saving it in database
            $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);

            // store the author in the database
            $this->authorTable->save($author);
            header('location: /author/success');
        }
        else{
            return [
                'template' => 'register.html.php',
                'title' => 'Register an account',
                'variables' => [
                    'errors'=> $errors,
                    'author' => $author
                ]
            ];
        }
    }

    public function list(){
        $authors = $this->authorTable->findAll(null);

        return [
            'template'=> 'authorList.html.php',
            'variables'=> [
                'authors'=> $authors
            ]
        ];
    }

    public function permissions($id = null){
        $author = $this->authorTable->find('id', $id)[0];

        $reflected = new \ReflectionClass('\Ijdb\Entity\Author');
        $constants = $reflected->getConstants();

        return [
            'title'=>'Edit permissions',
            'template'=> 'permissions.html.php',
            'variables'=>[
                'author' => $author,
                'permissions' => $constants
            ]
        ];
    }

    public function permissionsSubmit($id = null){
        $author = [
            'id'=> $id,
            'permissions'=> array_sum($_POST['permissions'] ?? [])
        ];

        $this->authorTable->save($author);

        header('location: /author/list');
    }

    public function registrationForm(){
        return [
            'template' => 'register.html.php',
            'title' => 'Register an account'
        ];
    }

    public function success(){
        return [
            'template' => 'registersuccess.html.php',
            'title' => 'Registration Successful'
        ];
    }
}
