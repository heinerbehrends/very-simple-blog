$(function() {

  var postsElement = $("#posts");
  var categoriesListElement = $("#selectCategory");

  $.ajax({
    method: "GET",
    url: "php/api.php?category=ALL",
  }).done(function(data) {
    addPosts(data);
  })

  $.ajax({
    method: "GET",
    url: "php/categories_api.php",
  }).done(function(data) {
    $.each(data, function(key, value) {
      categoriesListElement.append(makeCategoryListItem(value["id"], value["category"]));
    })
    $(".category-menu").click(function() {
      var category = $(this)[0].getAttribute("id");
      $(this).addClass('active');
      $(this).siblings().removeClass('active');
      $.ajax({
        method: "GET",
        url: "php/api.php?category=" + category,
      }).done(function(data) {
        postsElement.contents().fadeOut();
        addPosts(data);
      });
    })
  })

  function addPosts(data) {
    $.each(data, function(key, value) {
      var post = makePost(
        value["title"], value["post"], value['id'], parseInt(value["comments_on_off"])
      );
      postsElement.append(post);
    })
    getComments();
    postComment();
  }

  function addDeleteHandler() {
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
  }

  function getComments() {
    $.ajax({
      method: 'GET',
      url: "php/comments_api.php"
    }).done(function(data) {
      $('.comment').each(function() {
        for (var i=0; i<data.length; i++) {
          if (data[i]['id'] === $(this).attr('id')) {
            $(this).append(makeComment(data[i]['comment'], data[i]['comment_id']));
          }
        }
      })
      addDeleteHandler();
      addToggleCommentsHandler();
    })
  }

  function addToggleCommentsHandler() {
    $(".comments_on_off").click(function() {
      var on_off = $(this).attr('data-on_off');
      var post_id = $(this).attr('data-id');
      $.ajax({
        method: 'POST',
        url: 'php/comments_on_off.php',
        context: $(this),
        dataType: 'text',
        data: {on_off: on_off, post_id: post_id}
      }).done(function() {
        window.location = self.location;
      })
    })
  }

  function postComment() {
    var forms = $(".commentary-form");
    forms.submit(function(event) {
      event.preventDefault();
      var commentData =
      $.ajax({
        method: 'POST',
        url: "php/comments_api.php",
        context: $(this),
        data: {
          'comment': $(this).find('input').val(),
          'article_id': $(this).data('id')
        },
        success: function(data) {
          console.log(data);
          $(this).prev().append(
            makeComment(data['comment'], data['comment_id'])
          );
          $(this).val('');
          addDeleteHandler();
        }
      })
    })
  }

  function makeCategoryListItem(category_id, category) {
    return '<a id="' + category_id + '" class="nav-link category-menu my-1">' + category + '</a>';
  }

  function makePost(title, post, id, comments_on_off) {
    var post_string = '<h2 class="mb-0">' + title + '</h2>'
    + "<article style='font-size: 1.2rem' class='mb-3'>" + post + '</article>'
    if (comments_on_off) {
      post_string += '<div class="comments_on_off" data-id="' + id + '" data-on_off="0">'
      + '<hr><p class="text-center">Click here to turn off comments for this post</p></div>'
      + '<h3 class="text-center mt-3">Comments</h3>'
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
      + '<hr>'
      + '<p class="text-center">Click here to turn on comments for this post</p></div>'+ '<hr class="mb-5">';
    }
  }

  function makeComment(comment, commentID) {
    return '<div class="row">'
    + '<p class="comment-paragraph col">' + comment + '</p>'
    + '<div class="col-auto ml-auto delete-button">'
    + '<img data-commentID="' + commentID + '" src="icons/delete_icon.svg" alt="delete icon" style="height:23px"></div>'
    + '</div>'
  }
})
