$(function() {
  function makeCategory(category) {
    return '<option value="' + category.toLowerCase() + '">' + category + '</option>';
  }
  var categoriesElement = $("#categoriesSelector");
  $.ajax({
    method: "GET",
    url: "categories_api.php",
  }).done(function(data) {
    $.each(data, function(key, value) {
      var category = value["category"];
      var categoryOption = makeCategory(category);
      categoriesElement.append(categoryOption);
    })
  })
})
