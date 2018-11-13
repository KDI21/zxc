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
else{
echo "ADMIN";}
include('connect.php');
include('functions.php');
 ?>
<html>
<head>
  <title>Projects</title>
<style>
td{
  width:70px;
  text-align: center;
}
td:hover{
 color:white;
 background-color: black;
}
#aa{
  text-decoration: none;
  color: black;
}
#aa:hover{
  color: white;
}
</style>
</head>
<body>
  <div id=exit>
    <a href="exit.php"><input type="button" value="Выход" id=b_exit></a>
  </div>
<table border=1>
  <?php
  $i = $_GET["project_id"];
  $res = $mysqli->query("SELECT project_id, name FROM Projects where Projects.project_id = $i");
  $re = mysqli_fetch_assoc($res);
  if ($re['project_id'] == NULL) {
    echo "<center><H1>ERROR 404</H1></center>";
    die;
  }
    ?>
  <caption>Проект №<?php echo $i; ?>
  </caption>
  <tr>
    <th>Project ID</th>
    <th>Project Name</th>
    <th>User ID</th>
  </tr>
  <tr>
    <td><?php echo $re["project_id"]; ?></td>
    <td><?php echo $re["name"]; ?></td>
    <td><?php echo $id; ?></td>
  </tr>
</table><br><br>
<table border=1>
  <?php
  echo $i;
  $result = $mysqli->query("SELECT ticket_id, project_id, name, description, assignee_id, status FROM Tickets WHERE Tickets.project_id=$i");
  ?>
  <caption>Тикеты:</caption>
  <tr>
    <th>ID Создателя</th>
    <th>Имя тикета</th>
    <th>ID Адресата</th>
    <th>Статус</th>
  </tr>
  <?php
  while($resulte = $result->fetch_assoc()) {
    ?>
  <tr id=hei>
    <td><?php echo $id; ?></td>
    <td><a href="/ticket.php?ticket_id=<?php echo $resulte['ticket_id']; ?>" id=aa ><?php echo $resulte["name"]; ?></a></td>
    <td><?php echo $resulte["assignee_id"]; ?></td>
    <td><?php echo $resulte["status"]; ?></td>
  </tr>
<?php }
?>
</table>
<form method="post">
<input type="submit" name="sub" value="Создать новый тикет"/>
<input type="submit" name="subb" value="Закрыть"/>
</form>
<?php
if(isset($_POST['sub'])){
  if(!isset($_POST['subb'])){
  ?>
  <form method="POST" action="projects.php?project_id=<?php echo $i; ?>" enctype="multipart/form-data">
    <input name="nametic" type="text" placeholder="Имя тикета" autofocus maxlength="35" autocomplete="off"/><br>
    <input name="about" type="text" placeholder="Описание тикета" maxlength="250" autocomplete="off"/><br>
    <select name="useridd">
      <?php
        $users = $mysqli->query("SELECT login, user_id FROM Users");
        while ($res_users = $users->fetch_assoc()){
      ?>
      <option value="<?php echo $res_users['user_id']?>"><?php echo $res_users['login'] ?></option>
    <?php }?>
    </select><br>
    <select name="statuss">
      <option value="Inprogress">In progress</option>
      <option value="New">New</option>
      <option value="Testing">Testing</option>
      <option value="Done">Done</option>
    </select><br>
    <input name="upload" type="file" /><br>
    <input type="submit" value="Создать!"/><br>
  </form>
  <?php
}
}
  if (isset($_POST['nametic'])){
    $name = $_POST['nametic'];
    $userid = $_POST['useridd'];
    $about = $_POST['about'];
    $status = $_POST['statuss'];

    $uploadInfo = $_FILES['upload'];
    $up = $uploadInfo['name'];
    if (!empty($up)){
    if((!empty($name)) and (!empty($about))){
    $rand = rand(1, 100000);
    $path_parts = pathinfo("/$up");
    $namee = $path_parts['filename'].$rand.'.'.$path_parts['extension'];
    if (isset($up)){
    $newFilename = $_SERVER['DOCUMENT_ROOT']. "/$namee";
  }
  if (!move_uploaded_file($uploadInfo['tmp_name'], $newFilename)) {
    echo 'Не удалось осуществить сохранение файла';
  }
  $query = "INSERT INTO Tickets (project_id, user_id, name, description, assignee_id, status, file_name) VALUES ('$i', $id, '$name', '$about', $userid, '$status','$namee')";
  $ress = $mysqli->query($query);
  if ($ress == TRUE){
    echo "Тикет создан успешно";
    header("Location /projects.php?project_id=$i");
  }
}
  else {
    echo "Тикет не создан, повторите попытку!";
  }
}
  else{
    if ((!empty($name)) and (!empty($about))){
  $query = "INSERT INTO Tickets (project_id, user_id, name, description, assignee_id, status) VALUES ('$i', $id, '$name', '$about', $userid, '$status')";
  $ress = $mysqli->query($query);
  if ($ress == TRUE){
    echo "Тикет создан успешноjhhf";
    header("Location /projects.php?project_id=$i");
  }
}
    else {
      echo "Тикет не создан, повторите попытку!";
    }
  }
}
$mysqli->close();
?>
<a href="/my_projects.php" id=aa >Назад</a>
</body>
</html>
