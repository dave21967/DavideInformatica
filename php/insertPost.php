<?php
  include("connect.php");
  header("content-type: application/json");

  $authorized =  false;

  $data = array("status" => "", "data" => "", "error" => "");

  if(isset($_COOKIE["account"])) {
    $con = connect();
    if($con != NULL) {
      $mail = $_COOKIE["account"];
      $res=$con->query("SELECT * FROM Utenti WHERE Email = '$mail' AND Account = 'ADMIN'");
      if($res === TRUE) {
        $authorized=true;
      }
      else {
        http_response_code(403);
        echo json_encode(array("status" => "failed", "error" => "not authorized"));
      }

      $_POST = json_decode(file_get_contents("php://input"), true);

      $titolo = $_POST["titolo"];
      $contenuto = $_POST["contenuto"];
      $data_pub = $_POST["data_pubblicazione"];
      $tags = $_POST["tags"];
      $autore = $_POST["autore"];

      if(isset($titolo) && isset($contenuto) && isset($data_pub) && isset($tags) && isset($autore)) {
        $sql = "INSERT INTO Post VALUES ('0','$titolo','$contenuto','$data_pub','0','$tags','$autore')";
        $res = $con->query($sql);
        if($res === TRUE) {
          http_response_code(200);
          $data["status"] = "success";
          $data["data"] = "new post saved";
          echo json_encode($data);
        }
        else {
          http_response_code(501);
          $data["status"] = "failed";
          $data["error"] = "error while saving post";
          echo json_encode($data);
        }
      }
      else {
        http_response_code(401);
        $data["status"] = "failed";
        $data["error"] = "bad request: missing fields";
        echo json_encode($data);
      }
    }
    else {
      http_response_code(501);
      $data["status"] = "failed";
      $data["error"] = "internal server error: no database connection available";
      echo json_encode($data);
    }
  }
  else {
    http_response_code(403);
    $data["status"] = "failed";
    $data["error"] = "you must be logged in";
    echo json_encode($data);
  }
?>