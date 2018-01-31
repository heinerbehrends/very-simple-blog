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
    var category = $(this).html();
    postsElement.html("");
    $.ajax({
      method: "GET",
      url: "api.php",
    }).done(function(data) {
      $.each(data, function(key, value) {
        var postCategory = value["category"];
        console.log(postCategory === category.toLowerCase());
        if (postCategory === category.toLowerCase()) {
          var post = makePost(value["title"], value["post"]);
          postsElement.append(post);
        }
      })
    })
  })

})
