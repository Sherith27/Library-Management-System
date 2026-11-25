<?php
include "db_connect.php";

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$id = $_POST['id'];
$email = $_POST['email'];
$cellphone = $_POST['cellphone'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if ($password != $confirm_password) {
    die("Passwords do not match. <a href='createaccount.html'>Go back</a>");

}
$check = $conn->prepare("Select id FROM Candidates WHERE id = ? or email = ?");
$check->bind_param("ss", $id, $email);
$check->execute();
$check->store_result();

if ($check->num_rows >0) {
    die("Id or Email already exists. <a href='loginpage.html'>Login Instead</a>");
}
$stmt = $conn->prepare("INSERT INTO Candidates (id, first_name, last_name, email, cellphone, pass_word) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssssss", $id, $first_name,$last_name,$email,$cellphone,$password);

if ($stmt->execute()) {
    echo "<p style='text-align:center;font-size:20px; margin-top:50px;'>Account Created!</p>";
    echo "<script>setTimeout(function() {window.location.href = 'loginpage.html'},2000);</script>";
    exit;
} else {
    echo "Something went wrong. Please try again";
}
?>