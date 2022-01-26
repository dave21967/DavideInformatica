<?php
  include("connect.php");
  header("content-type: application/json");

  $data = array("status" => "", "data" => array(), "error" => "");

  $con = connect();
  if($con != NULL) {
    $sql = "SELECT * FROM Accounts";
    $res=$con->query($sql);
    if($res->num_rows > 0) {
      $data["status"] = "success";
      while($row = $res->fetch_assoc()) {
        $data["data"][] = $row;
      }
      http_response_code(200);
      echo json_encode($data);
    }
    else {
      $data["status"] = "failed";
      $data["error"] = "no result";
      http_response_code(204);
      echo json_encode($data);
    }
  }
?>