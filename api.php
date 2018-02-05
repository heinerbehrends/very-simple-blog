<?php
include 'config.php';
header('Content-Type: application/json');
$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
  case "POST":
  handlePostRequest($conn);
  break;
  case "GET":
  handleGetRequest($conn);
  break;
}
$conn->close();


function handlePostRequest($conn) {
  $categoriesArray = array();
  // print_r($_POST);
  // foreach ($_POST["category"] as $key => $selectedCategory) {
  //   $categoriesArray[$key] = $selectedCategory;
  // }
  // print_r($categoriesArray);
  $title = $conn->real_escape_string($_POST['title']);
  $post = $conn->real_escape_string($_POST['post']);
  $sql = "INSERT INTO posts (title, post) VALUES (?, ?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("ss", $title, $post);
  $statement->execute();

    // $sqlGetMaxID = "SELECT * FROM posts ORDER BY id DESC LIMIT 1";
    // $last_row = mysqli_fetch_assoc($conn->query($sqlGetMaxID));
  $maxID = $conn->insert_id;
  foreach ($_POST['category'] as $category) {
    $sqlArticlesCategories = "INSERT INTO articles_categories (article_id, category_id) VALUES ('$maxID', '$category')";
    $conn->query($sqlArticlesCategories);
  }
  header("Location: success_post.html");
  exit();
}
function handleGetRequest($conn) {
  // debug_to_console(array_key_exists("category", $_GET));
  $category = $_GET["category"];
  // echo $category;
  if ($category === "ALL") {
    $sql_get_posts_by_category = "SELECT * FROM posts ORDER BY id DESC";
  }
  else {
    $sql_get_post_ids = "SELECT * FROM articles_categories WHERE category_id = '$category' ORDER BY id DESC";
    $result = $conn->query($sql_get_post_ids);
    $result_array = array();
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($result_array, $row);
      }
    }
    // get post id by category
    $posts_by_category_array = array();
    foreach ($result_array as $result) {
      array_push($posts_by_category_array, $result["article_id"]);
    }
    // print_r($posts_by_category_array);
    $array_keys = array_keys($posts_by_category_array);
    $last_key = array_pop($array_keys);
    $posts_string = "(";
    foreach($posts_by_category_array as $index => $post_id) {
      if ($index != $last_key) {
        $posts_string = $posts_string . "{$post_id}, ";
      }
      else {
        $posts_string = $posts_string . "{$post_id}";
      }
    }
    $posts_string = $posts_string . ")";
    $sql_get_posts_by_category = "SELECT * from posts WHERE id in $posts_string";
  }
  $sql_result_posts_by_category = $conn->query($sql_get_posts_by_category);
  $result_array_posts_by_category = array();
  if ($sql_result_posts_by_category->num_rows > 0) {
    while($row = $sql_result_posts_by_category->fetch_assoc()) {
      array_push($result_array_posts_by_category, $row);
    }
  }

  // echo a json encoded array to the client
  echo json_encode($result_array_posts_by_category);
}
?>
