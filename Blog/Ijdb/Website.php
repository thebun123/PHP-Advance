<?php
namespace Ijdb;

use Ijdb\Controllers\Author;
use Ijdb\Controllers\Blog;
use Ijdb\Controllers\Login;
use Website\Authentication;
use Website\DatabaseTable;

class Website implements \Website\Website
{
    private $pdo;
    private ?DatabaseTable $blogTable;
    private ?DatabaseTable $authorTable;
    private $authentication;

    public function __construct(){
        $pdo = new \PDO('mysql:host=mysql;dbname=my_blog;charset=utf8mb4', 'root', '123456Aa');
        $this->blogTable = new DatabaseTable($pdo, 'blog', 'id', '\Ijdb\Entity\Blog', [&$this->authorTable]);
        $this->authorTable = new DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this->blogTable]);
        $this->authentication = new Authentication($this->authorTable, 'email', 'password');

        // create database at the first time
        if (file_exists(__DIR__ . '/setup.php')){
            include (__DIR__ . '/setup.php');
            if (isset($initialQuery)){
                $pdo->prepare($initialQuery)->execute();;
                unlink(__DIR__ . '/setup.php');
            }
        }
    }

    public function checkLogin(string $uri): ?string{
        if (isset($restrictPages[$uri])) {
            if ($this->authentication->isLoggedIn() || !$this->authentication->getUser()->hasPermission($restrictPages)) {
                header('location: /login/login');
            }
        }
        return $uri;
    }

    public function getDefaultRoute(): string
    {
        return 'blog/home';
    }


    public function getLayoutVariables(): array{
        return array(
            'loggedIn'=> $this->authentication->isLoggedIn()
        );
    }


    public function getController(string $controllerName): object
    {
        $controllers = [
            'blog'=> new Blog($this->blogTable, $this->authorTable, $this->authentication),
            'login'=> new Login($this->authentication),
            'author'=> new Author($this->authorTable),
        ];
        return $controllers[$controllerName];
    }
}