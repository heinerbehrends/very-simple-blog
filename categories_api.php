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
  insert_new_category($conn);
  header("Location: success_category.html");
  exit();
  break;
  case "GET":
  categories_to_json($conn);
  break;
}

function insert_new_category($conn) {
  $category = mysqli_real_escape_string($conn, $_POST['new_category']);
  $sql = "INSERT INTO categories (category) VALUES (?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("s", $category);
  $statement->execute();
}

function categories_to_json($conn) {
  $sql = "SELECT * FROM categories ORDER BY category";
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
