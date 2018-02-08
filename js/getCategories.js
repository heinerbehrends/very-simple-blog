$(function() {
  function makeCategory(category_id, category) {
    return '<option value="' + category_id + '">' + category + '</option>';
  }

  $.ajax({
    method: "GET",
    url: "php/categories_api.php",
  }).done(function(data) {
    $.each(data, function(key, value) {
      $("#categoriesSelector").append(makeCategory(value["id"], value["category"]));
    })
  })
})
