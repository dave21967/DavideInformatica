<?php
  include("connect.php");

  header("content-type: application/json");

  $authorized = true;

  $_POST = json_decode(file_get_contents("php://input"), true);

  $username = $_POST["username"];
  $passwd = $_POST["password"];
  $email = $_POST["email"];
  $account = $_POST["account"];

  if(!isset($username) || !isset($passwd) || !isset($email) || !isset($account)) {
    http_response_code(401);
    echo json_encode(array("status" => "failed", "error" => "Bad request: missing fields"));
    return;
  }

  if($account == "ADMIN" || $account == "COLLABORATORE") {
    if(!isset($_COOKIE["account"])) {
      $mymail = $_COOKIE["account"];
      $sql = "SELECT Username,Password,Email,Account FROM Utenti WHERE Email = '$mymail' AND Account = 'ADMIN'";
      $res = $con->query($sql);
      if($res->num_rows > 0) {
        $authorized = true;
      }
      else {
        $authorized = false;
        http_response_code(403);
        echo json_encode(array("status" => "failed", "error" => "you must be an admin"));
      }
    }
  }

  $encPasswd = password_hash($passwd, PASSWORD_BCRYPT);

  $con = connect();
  if($con != NULL) {
    $sql = "INSERT INTO Utenti VALUES ('0','$username','$email','$encPasswd','$account','true')";
    $result = $con->query($sql);
    if($result === TRUE) {
      echo json_encode(array("status" => "success", "data" => "new user inserted"));
    }
  }
  else {
    http_response_code(501);
    echo json_encode(array("status" => "failed", "error" => "Internal server error: no mysql connection available"));
  }
?>