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
  insert_new_comment($conn);
  exit();
  break;
  case "GET":
  comments_to_json($conn);
  exit();
  break;
}

function insert_new_comment($conn) {
  $comment = $conn->real_escape_string($_POST['comment']);
  $article_id = $conn->real_escape_string($_POST['article_id']);
  $sql = "INSERT INTO comments (comment, article_id) VALUES (?, ?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("si", $comment, $article_id);
  $statement->execute();
}

function comments_to_json($conn) {
  $sql = "SELECT * FROM comments ORDER BY id DESC";
  $result = $conn->query($sql);
  $result_array = array();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($result_array, $row);
    }
  }
  echo json_encode($result_array);
}

$conn->close();

 ?>
