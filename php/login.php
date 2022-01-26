<?php
  include("connect.php");

  header("content-type: application/json");

  $_POST = json_decode(file_get_contents("php://input"), true);

  $email = $_POST["email"];
  $passwd = $_POST["password"];

  if(isset($email) && isset($passwd)) {
    $con = connect();
    if($con != NULL) {
      $sql = "SELECT * FROM Utenti WHERE Email = '$email'";
      $result = $con->query($sql);
      if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $ok = password_verify($passwd, $row["Password"]);
          if($ok) {
            setcookie("account", $row["Email"], time()+86400*30, "/");
            http_response_code(201);
            echo json_encode(array("status" => "success", "data" => "user logged in"));
          }
          else {
            http_response_code(401);
            echo json_encode(array("status" => "failed", "data" => "no user found"));
          }
        }
      }
      else {
        http_response_code(401);
        echo json_encode(array("status" => "failed", "data" => "no user found"));
      }
    }
    else {
      http_response_code(501);
      echo json_encode(array("status" => "failed", "error" => "Internal server error: no database connection available"));
    }
  }
  else {
    echo json_encode(array("status" => "failed", "error" => "Bad request: missing fields"));
  }
?>