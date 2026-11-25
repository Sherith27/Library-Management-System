<?php

session_start();

if(!isset($_SESSION['id']))
{
    header("Location: loginpage.html");
    exit();
}

include "db_connect.php";

$userId = $_SESSION['id'];

$stmt = $conn->prepare(
"SELECT b.title,
        b.author,
        b.category,
        b.book_type,
        b.cost,
        br.*
 FROM borrowings br
 JOIN books b
 ON b.id = br.book_id
 WHERE br.user_id = ?
 ORDER BY br.created_at DESC"
);

$stmt->bind_param("s",$userId);
$stmt->execute();

$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head><meta name="viewport" content="width=device-width, initial-scale=1"><title>My Borrowed Books</title>
<style>*{box-sizing:border-box;font-family:Arial,sans-serif}body{background:#f8f9fa;margin:0;padding:20px}.page{max-width:800px;margin:auto}h1{color:#2c3e50}.card{background:white;padding:18px;border-radius:12px;margin:12px 0;border:1px solid #eee}.collect{color:#2e7d32;font-weight:bold}.back{display:inline-block;background:#2c3e50;color:white;padding:10px 18px;border-radius:20px;text-decoration:none;font-size:14px}</style>
</head>
<body>
<div class="page">
<a class="back" href="books.php">← Back to Books</a>
<h1> My Borrowed Books</h1>
<p style="color:#888;">These are your reservations. Collect them at the library.</p>
<?php

if($res->num_rows==0) echo "<p>No reservations yet. Go borrow a book!</p>";
while($r=$res->fetch_assoc()):
  $cost = ($r['book_type']=='Free') ? 'FREE' : 'R'.number_format($r['cost'],0);
?>
<div class="card">
  <b style="font-size:18px;"><?php echo htmlspecialchars($r['title']); ?></b> - <?php echo htmlspecialchars($r['author']); ?><br>
  <span style="color:#888;font-size:13px;"><?php echo htmlspecialchars($r['category']); ?> • <?php echo $cost; ?> • <?php echo $r['book_type']; ?></span><br><br>
  Reserved by: <b><?php echo htmlspecialchars($r['user_name']); ?></b> (<?php echo htmlspecialchars($r['user_phone']); ?>)<br>
  <span class="collect"> Collect at Library on: <?php echo $r['pickup_date']; ?> at <?php echo $r['pickup_time']; ?></span><br>
  Status: <b><?php echo $r['status']; ?></b>
</div>
<?php endwhile; ?>
</div>
</body>
</html>