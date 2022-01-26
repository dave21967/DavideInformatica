<?php
  function connect() {
    $con = new mysqli("localhost","davide","davide","davideinformatica");
    if($con != NULL) {
      return $con;
    }
    else {
      return NULL;
    }
  }
?>