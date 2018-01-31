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
  case "GET":
  handleGetRequest($conn);
}


function handlePostRequest($conn) {
  print_r($_POST);
  $title = $_POST['title'];
  $post = $_POST['post'];
  $category = $_POST['category'];
  $sql = "INSERT INTO posts (title, post, category) VALUES (?, ?, ?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("sss", $title, $post, $category);
  $statement->execute();
}
function handleGetRequest($conn) {
  $sql = "SELECT * FROM posts ORDER BY id DESC";
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
?>
