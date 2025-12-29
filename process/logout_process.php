<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke login tanpa alert
header("Location: ../login.php");
exit();
?>
