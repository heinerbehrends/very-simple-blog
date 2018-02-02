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
  $sql = "INSERT INTO posts (title, post, category_id) VALUES (?, ?, ?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("sss", $title, $post, $category);
  $statement->execute();

  $sqlGetMaxID = "SELECT * FROM posts ORDER BY id DESC LIMIT 1";
  $last_row = mysqli_fetch_assoc($conn->query($sqlGetMaxID));
  $maxID = $last_row["id"];
  foreach ($_POST['category'] as $category) {
    $sqlArticlesCategories = "INSERT INTO articles_categories (article_id, category_id) VALUES ('$maxID', '$category')";
    $conn->query($sqlArticlesCategories);
  }
}
function handleGetRequest($conn) {
  // debug_to_console(array_key_exists("category", $_GET));
  $category = $_GET["category"];
  // echo $category;
  if ($category === "ALL") {
    $sql = "SELECT * FROM posts";
  }
  else {
    $sql = "SELECT * FROM posts WHERE category_id = '$category' ORDER BY id DESC";
  }
  $result = $conn->query($sql);
  $result_array = array();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($result_array, $row);
    }
  }
  // echo a json encoded array to the client
  echo json_encode($result_array);
}
// function debug_to_console($data) {
//   $output = $data;
//   if (is_array($output))
//   $output = implode(',', $output);
//
//   echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
// }
?>
