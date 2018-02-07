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
  if($statement->execute() == true) {
      echo $comment;
    }
    else {
      echo "ERROR: Not able to execute $stmt. " . $conn->error;
    }
}

function comments_to_json($conn) {
  $sql = "SELECT p.id, c.comment, c.id AS comment_id from posts p, comments c WHERE p.id = c.article_id ORDER BY c.id";
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
