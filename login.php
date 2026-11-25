<?php
session_start();

include "db_connect.php";

$email = $_POST['email'];
$id = $_POST['id'];
$password = $_POST['password'];

$stmt = $conn->prepare(
"SELECT * FROM candidates
 WHERE email=? AND id=? AND pass_word=?"
);

$stmt->bind_param("sss", $email, $id, $password);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 1)
{
    $user = $result->fetch_assoc();

    $_SESSION['id'] = $user['id'];
    $_SESSION['name'] = $user['first_name'];
    $_SESSION['phone'] = $user['cellphone'];
    $_SESSION['email'] = $user['email'];

    header("Location: books.php");
    exit();
}
else
{
    echo "Wrong Email, ID or Password.
    <a href='loginpage.html'>Try Again</a>";
}
?>