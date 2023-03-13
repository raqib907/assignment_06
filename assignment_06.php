<?php
session_start();
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$profile_pic = $_FILES['profile-pic'];

if (empty($name) || empty($email) || empty($password) || empty($profile_pic)) {
    die("Error: All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error: Invalid email format.");
}

$upload_dir = "uploads/";
$upload_file = $upload_dir . date("YmdHis") . "_" . basename($profile_pic);
if (!move_uploaded_file($_FILES['profile-pic']['tmp_name'], $upload_file)) {
    die("Error: Failed to upload profile picture.");
}

$user_data = array($name, $email, $upload_file);
$fp = fopen('users.csv', 'a');
fputcsv($fp, $user_data);
fclose($fp);


setcookie('user_name', $name, time() + (86400 * 30), "/");


if (($handle = fopen("users.csv", "r")) !== false) {
    echo "<table>\n";
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        echo "<tr>\n";
        foreach ($data as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>\n";
        }
        echo "</tr>\n";
    }
    fclose($handle);
    echo "</table>\n";
} else {
    echo "Error: Failed to open users.csv";
}


