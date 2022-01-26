<?php
  include("connect.php");
  header("content-type: application/json");

  $data = array("status" => "", "data" => array());

  $con = connect();
  if($con != NULL) {
    $sql = "SELECT * FROM Post";
    $result = $con->query($sql);
    if($result->num_rows > 0) {
      $data["status"] = "success";
      while($row=$result->fetch_assoc()) {
        $data["data"][] = $row;
      }
      http_response_code(201);
      echo json_encode($data);
    }
    else {
      http_response_code(204);
      $data["status"] = "failed";
      $data["error"] = "No result found for this query";
      echo json_encode($data);
    }
  }
  else {
    http_response_code(501);
    $data["status"] = "failed";
    $data["error"] = "internal server error: no database connection available";
    echo json_encode($data);
  }
?>