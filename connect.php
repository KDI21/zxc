<?php
$mysqli = new mysqli("127.0.0.1", "skorov", "777", "Project");
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}
