<?php
session_start();
include('connect.php');
if (isset($_POST['edit_comm'])){
$text = $_POST['edit_comm'];
$id = $_POST['hidden_comm'];
$tik_id = $_GET['ticket_id'];
  if(!empty($text)){
    $query = "UPDATE `Comments` SET text='$text' WHERE Comments.comment_id=$id";
    $resulte = $mysqli->query($query);
  }
  header("Location: /ticket.php?ticket_id=$tik_id");
}
else{
$i = $_POST['id'];
echo $i;
$res = $mysqli->query("SELECT ticket_id, name, user_id, project_id, description, assignee_id, status, file_name FROM Tickets WHERE Tickets.ticket_id=$i");
$result = mysqli_fetch_assoc($res);
if (isset($i)){
  $name = $_POST['tik_name'];
  $description = $_POST['description'];
  $usr = $_POST['assignee_id'];
  $status = $_POST['status'];
  $uploadInfo = $_FILES['outload'];
  $up = $uploadInfo['name'];
  if (!empty($up)){
    $rand = rand(1, 100000);
    $path_parts = pathinfo("/$up");
    $namee = $path_parts['filename'].$rand.'.'.$path_parts['extension'];
    $newFilename = $_SERVER['DOCUMENT_ROOT']. "/$namee";
    move_uploaded_file($uploadInfo['tmp_name'], $newFilename);
    $query = "UPDATE `Tickets` SET name='$name', description='$description', assignee_id='$usr', file_name='$namee', status='$status' WHERE Tickets.ticket_id='$i'";
    $resulte = $mysqli->query($query);
  }
if (empty($up)){
  $query = "UPDATE `Tickets` SET name='$name', description='$description', assignee_id='$usr' WHERE Tickets.ticket_id='$i'";
  $resulte = $mysqli->query($query);
}
}
if($result['assignee_id'] == $_SESSION['id']){
  $query = "UPDATE `Tickets` SET status='$status' WHERE Tickets.ticket_id='$i'";
  $resulte = $mysqli->query($query);
}
header("Location: /ticket.php?ticket_id=$i");
}
 ?>
