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
  print_r($_POST['category']);
  insert_blog_post_data($conn);
  break;
  case "GET":
  get_posts_by_category($conn);
  break;
}
$conn->close();


function insert_blog_post_data($conn) {
  $sql = "INSERT INTO posts (title, post) VALUES (?, ?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("ss", $_POST['title'], $_POST['post']);
  $statement->execute();
  $article_id = $conn->insert_id;
  foreach ($_POST['category'] as $category) {
    $sqlArticlesCategories = "INSERT INTO articles_categories (article_id, category_id)
                              VALUES ('$article_id', '$category')";
    $conn->query($sqlArticlesCategories);
  }
  header("Location: ../success_post.html");
  exit();
}

function get_posts_by_category($conn) {
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
  echo json_encode($result_array_posts_by_category);
}
?>
