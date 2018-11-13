<?php
session_start();
if(!isset($_SESSION['login'])){
  echo "Вы не авторизованы и просмотр этой страницы невозможен";
  die;
}
$user = $_SESSION['login'];
$id = $_SESSION['id'];
if($_SESSION['rights'] == 'usr'){
echo "Вы вошли как $user";
}
if($_SESSION['rights'] == 'adm'){
  echo "ADMIN";
}
include('connect.php');
include('functions.php');
$i = $_GET['project_id'];
$res = $mysqli->query("SELECT project_id FROM Projects where Projects.project_id = $i");
$re = mysqli_fetch_assoc($res);
if ($re['project_id']){
$result = $mysqli->query("SELECT name, project_id FROM Projects WHERE Projects.project_id=$i");
$res = mysqli_fetch_assoc($result);
$name = $res['name'];
if(isset($_POST['name'])){
  $namee = $_POST['name'];
if (!empty($_POST['name'])){
  $query = "UPDATE `Projects` SET name='$namee' WHERE Projects.project_id='$i'";
  $resulte = $mysqli->query($query);
  header("Location: /projects.php?project_id=$i");
}
else {
  echo "Поле имя не заполнено!";
}
}
 ?>
<html>
<head>
</head>
<body>
<form method="POST" action="/edit.php?project_id=<?php echo $i; ?>">
  <input name="name" type="text" placeholder="Имя проекта" autofocus maxlength="30" autocomplete="off" value="<?php echo $name;?>"/><br>
  <input name="id" type="hidden" value="<?php echo $i ?>"/>
  <input type="submit" value="Отправить!"/>
  </form>
  <?php
  $mysqli->close();
}
else {
 echo "<H1><CENTER>ERROR 404</center><h1>";
}
  ?>
  <a href="/my_projects.php">Назад</a>
</body>
</html>
