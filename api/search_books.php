<?php
include '../db_connect.php';
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'All';
$type = $_GET['type'] ?? 'All';
$sql = "SELECT * FROM books WHERE 1=1";
$params=[]; $types="";
if($search!=""){ $sql.=" AND (title LIKE ? OR author LIKE ? OR isbn LIKE ?)"; $like="%$search%"; array_push($params,$like,$like,$like); $types.="sss"; }
if($category!="" && $category!="All"){ $sql.=" AND category=?"; array_push($params,$category); $types.="s"; }
if($type!="" && $type!="All"){ $sql.=" AND book_type=?"; array_push($params,$type); $types.="s"; }
$sql.=" ORDER BY title ASC";
$stmt=$conn->prepare($sql);
if(!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute(); $result=$stmt->get_result();
if($result->num_rows==0){ echo "<p style='grid-column:1/-1;text-align:center;color:#888;'>No books found</p>"; exit; }
while($row=$result->fetch_assoc()){
  $id=(int)$row['id'];
  $titleJs = htmlspecialchars(addslashes($row['title']));
  if($row['book_type']=='Free'){ $typeBadge="<span class='badge free'>FREE</span>"; $costText="<b style='color:green;'>FREE</b>"; }
  else { $typeBadge="<span class='badge rent'>RENT</span>"; $costText="<b>R".number_format($row['cost'],0)."</b>"; }
  if($row['status']=='Available'){ $statusBadge="<span class='badge avail'>● Available</span>"; $btn="<button class='borrow-btn' onclick=\"openBorrow($id,'$titleJs')\">Borrow Now</button>"; }
  else { $statusBadge="<span class='badge borrowed'>● Borrowed</span>"; $btn="<button class='borrow-btn disabled' disabled>Not Available</button>"; }
  echo "<div class='card'><div class='cover'>📖</div><h4>".htmlspecialchars($row['title'])."</h4><p class='author'>".htmlspecialchars($row['author'])."</p><span class='tag'>".htmlspecialchars($row['category'])." • ".htmlspecialchars($row['isbn'])."</span><div class='badges'>$typeBadge $statusBadge</div><p class='cost'>Cost: $costText</p>$btn</div>";
}
?>