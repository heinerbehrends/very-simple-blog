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
  write_new_rule($conn);
  header("Location: ../success_new_rule.html");
  exit();
  break;
  case "GET":
  get_rules($conn);
  exit();
  break;
}

function write_new_rule($conn) {
  $sql = "INSERT INTO text_expand (abbreviation, snippet) VALUES (?, ?)";
  $statement = $conn->prepare($sql);
  $statement->bind_param("ss", $_POST['abbreviation'], $_POST['snippet']);
  $statement->execute();
}

function get_rules($conn) {
  $sql = "SELECT * FROM text_expand ORDER BY abbreviation";
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
