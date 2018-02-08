<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["comment_id"];
}
// setup variables for db connection
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}
// sql to delete a record
$sql = "DELETE FROM comments WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo $id;
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
// close connection
$conn->close();
?>
