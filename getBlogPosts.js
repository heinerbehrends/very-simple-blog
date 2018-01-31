$(function() {
  function makePost(title, post) {
    return "<h1>" + title + "</h1> <p>" + post + "</p>";
  }
  var postsElement = $("#posts");
  $.ajax({
    method: "GET",
    url: "api.php",
  }).done(function(data) {
    $.each(data, function(key, value) {
      var title = value["title"];
      var post = value["post"];
      var post = makePost(title, post);
      postsElement.append(post);
    })
  })
  $("li.list-group-item").click(function() {
    var category = $(this).html().toLowerCase();
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
        if (postCategory === category.toLowerCase()) {
          var post = makePost(value["title"], value["post"]);
          postsElement.append(post);
        }
      })
    })
  })

})
