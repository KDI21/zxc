<?php
include("connect.php");
session_start();
if(isset($_SESSION['login'])){
  header("Location: /my_projects.php");
}
?>
<html>
<head>
  <title>Баг-система</title>
  <style>
  #center{
    position: absolute;
    background-color: #00004d;
    width: 60%;
    height: 60%;
    left:20%;
    top: 20%;
    border-radius: 10px;
  }
  </style>
</head>
<body>
  <div id=center>
<div id=enter>
  <h1>Вход</h1>
</div>
<div id=form>
<form action="" method="post">
  <input name="login" type="text" autocomplete="off" required autofocus placeholder="Логин" maxlength="35"/>
  <input name="password" type="password" autocomplete="off" required autofocus placeholder="Пароль" maxlength="40"/>
  <input type="submit" value="Войти" />
</form>
</div>
<div>
  <a href="/reg.php">Регистрация</a>
</div>
</div>
</body>
</html>
<?php
if (isset($_POST['login'])){
  $login = $_POST['login'];
  $password = $_POST['password'];
  $password = $_POST['password'];
  $login = stripslashes($login);
  $login = htmlspecialchars($login);
  $password = stripslashes($password);
  $password = htmlspecialchars($password);
  $login = trim($login);
  $password = trim($password);
  $query = $mysqli->query("SELECT * FROM Users WHERE Users.login='$login'");
  $result = mysqli_fetch_assoc($query);
  if (!empty($result['login'])){
    // if($result['password'] == $password){
      if(password_verify($password,$result['password'])){
      $_SESSION['login'] = $result['login'];
      $_SESSION['id'] = $result['user_id'];
      $_SESSION['rights'] = $result['rights'];
      echo $_SESSION['login'];
      header("Location: /my_projects.php");
    }
  }
}
 ?>
