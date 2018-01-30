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
    $title = $_POST['title'];
    $post = $_POST['post'];
    echo $title . "<br>" . $post;
    $sql = "INSERT INTO posts (title, post) VALUES (?, ?)";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ss", $title, $post);
    $statement->execute();

  }
 ?>
