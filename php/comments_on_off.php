<?php
include 'config.php';
header('Content-Type: application/json');
$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
  change_comments_on_off($conn);
}

function change_comments_on_off($conn) {
  $on_off = $conn->real_escape_string($_POST['on_off']);
  $article_id = $conn->real_escape_string($_POST['post_id']);
  $sql = "UPDATE posts SET comments_on_off = ? WHERE id = ?";
  $statement = $conn->prepare($sql);
  $statement->bind_param('ii', $on_off, $article_id);
  $statement->execute();
  echo $on_off;
}
?>
