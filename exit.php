<?php
session_start();
unset($_SESSION['login']);
unset($_SESSION['id']);
unset($_SESSION['rights']);
header("Location: /");
