<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>Document</title>
</head>
<body>
  <div id="posts" class="container">
    <?php
    include 'config.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    }

    function postsToArray($conn) {
      $sql = "SELECT * FROM posts ORDER BY id DESC";
      $result = $conn->query($sql);
      $result_array = array();
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          array_push($result_array, $row);
        }
        return $result_array;
      }
    }

    $posts_array = postsToArray($conn);

    foreach ($posts_array as $post) {
      echo "<h1>" . $post['title'] . "</h1><p>" . $post['post'] . "</p>";
    }




    ?>
  </div>
</body>
</html>
