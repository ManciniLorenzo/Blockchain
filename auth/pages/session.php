<?php
  session_start();
  if(!isset($_SESSION['start_time'])){
      header('Location: ' . ROOT_URL . 'auth/pages/login.php');
      die;
  }else{
      $now = time();
      $time = $now - $_SESSION['start_time'];
      if($time > 3600){ //1h
        header('Location: ' . ROOT_URL . 'auth/pages/logout.php');
        die;
      }
  }
?>