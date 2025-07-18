<?php
require_once '../db.php';
$conn->query("UPDATE notifications SET is_read = 1");
header("Location: notifications.php");
exit;
