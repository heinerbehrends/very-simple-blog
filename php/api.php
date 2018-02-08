<?php
include 'config.php';
header('Content-Type: application/json');
$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}

switch ($_SERVER["REQUEST_METHOD"]) {
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
  $title = $conn->real_escape_string($_POST['title']);
  $post = $conn->real_escape_string($_POST['post']);
  $sql = "INSERT INTO posts (title, post) VALUES (?, ?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("ss", $title, $post);
  $statement->execute();

  $postID = $mysqli->insert_id;
  foreach ($_POST['category'] as $category) {
    $sqlArticlesCategories = "INSERT INTO articles_categories (article_id, category_id) VALUES ('$postID', '$category')";
    $conn->query($sqlArticlesCategories);
  }
  header("Location: success_post.html");
  exit();
}
function handleGetRequest($conn) {
  // debug_to_console(array_key_exists("category", $_GET));
  $category = $_GET["category"];
  if ($category === "ALL") {
    $sql_get_posts_by_category = "SELECT * FROM posts ORDER BY id DESC";
  }
  else {
    $sql_get_posts_by_category = "SELECT posts.title, posts.post, posts.id, posts.comments_on_off
                                  FROM posts
                                  INNER JOIN articles_categories a2c
                                  ON posts.id = a2c.article_id
                                  WHERE a2c.category_id = '$category'
                                  ORDER BY posts.id DESC";
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
