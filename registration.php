<?php
include("connect.php");
$login = $_POST['login'];
$password = $_POST['password'];
$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = stripslashes($password);
$password = htmlspecialchars($password);
$login = trim($login);
$password = trim($password);
$query = $mysqli->query("SELECT login FROM Users WHERE Users.login='$login'");
$result = mysqli_fetch_assoc($query);
if ($result['login'] !== NULL){
  echo "Такой логин уже существует";
}
$query2 = "INSERT INTO Users (login, password, rights) VALUES ('$login', '$password', 'usr')";
$result2 = $mysqli->query($query2);
header("Location: /reg.php");
echo "Регистрация успешно завершена";
