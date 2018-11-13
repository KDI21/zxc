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
  <h1>Регистрация</h1>
</div>
<div id=form>
<form action="reg.php" method="post">
  <input type="text" name="login" autocomplete="off" required autofocus placeholder="Логин" maxlength="35"/>
  <input type="password" name="password" autocomplete="off" required autofocus placeholder="Пароль" maxlength="40"/>
  <input type="password" name="password2" autocomplete="off" required autofocus placeholder="Повтор пароля" maxlength="40"/>
  <input type="submit" value="Регистрация" />
</form>
</div>
<div>
  <a href="/">Назад</a>
</div>
</div>
</body>
</html>
<?php
include("connect.php");
if(isset($_POST['login'])){
$login = $_POST['login'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = stripslashes($password);
$password = htmlspecialchars($password);
$password2 = stripslashes($password2);
$password2 = htmlspecialchars($password2);
$login = trim($login);
$password = trim($password);
if ($password !== $password2){
  echo "Пароли не одинаковы";
  die;
}
$query = $mysqli->query("SELECT login FROM Users WHERE Users.login='$login'");
$result = mysqli_fetch_assoc($query);
if ($result['login'] !== NULL){
  echo "Такой логин уже существует";
}
else{
$password_hash = password_hash($password,PASSWORD_BCRYPT);
$query2 = "INSERT INTO Users (login, password, rights) VALUES ('$login', '$password_hash', 'usr')";
$result2 = $mysqli->query($query2);
echo "Регистрация успешно завершена";
$res = $mysqli->query("SELECT * FROM Users where Users.login = '$login'");
$re = mysqli_fetch_assoc($res);
$_SESSION['id'] = $re['user_id'];
$_SESSION['login'] = $re['login'];
$_SESSION['rights'] = $re['rights'];
echo $_SESSION['id'];
header("Location: /my_projects.php");
}
}
