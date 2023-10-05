<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/blogs.css">
        <title><?=$title?></title>
    </head>
    <body>
        <header>
            <?php include '../templates/home.html.php'?>
        </header>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/blog/list">Blog List</a></li>
                <?php if($loggedIn): ?>
                    <li><a href="/blog/add">Add New</a></li>
                    <li><a href="/login/logout">Log Out</a></li>
                <?php else:?>
                    <li><a href="/login/login">Log In</a></li>
                <?php endif;?>
            </ul>
        </nav>

        <main id="content">
            <div id="container">
                <?=$output?>
            </div>
        </main>
        <script src="/js/jquery-3.7.1.min.js"></script>
        <script src="/js/script.js"></script>
    </body>


</html>