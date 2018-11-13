<?php
include_once('functions.php');
session_start();
if(!isset($_SESSION['login'])){
  echo "Вы не авторизованы и просмотр этой страницы невозможен";
  die;
}
$user = $_SESSION['login'];
$id = $_SESSION['id'];
if ($_SESSION['rights'] == 'usr'){
  echo "Вы вошли как $user";
}
if ($_SESSION['rights'] == 'adm'){
  echo "ADMIN";
}
 ?>
<html>
<head>
  <title>Projects</title>
<style>
table{
  height: 300px;
  width: 500px;
  background-color: black;
  text-align: center;
}
#a{
  text-decoration: none;
  color:white;
}
#a:hover{
  text-decoration: underline;
}
td:hover{
  background-color: green;
}
#p{
  top: inherit;
  text-align: right;
}
#del{
  text-align: left;
}
</style>
</head>
<center>
<body>
  <div id=exit>
    <a href="exit.php"><input type="button" value="Выход" id=b_exit></a>
  </div>
<table border="1">
<caption>Имена Проектов:</caption>
<?php
include('connect.php');
    $res = $mysqli->query("SELECT project_id, name, user_id FROM Projects");
    while($ro = $res->fetch_assoc()) {
    ?>
  <tr>
    <td>
      <a href="projects.php?project_id=<?php echo $ro["project_id"];?>" id="a"><?php echo $ro["name"]?></a>
    <div id="p">
      <?php if (is_allowed($ro['user_id'])): ?>
        <a href="edit.php?project_id=<?php echo $ro["project_id"];?>" id="a">Редактировать</a>
      <?php endif; ?>
    </div>
    <div id=del>
      <?php if (is_allowed($ro['user_id'])):?>
    <a href="del.php?project_id=<?php echo $ro["project_id"];?>" id="a">Удалить</a>
  <?php endif;?>
    </div>
    </td>
  </tr>
<?php
}
$res->free();
$mysqli->close();
?>
</table>
<a href="create.php">Создать проект</a>
</center>
</body>
</html>
