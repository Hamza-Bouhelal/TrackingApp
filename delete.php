<?php
require_once "connect.php";
session_start();
if (!isset($_SESSION["user_id"])) {
    echo "<script>alert('Some error occured, seems like you are not logged in. Please login and try again.');</script>";
    header("Location: http://localhost/trackingApp/index.php");
}
$userid = $_SESSION['user_id'];
if (isset($_GET['key']) && !empty($_GET['key'])) {
    $tid = mysqli_real_escape_string($conn, $_GET['key']);
    $stmt = $conn->prepare("SELECT * FROM tracks WHERE tid = ? AND uid = ?");
    $stmt->bind_param("ss", $tid, $_SESSION['user_id']);
    $stmt->execute();
    $select = $stmt->get_result();
    if (mysqli_num_rows($select)) {
        $stmt = $conn->prepare("DELETE FROM tracks WHERE tid = ? AND uid = ?");
        $stmt->bind_param("ss", $tid, $userid);
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM entries WHERE tid = ?");
        $stmt->bind_param("s", $tid);
        $stmt->execute();
        mysqli_close($conn);
        header("Location: http://localhost/trackingApp/home.php");
    } else {
        echo "<script>alert('Some error occured, seems like this track is not linked to your account');</script>";
        header("Location: http://localhost/trackingApp/home.php");
    }
} else {
    echo "<script>alert('The id of the track to be deleted wasn't provided');</script>";
    header("Location: http://localhost/trackingApp/home.php");
}
?>