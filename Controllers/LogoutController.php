<?php
// Controllers/LogoutController.php

session_start();

// 1. Alle Session-Variablen löschen
$_SESSION = [];

// 2. Session zerstören
session_destroy();

// 3. Zur Login-Seite (oder Startseite) weiterleiten
header("Location: ../login/login.php");
exit();