<?php
session_start();
session_destroy();
header("Location:\PHP\PR Project\Home\index.php");
exit;
