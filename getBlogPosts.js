$(function() {

  function makeCategoryListItem(category_id, category) {
    return '<li id="' + category_id + '" class="list-group-item border-0 category-menu">' + category + '</li>';
  }
  var categoriesListElement = $("#selectCategory");

  function makePost(title, post, id) {
      return '<h2 class="mb-0">' + title + "</h2> <p style='font-size: 1.2rem' class='mb-3'>"
              + post + '</p>'
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
      console.log('id is ' + value['id']);
      var id = value['id'];
      var title = value["title"];
      var post = value["post"];
      var post = makePost(title, post, id);
      postsElement.append(post);
    })

    var forms = $(".commentary-form");
    forms.submit(function(event) {
      event.preventDefault();
      var commentData = {};
      commentData['comment'] = $(this).find('input').val();
      commentData['article_id'] = $(this).data('id');
      $.ajax({
        method: 'POST',
        url: "commentary_api.php",
        data: commentData
      }).done(function() {

      })
    })
  })

  function getCategoryPosts() {
    $(".category-menu").click(function() {
      var category = $(this)[0].getAttribute("id");
      postsElement.html("");
      console.log("api.php?category=" + category);
      $.ajax({
        method: "GET",
        url: "api.php?category=" + category,
      }).done(function(data) {
        console.log(data);

        $.each(data, function(key, value) {
          var postCategory = value["category"];
          var post = makePost(value["title"], value["post"]);
          postsElement.append(post);
        })
      })
    })
  }
})
