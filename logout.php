<?php
ob_start();
session_start();
if ($_SESSION['user_id'] != "") {
    session_destroy();
    unset($_SESSION['user_id']);
    unset($_SESSION['userName']);
    header("Location: http://localhost/trackingApp");
} else {
    header("Location: http://localhost/trackingApp");
}
?>