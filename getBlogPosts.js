$(function() {
  function makePost(title, post) {
    return "<h1>" + title + "</h1> <p>" + post + "</p>";
  }
  $.ajax({
    method: "GET",
    url: "api.php",
  }).done(function(data) {
    $.each(data, function(key, value) {
      var title = value["title"];
      var post = value["post"];
      console.log(post);
      var post = makePost(title, post);
      $("#posts").append(post);
    })
  })
})
