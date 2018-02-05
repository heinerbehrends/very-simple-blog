$(function() {

  function makeCategoryListItem(category_id, category) {
    return '<li id="' + category_id + '" class="list-group-item border-0 category-menu">' + category + '</li>';
  }
  var categoriesListElement = $("#selectCategory");
  
  function makePost(title, post) {
      return '<h2 class="mb-0">' + title + "</h2> <p style='font-size: 1.2rem' class='mb-5'>" + post + '</p><hr class="mb-5">';
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
      var title = value["title"];
      var post = value["post"];
      var post = makePost(title, post);
      postsElement.append(post);
    })
  })

  function getCategoryPosts() {
    $(".category-menu").click(function() {
      console.log($(this));
      var category = $(this)[0].getAttribute("id");
      postsElement.html("");
      console.log("api.php?category=" + category);
      $.ajax({
        method: "GET",
        url: "api.php?category=" + category,
        success: function(data){
          console.log("Hello!");
        }
      }).done(function(data) {
        console.log("Hello!");
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
