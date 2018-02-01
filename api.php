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
$conn->close();


function handlePostRequest($conn) {
  // print_r($_POST);
  $title = mysqli_real_escape_string($_POST['title']);
  $post = mysqli_real_escape_string($_POST['post']);
  $category = mysqli_real_escape_string($_POST['category']);
  $sql = "INSERT INTO posts (title, post, category) VALUES (?, ?, ?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("sss", $title, $post, $category);
  $statement->execute();
}
function handleGetRequest($conn) {
  // debug_to_console(array_key_exists("category", $_GET));
  $category = $_GET["category"];
  // echo $category;

  $sql = "SELECT * FROM posts WHERE category = '$category' ORDER BY id DESC";
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
function debug_to_console($data) {
  $output = $data;
  if (is_array($output))
  $output = implode(',', $output);

  echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}
?>
