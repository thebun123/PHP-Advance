<?php
namespace Ijdb\Entity;

class Author{
    const EDIT_BLOG = 1;
    const DELETE_BLOG = 2;
    const LIST_CATEGORIES = 4;
    const EDIT_CATEGORY = 8;
    const DELETE_CATEGORY = 16;
    const EDIT_USER_ACCESS = 32;

    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct(private \Website\DatabaseTable $blogTable)
    {
    }

    public function hasPermission(int $permission){
        return $this->permissions & $permission;
    }

    public function addBlog(array $blog){
        return $this->blogTable->save($blog);
    }
    public function getBlogs(){
        return $this->blogTable->find('authorId', $this->id);
    }

}