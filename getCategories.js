$(function() {
  function makeCategory(category_id, category) {
    return '<option value="' + category_id + '">' + category + '</option>';
  }
  var categoriesElement = $("#categoriesSelector");
  $.ajax({
    method: "GET",
    url: "categories_api.php",
  }).done(function(data) {
    $.each(data, function(key, value) {
      var category = value["category"];
      var category_id = value["id"];
      console.log(category_id);
      var categoryOption = makeCategory(category_id, category);
      categoriesElement.append(categoryOption);
    })
  })
})
