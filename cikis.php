<?php
// =============================================
// CIKIS.PHP
// Oturumu sonlandırır
// =============================================

session_start();
session_destroy();
header("Location: login.php");
exit;
?>
