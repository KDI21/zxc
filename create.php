<?php
session_start();
if(!isset($_SESSION['login'])){
  echo "Вы не авторизованы и просмотр этой страницы невозможен";
  die;
}
$id = $_SESSION['id'];
include('connect.php');
?>
<head>
  <title>Create project</title>
</head>
<body>
  <div id=exit>
    <a href="exit.php"><input type="button" value="Выход" id=b_exit></a>
  </div>
  <form method="POST" action="create.php">
    <input name="name" type="text" placeholder="Имя проекта" autofocus maxlength="30" autocomplete="off"/><br>
    <input type="submit" value="Создать!"/><br>
  </form>
  <?php
  if (isset($_POST['name'])){
    $name = $_POST['name'];
    $name = $name;
    $query = "INSERT INTO Projects (name, user_id) VALUES ('$name', $id)";
    $res = $mysqli->query($query);
    if ($res == TRUE){
      echo "Проект создан успешно";
    }
    else {
      echo $query;
      echo mysqli_error($mysqli);
      echo "Проект не создан, повторите попытку!";
    }
  }
   ?>
<a href="/my_projects.php">Назад</a>
</body>
</html>
