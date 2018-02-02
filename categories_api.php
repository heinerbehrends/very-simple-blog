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
  header("Location: success_category.html");
  exit();
  break;
  case "GET":
  handleGetRequest($conn);
  break;
}

function handlePOSTRequest($conn) {
  $category = mysqli_real_escape_string($conn, $_POST['new_category']);
  $sql = "INSERT INTO categories (category) VALUES (?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("s", $category);
  $statement->execute();
}

function handleGetRequest($conn) {
  $sql = "SELECT * FROM categories";
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

$conn->close();

 ?>
