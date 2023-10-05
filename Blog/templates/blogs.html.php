<div class="blogs">
    <table class="blog-list">
        <tr class="blog-header">
            <th>ID</th>
            <th>Thumb</th>
            <th>Title</th>
            <th class="manage" <?php if(!$user): echo 'hidden="hidden"'; endif; ?>>Action</th>
        </tr>
            <?php foreach ($blogs as $blog): ?>

                <tr class="row-data">
                    <th ><?=$blog->id?></th>
                    <th><img class="thumb" src="<?=$blog->image?>"></th>
                    <th><a class="blog-show" href="/api/blog/show/<?=$blog->id?>"><?=$blog->title?></a> </th>

                    <th class="manage" <?php if(!$user): echo 'hidden="hidden"'; endif; ?>> <a class="blog-show"  href="/api/blog/show/<?=$blog->id?>">Show</a> |
                        <a class="blog-remove" href="/api/blog/delete/<?=$blog->id?>">Remove</a> |
                        <a class="blog-edit" href="/blog/edit/<?=$blog->id?>">Edit</a></th>
                </tr>
            <?php endforeach; ?>

    </table>

    <span class="num-page-title"><p>Number of product per page:</p><select class="product-per-page">
            <option id="3" value=3>3</option>
            <option id="5" value=5>5</option>
            <option id="7" value=7>7</option>
            <option id="-1" value=-1>All</option>
        </select></span>
    <p class="select-page">Select page:
    <?php $numPages = ceil($totalBlogs/$limit); ?>
    <span class="page-number">
        <?php for ($i=1; $i <= $numPages; $i++): ?>
    <a <?php if($i==$currentPage): echo 'class="currentpage"'; endif; ?> href="/blog/list/<?=$i?>/<?=$limit?>"><?=$i?></a>
        <?php endfor; ?>
        </span>
    </p>
</div>