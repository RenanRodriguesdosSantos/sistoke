<?php

session_start();
$_SESSION["user"] = null;
session_unset();
header("Location: ../index.php");