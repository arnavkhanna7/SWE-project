<?php
$password = "root";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Der Hash für $password ist: " . $hash . "\n";
?>