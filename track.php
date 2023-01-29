<?php
require_once "connect.php";
session_start();
try {
    if (!isset($_SESSION["user_id"])) {
        echo "Some error occured, seems like you are not logged in. Please login and try again.";
        return;
    }
    if (isset($_GET['url']) && !empty($_GET['url'])) {
        $url = mysqli_real_escape_string($conn, $_GET['url']);
        $select = mysqli_query($conn, "SELECT * FROM tracks WHERE url = '" . strtolower($url) . "' AND uid = '" . $_SESSION['user_id'] . "'");
        if (mysqli_num_rows($select)) {
            echo "This url already has a spoofed link.";
        } else {
            mysqli_query($conn, "INSERT INTO tracks(url, createdAt, uid) VALUES('" . strtolower($url) . "', '" . date("Y-m-d H:i:s") . "', '" . $_SESSION['user_id'] . "')");
            echo "success";
            mysqli_close($conn);
        }
    } else {
        echo "Url entered is invalid or empty .";
    }
} catch (Exception $e) {
    echo "Some error occured, please try again.";
}
?>