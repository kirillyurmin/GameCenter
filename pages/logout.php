<?php
session_start();
session_destroy();
header("Location: /GameCenter/index.php");
exit;
?>