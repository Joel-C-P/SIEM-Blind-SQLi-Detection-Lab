<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $server='localhost';
  $username="example";
  $password="example123";
  $database="bbddName";

  // Conexion a la base de datos
  $conn = new mysqli($server, $username, $password, $database);

  $id = mysqli_real_escape_string($conn, $_GET['id']);
  
  //$id = $_GET['id'];

  $data = mysqli_query($conn, "SELECT username FROM users WHERE id = $id");
  
  $response = mysqli_fetch_array($data);

  if (!isset($response['username'])){
    http_response_code(404);
  } 
?>

