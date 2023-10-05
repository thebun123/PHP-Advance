<?php if($user):?>

<form class="form-data" action="" method="post">
        <input id="id" type="hidden" name="blog[id]" value="<?=$blog->id ?? ''?>">

        <label for="title">Title: </label>
        <input id="title" name="blog[title]" type="text" value="<?=$blog->title ?? ''?>">

        <label  for="status" >Status:</label>
        <select id="status" name="blog[status]">
            <option <?php if ( $blog && !$blog->status): echo 'selected'; endif; ?> value=0>Disable</option>
            <option <?php if ($blog && $blog->status): echo 'selected'; endif; ?> value=1>Enable</option>
        </select>

        <div class="preview">
            <?php if($blog && $blog->image):?>
            <img class="preview" src="<?=$blog->image?>">
            <?php endif;?>
        </div>

        <label for="image">Image: </label>
        <input id="image" type="file" name="blog[image]" multiple accept="image/*">

        <label for="description">Description: </label>
        <textarea id="description" name="blog[description]" rows="10" cols="100"><?=$blog->description ?? ''?></textarea>

        <input type="submit" name="submit" value="Save">
        <input type="submit" name="cancel" value="Cancel">
    </form>
<?php else: ?>
<p>Please log in to edit this blog.</p>
<?php endif;?>