<?php
require_once "connect.php";
if (isset($_POST['emailRegister']) && !empty($_POST['emailRegister'])) {
    $email = mysqli_real_escape_string($conn, $_POST['emailRegister']);
    $password = mysqli_real_escape_string($conn, $_POST['passwordRegister']);
    $confirmpassword = mysqli_real_escape_string($conn, $_POST['confirmpasswordRegister']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "email_invalid";
    } else if ($password != $confirmpassword) {
        echo "password_mismatch";
    } else if (strlen($password) < 6) {
        echo "password_short";
    } else if (strlen($password) > 15) {
        echo "password_long";
    } else {
        $select = mysqli_query($conn, "SELECT * FROM users WHERE email = '" . strtolower($_POST['emailRegister']) . "'");
        if (mysqli_num_rows($select)) {
            echo "email_exists";
        } else {
            mysqli_query($conn, "INSERT INTO users(email ,password) VALUES('" . strtolower($email) . "', '" . hash("sha256", $password) . "')");
            echo "success";
            mysqli_close($conn);
        }
    }
}
?>