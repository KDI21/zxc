<?php
session_start();
if(!isset($_SESSION['login'])){
  echo "Вы не авторизованы и просмотр этой страницы невозможен";
  die;
}
include('connect.php');
if(isset($_GET['tag_id']) and isset($_GET['ticket_id'])){
  $tikid = $_GET['ticket_id'];
  $tagid = $_GET['tag_id'];
  echo $tagid.$tikid;
  $tags = "DELETE FROM `Tickets_Tags` WHERE Tickets_Tags.ticket_id=$tikid and Tickets_Tags.tag_id=$tagid";
  $del_tag = $mysqli->query($tags);
  header("Location: /ticket.php?ticket_id=$tikid");
  die;
}
if (isset($_GET['project_id'])){
$id=$_GET['project_id'];
$res = $mysqli->query("SELECT project_id FROM Projects where Projects.project_id = $id");
$re = mysqli_fetch_assoc($res);
$i = $re['project_id'];
echo $i;
  $search = $mysqli->query("SELECT ticket_id FROM Tickets where Tickets.project_id = $i");
  while($res_search = $search->fetch_assoc()) {
    $tikid = $res_search['ticket_id'];
    $tags = "DELETE FROM `Tickets_Tags` WHERE Tickets_Tags.ticket_id=$tikid";
    $del_tag = $mysqli->query($tags);
    $comment = "DELETE FROM `Comments` WHERE Comments.ticket_id=$tikid";
    $del_com = $mysqli->query($comment);
    $query = "DELETE FROM `Tickets` WHERE Tickets.ticket_id=$tikid";
    $result = $mysqli->query($query);
  }
  $query = "DELETE FROM `Projects` WHERE Projects.project_id=$i";
  $result = $mysqli->query($query);
  if($result == TRUE){
    header("Location: /my_projects.php");
  }
}
if (isset($_GET['ticket_id'])){
  $id=$_GET['ticket_id'];
  $res = $mysqli->query("SELECT ticket_id, project_id FROM Tickets where Tickets.ticket_id = $id");
  $re = mysqli_fetch_assoc($res);
  $i = $re['ticket_id'];
  $id = $re['project_id'];
  echo $i;
    $tags = "DELETE FROM `Tickets_Tags` WHERE Tickets_Tags.ticket_id=$i";
    $del_tag = $mysqli->query($tags);
    $comment = "DELETE FROM `Comments` WHERE Comments.ticket_id=$i";
    $del_com = $mysqli->query($comment);
    $query = "DELETE FROM `Tickets` WHERE Tickets.ticket_id=$i";
    $result = $mysqli->query($query);
    if($result == TRUE){
      header("Location: /projects.php?project_id=$id");
  }
  }
if (isset($_GET['comment_id'])){
  $id=$_GET['comment_id'];
  $res = $mysqli->query("SELECT comment_id, ticket_id FROM Comments WHERE Comments.comment_id=$id");
  $re = mysqli_fetch_assoc($res);
  $tik = $re['ticket_id'];
  $i = $re['comment_id'];
  if ($i == TRUE){
    $query = "DELETE FROM `Comments` WHERE Comments.comment_id=$i";
    $result = $mysqli->query($query);
    if($result == TRUE){
      header("Location: /ticket.php?ticket_id=$tik");
    }
    else{
      echo "Error!!!!!";
    }
  }
}
?>
