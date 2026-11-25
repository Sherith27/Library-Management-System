<?php
session_start();

include "../db_connect.php";

if(!isset($_SESSION['id']))
{
    die("Please login first.");
}

$book_id = $_POST['id'];
$pickup_date = $_POST['date'];
$pickup_time = $_POST['time'];

$user_id = $_SESSION['id'];
$user_name = $_SESSION['name'];
$user_phone = $_SESSION['phone'];

$check = $conn->prepare(
"SELECT status
 FROM books
 WHERE id=?"
);

$check->bind_param("i",$book_id);
$check->execute();

$book = $check->get_result()->fetch_assoc();

if($book['status'] != 'Available')
{
    die("Book is already borrowed.");
}

$status = "Reserved";

$stmt = $conn->prepare(
"INSERT INTO borrowings
(book_id,user_id,user_name,user_phone,pickup_date,pickup_time,status)
VALUES (?,?,?,?,?,?,?)"
);

$stmt->bind_param(
"issssss",
$book_id,
$user_id,
$user_name,
$user_phone,
$pickup_date,
$pickup_time,
$status
);

if($stmt->execute())
{
    $update = $conn->prepare(
    "UPDATE books
     SET status='Borrowed'
     WHERE id=?"
    );

    $update->bind_param("i",$book_id);
    $update->execute();

    echo "Reservation successful!";
}
else
{
    echo "Failed to reserve.";
}
?>