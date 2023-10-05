
$(function() {

    // Add quick view of upload image
    $('input#image').on('change', function() {
        var file = this.file;
        $('img.preview').remove();
        $('div.preview').append(
        //     // build  a fake path string for each File
        //     // all that is really needed to display the image
            '<img class="preview" src="' + URL.createObjectURL(file) + '">');
    });

    // Show blog detail
    $('a.blog-show').on('click', function (e) {
        e.preventDefault();
        var url = this.href;
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                data = JSON.parse(data)
                var content = ''
                content += '<h2 class="blog-title">' + data.blog.title + '</h2>';
                content += '<img class="normal" src="' + data.blog.image + '"><br/>';
                content += '<span class="blog-content">' + data.blog.description+'</span>';
                content += '<br/><a class="btn back-button" href="/blog/list"><button>Back</button></a>';

                $("#container").html(content); // update content
            }
        })
    });

    $('.blog-remove').on('click', function (e){
        e.preventDefault();
        var url = this.href;
        console.log(url);
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                // back to the list
                window.location.href = 'http://127.0.0.1/blog/list';
            }
        })
    })

    $('form.form-data').on('submit', function(e){
        e.preventDefault();
        id =  $('form.form-data').find("#id").val();
        data = new FormData(this);
        console.log(data);
        $.ajax({
            url: '/blog/edit/' + id,
            type: "POST",
            data:  data,
            contentType: false,
            cache: false,
            processData:false,
            beforeSend : function()
            {
                $("#err").fadeOut();
            },
            success: function(data)
            {
                console.log('success');
                window.location.href = 'http://127.0.0.1/blog/list';
            },
            error: function(e)
            {
                console.log(e);
            }
        });

    });

    $('.product-per-page').on('change', function(){
        let limit = $('.product-per-page').find(":selected").val();
        $('.row-data').remove();
        $.ajax({
            url: 'http://127.0.0.1/api/blog/list/1/' + limit, //get date of the first page when renew the page
            type: 'get',
            success: function (data) {
                newContent = '';
                console.log(data);
                data = JSON.parse(data);
                blogs = data['blogs']
                for (let i=0; i < blogs.length; i++){
                    newContent += '<tr class="row-data">';
                    newContent += '<th>' + blogs[i]['id'] +'</th>';
                    newContent += '<th><img class="thumb" src="' + blogs[i]['image'] + '"</th>';
                    newContent += '<th><a class="blog-show" href="/api/blog/show/' + blogs[i]['id'] + '">'
                    newContent +=  blogs[i]['title'] + '</a></th>';
                    newContent +=  '<th class="manage" '
                    if ($('th.manage').attr('hidden')){
                        newContent += 'hidden="hidden"'
                    }
                    newContent += '><a class="blog-show" href="/api/blog/show/' + blogs[i]['id'] +'>">Show</a> |' +
                        '<a class="blog-remove" href="/api/blog/delete/ ' + blogs[i]['id'] + '">Remove</a> |' +
                        '<a class="blog-edit" href="/blog/edit/' + blogs[i]['id'] + '">Edit</a></th>';

                }
                $('.blog-header').after(newContent);

                // $('option').attr('id').eq(limit).

                totalBlogs = data['totalBlogs'];
                totalPages = Math.ceil(totalBlogs/limit);

                $('.page-number').remove()
                numPageContents = '<span class="page-number">'
                for (let i = 1; i<= totalPages; i++){
                    numPageContents += '<a  class="page-' + i;
                    if (i===1){
                        numPageContents += ' currentpage';
                    }
                    numPageContents +=  '"'
                    numPageContents += 'href="/blog/list/' + i + '/' + limit + '">' + i + '</a>';
                }
                $('p.select-page').append(numPageContents);
            }
        });

    });


});