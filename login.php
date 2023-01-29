<?php
require_once "connect.php";
if (isset($_POST['emailLogin']) && !empty($_POST['emailLogin'])) {
    $email = mysqli_real_escape_string($conn, $_POST['emailLogin']);
    $password = mysqli_real_escape_string($conn, $_POST['passwordLogin']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "email_invalid";
    } else {
        $email = strtolower($_POST['emailLogin']);
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $select = $stmt->get_result();
        if (mysqli_num_rows($select)) {
            $row = mysqli_fetch_assoc($select);
            if (hash("sha256", $password) == $row['password']) {
                session_start();
                $_SESSION['user_id'] = $row['uid'];
                /* get anythin before @ in the email field */
                $_SESSION['userName'] = explode("@", $row['email'])[0];
                echo "success";
            } else {
                echo "password_invalid";
            }
        } else {
            echo "noAccount";
        }
    }
}
?>