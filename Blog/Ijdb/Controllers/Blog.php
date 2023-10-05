<?php
namespace Ijdb\Controllers;

use \Website\DatabaseTable;
use \Website\Authentication;
class Blog
{
    public function __construct(private DatabaseTable $blogTable,
                                private DatabaseTable $authorTable,
                                private Authentication $authentication
                                ){
    }

    public function list(int $page = 0, int $limit = 3): array
    {
        $offset = ($page-1)*$limit;
        $totalBlogs = $this->blogTable->total();
        $user = $this->authentication->getUser();

        // show all blogs for admin
        if ($user){
            $blogs = $this->blogTable->find('id ASC', $limit, $offset);
            $totalBlogs = count($blogs);
        }
        else{
            // show all enable blogs for guess users
            $blogs = $this->blogTable->find('status', 1,'id ASC', $limit, $offset);
        }
        return array(
            'template' => 'blogs.html.php',
            'title' => 'Blog List',
            'variables' => [
                'totalBlogs' => $totalBlogs,
                'blogs' => $blogs,
                'user'=> $user,
                'currentPage'=> $page,
                'limit'=>$limit,
            ]
        );
    }

    public function home()
    {
        $title = 'My Blog';
        return array(
            'template' => 'home.html.php',
            'title' => $title
        );
    }

    public function delete($id = null)
    {
        $user = $this->authentication->getUser();
        // user logged in
        if ($user){
            $this->blogTable->delete('id', $id ?? $_POST['id']);
        }
        else{
            return;
        }
    }


    function uploadImage($file){
        $path = 'images/';
        if (!$file)
        {
            return '/images/apple.png';
        }
        else{
            $img = $file['name']['image'];
            $tmp = $file['tmp_name']['image'];

            strtolower(pathinfo($img, PATHINFO_EXTENSION));
            $final_image = rand(1000,1000000).$img;
            $path = $path.strtolower($final_image);
            if (move_uploaded_file($tmp,$path)){
                return '/' . $path; // amend the path to insert into database
            }
        }
        return '/images/apple.png';
    }

    public function editSubmit($id = null){
        // get currently user
        $author = $this->authentication->getUser();
        if (isset($id)) {
            $blog = $this->blogTable->find('id', $id)[0] ?? null;
        }
        $blog = $_POST['blog'];

        // process file
        $blog['image'] = $this->uploadImage($_FILES['blog']);

        // set date
        $currentTime = date("Y-m-d H:i:s");
        $blog['updated'] = $currentTime;
        $blog['created'] = $blog['created'] ?? $currentTime;

        $author->addBlog($blog);
    }
    public function edit($id = null)
    {
        $user = $this->authentication->getUser();
        if (isset($id)) {
            $blog = $this->blogTable->find('id', $id)[0] ?? null;
        }
        $title = 'Edit a blog';
        return array(
            'template' => 'editBlog.html.php',
            'title' => $title,
            'variables' => [
                'blog' => $blog ?? null,
                'user'=> $user,
            ]
        );
    }

    public function add(){
        $user = $this->authentication->getUser();
        $title = 'Add new blog';
        return array(
            'template' => 'editBlog.html.php',
            'title' => $title,
            'variables' => [
                'blog' => null,
                'user'=> $user,
            ]
        );
    }

    public function show($id){
        $blog = $this->blogTable->find('id', $id)[0] ?? null;
        return array(
            'template' => 'showBlog.html.php',
            'title' => 'View',
            'variables' => [
                'blog' => $blog ?? null,
            ]
        );
    }

}