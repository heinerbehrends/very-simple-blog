$(function() {

  function makeCategoryListItem(category_id, category) {
    return '<li id="' + category_id + '" class="list-group-item border-0 category-menu">' + category + '</li>';
  }
  var categoriesListElement = $("#selectCategory");

  function makeComment(comment) {
    '<p class="commentParagraph">' + comment + '</p>'
  }

  function makePost(title, post, id, comments_on_off) {
    var post_string = '<h2 class="mb-0">' + title + '</h2>'
    + "<p style='font-size: 1.2rem' class='mb-3'>" + post + '</p>'
    if (comments_on_off) {
      post_string += '<div class="comments_on_off" data-id="' + id + '" data-on_off="0">'
      + '<img style="height: 30px" class="mr-3 my-3" src="icons/on-off-button.svg">'
      + '<span>Click here to turn off comments for this post</span></div>'
      + '<h3 class="mt-3">Comments</h3>'
      + '<div id="' + id + '" class="comment"></div>'
      + '<form data-id="' + id + '"class="commentary-form" method="post" enctype="multipart/form-data">'
      + '<div class="form-group">'
      + '<label for="commentary" class="sr-only">Commentary</label>'
      + '<input type="text" class="form-control" id="commentary" placeholder="Enter your commentary">'
      + '</div></form><hr class="my-5">';
      return post_string;
    }
    else {
      return post_string += '<div class="comments_on_off" data-id="' + id + '" data-on_off="1">'
      + '<img style="height: 30px" class="mr-3 my-3" src="icons/on-off-button.svg">'
      + '<span>Click here to turn on comments for this post</span></div>'+ '<hr class="my-5">';
    }
  }

  var postsElement = $("#posts");

  $.ajax({
    method: "GET",
    url: "php/categories_api.php",
  }).done(function(data) {
    $.each(data, function(key, value) {
      var category = value["category"];
      var category_id = value["id"];
      var categoryListItem = makeCategoryListItem(category_id, category);
      categoriesListElement.append(categoryListItem);
    })
    getCategoryPosts();
  })


  $.ajax({
    method: "GET",
    url: "php/api.php?category=ALL",
  }).done(function(data) {
    $.each(data, function(key, value) {
      var id = value['id'];
      var title = value["title"];
      var post = value["post"];
      var comments_on_off = parseInt(value["comments_on_off"]);
      var post = makePost(title, post, id, comments_on_off);
      postsElement.append(post);
    })

    $.ajax({
      method: 'GET',
      url: "php/comments_api.php",
    }).done(function(data) {
      $('.comment').each(function() {
        for (var i=0; i<data.length; i++) {
          if (data[i]['id'] === $(this).attr('id')) {
            $(this).append(
              '<div class="row">'
              + '<p class="comment-paragraph col">' + data[i]['comment'] + '</p>'
              + '<div class="col-auto ml-auto delete-button">'
              + '<img data-commentID="' + data[i]['comment_id'] + '" src="icons/delete_icon.svg" alt="delete icon" style="height:15px"></div>'
              + '</div>'
            );
          }
        }
      })
      $(".delete-button").click(function() {
        var comment_id = $(this).children().attr('data-commentID');
        $.ajax({
          method: 'POST',
          url: 'php/delete_comment.php',
          context: $(this),
          data: {comment_id: comment_id}
        }).done(function() {
          $(this).parent().remove();
        })
      })
      $(".comments_on_off").click(function() {
        var on_off = $(this).attr('data-on_off');
        var post_id = $(this).attr('data-id');
        $.ajax({
          method: 'POST',
          url: 'php/comments_on_off.php',
          context: $(this),
          dataType: 'text',
          data: {on_off: on_off, post_id: post_id}
        }).done(function(data) {
          window.location.reload(true);
        })
      })
    })

    var forms = $(".commentary-form");
    forms.submit(function(event) {
      event.preventDefault();
      var commentData = {};
      commentData['comment'] = $(this).find('input').val();
      commentData['article_id'] = $(this).data('id');
      $.ajax({
        method: 'POST',
        url: "php/comments_api.php",
        dataType: "text",
        context: $(this),
        data: commentData,
        success: function(data) {
          $(this).prev().append(
            '<p class="comment-paragraph">' + data + '</p>'
          );
          $(this).val("");
        }
      })
    })
  })

  function getCategoryPosts() {
    $(".category-menu").click(function() {
      var category = $(this)[0].getAttribute("id");
      postsElement.html("");
      $.ajax({
        method: "GET",
        url: "php/api.php?category=" + category,
      }).done(function(data) {
        $.each(data, function(key, value) {
          var postCategory = value["category"];
          var id = value['id'];
          var title = value["title"];
          var post = value["post"];
          var comments_on_off = parseInt(value["comments_on_off"]);
          var post = makePost(title, post, id, comments_on_off);
          postsElement.append(post);
        })
        $(".delete-button").click(function() {
          var comment_id = $(this).children().attr('data-commentID');
          $.ajax({
            method: 'POST',
            url: 'php/delete_comment.php',
            context: $(this),
            data: {comment_id: comment_id}
          }).done(function() {
            $(this).parent().remove();
          })
        })
        $(".comments_on_off").click(function() {
          var on_off = $(this).attr('data-on_off');
          var post_id = $(this).attr('data-id');
          $.ajax({
            method: 'POST',
            url: 'php/comments_on_off.php',
            context: $(this),
            dataType: 'text',
            data: {on_off: on_off, post_id: post_id}
          }).done(function(data) {
            window.location.reload(true);
          })
        })
      })
    })
  }
})
