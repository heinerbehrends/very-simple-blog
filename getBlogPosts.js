$(function() {

  function makeCategoryListItem(category_id, category) {
    return '<li id="' + category_id + '" class="list-group-item border-0 category-menu">' + category + '</li>';
  }
  var categoriesListElement = $("#selectCategory");

  function makeComment(comment) {
    '<p class="commentParagraph">' + comment + '</p>'
  }

  function makePost(title, post, id) {
      return '<h2 class="mb-0">' + title + '</h2>'
              + "<p style='font-size: 1.2rem' class='mb-3'>" + post + '</p>'
              + '<h3>Comments</h3>'
              + '<div id="' + id + '" class="comment"></div>'
              + '<form data-id="' + id + '"class="commentary-form" method="post" enctype="multipart/form-data">'
              + '<div class="form-group">'
              + '<label for="commentary" class="sr-only">Commentary</label>'
              + '<input type="text" class="form-control" id="commentary" placeholder="Enter your commentary">'
              + '</div></form><hr class="my-5">'
;
    }
  var postsElement = $("#posts");

  $.ajax({
    method: "GET",
    url: "categories_api.php",
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
    url: "api.php?category=ALL",
  }).done(function(data) {
    $.each(data, function(key, value) {
      var id = value['id'];
      var title = value["title"];
      var post = value["post"];
      var post = makePost(title, post, id);
      postsElement.append(post);
    })

    $.ajax({
      method: 'GET',
      url: "comments_api.php",
    }).done(function(data) {
      $('.comment').each(function() {
        for (var i=0; i<data.length; i++) {
          if (data[i]['id'] === $(this).attr('id')) {
            $(this).append(
              '<div class="row">'
              + '<p class="comment-paragraph col">' + data[i]['comment'] + '</p>'
              + '<div class="col-auto ml-auto delete-button"><img data-commentID="' + data[i]['comment_id'] + '" src="delete_icon" alt="delete icon" style="height:20px"></div>'
              + '</div>'
            );
          }
        }
      })
      $(".delete-button").click(function() {
        var comment_id = $(this).children().attr('data-commentID');
        $.ajax({
          method: 'POST',
          url: 'delete_comment.php',
          context: $(this),
          data: {comment_id: comment_id}
        }).done(function() {
            $(this).parent().remove();
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
        url: "comments_api.php",
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
        url: "api.php?category=" + category,
      }).done(function(data) {
        $.each(data, function(key, value) {
          var postCategory = value["category"];
          var post = makePost(value["title"], value["post"]);
          postsElement.append(post);
        })
      })
    })
  }
})
