<?php 
require_once '../config/db.php';

if ($pdo) {
  echo "Database Connected Successfull!";
} else {
  echo "Database Connection Failed!";
}
?>