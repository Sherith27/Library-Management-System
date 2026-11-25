<?php

session_start();

if(!isset($_SESSION['id']))
{
    header("Location: loginpage.html");
    exit();
}

include "db_connect.php";
$cats = $conn->query("SELECT DISTINCT category FROM books WHERE category != '' ORDER BY category");
$total = $conn->query("SELECT COUNT(*) as c FROM books")->fetch_assoc()['c'];
$freeCount = $conn->query("SELECT COUNT(*) as c FROM books WHERE book_type='Free'")->fetch_assoc()['c'];
$rentCount = $conn->query("SELECT COUNT(*) as c FROM books WHERE book_type='Rent'")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Books - Library</title>
<style>
    * { box-sizing: border-box; font-family: Arial, sans-serif; }
  body { background: #f8f9fa; margin: 0; padding: 20px; }
  .page { max-width: 1100px; margin: auto; }
  .topnav { display:flex; justify-content:space-between; align-items:center; }
  .topnav a { text-decoration:none; background:#2c3e50; color:white; padding:10px 18px; border-radius:20px; font-size:14px; }
  h1 { font-size: 38px; color: #2c3e50; margin: 10px 0 5px; }
  .subtitle { color: #888; margin: 0 0 20px; }
  .search-container { max-width: 500px; margin: 0 0 20px 0; }
  .search-box { display: flex; align-items: center; gap: 10px; background: white; border-radius: 50px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 6px 18px; border: 1px solid #eee; }
  .search-box input { flex: 1; border: none; outline: none; padding: 12px 5px; font-size: 15px; }
  h3 { color: #2c3e50; margin: 22px 0 10px; font-size: 17px; }
  .categories, .type-filter { display: flex; flex-wrap: wrap; gap: 10px; }
  .cat, .type-btn { padding: 10px 20px; border-radius: 50px; border: 1px solid #ddd; background: white; cursor: pointer; font-size: 14px; }
  .cat.active, .type-btn.active { background: #2c3e50; color: white; border-color: #2c3e50; }
  .books { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 16px; margin-top: 25px; }
  .card { background: white; border-radius: 15px; padding: 15px; border: 1px solid #eee; display: flex; flex-direction: column; }
  .cover { background: #eef2f7; height: 90px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 28px; }
  .card h4 { margin: 12px 0 4px; color: #2c3e50; font-size: 16px; }
  .card p.author { margin: 0; color: #888; font-size: 13px; }
  .tag { display: inline-block; background: #eef2f7; color: #2c3e50; font-size: 12px; padding: 4px 8px; border-radius: 6px; margin-top: 10px; align-self: flex-start; }
  .badges { display: flex; gap: 5px; flex-wrap: wrap; margin-top: 10px; }
  .badge { font-size: 11px; padding: 4px 8px; border-radius: 20px; font-weight: bold; }
  .badge.free { background: #d4edda; color: #155724; }
  .badge.rent { background: #fff3cd; color: #856404; }
  .badge.avail { background: #e8f5e9; color: #2e7d32; }
  .badge.borrowed { background: #fdecea; color: #c62828; }
  .cost { margin: 10px 0 0; font-size: 14px; }
  .borrow-btn { width: 100%; margin-top: 12px; padding: 11px; background: #2c3e50; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
  .borrow-btn.disabled { background: #ccc; cursor: not-allowed; }
  /* Popup */
  .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99; align-items:center; justify-content:center; }
  .modal-box { background:white; width:90%; max-width:400px; padding:25px; border-radius:15px; }
  .modal-box input, .modal-box select { width:100%; padding:11px; margin:8px 0; border:1px solid #ddd; border-radius:8px; font-size:14px; }
  .modal-box button { width:100%; padding:12px; border:none; border-radius:8px; font-weight:bold; cursor:pointer; }
  .btn-confirm { background:#2c3e50; color:white; margin-top:10px; }
  .btn-cancel { background:#eee; margin-top:8px; }
</style>
</head>
<body>
<div class="page">
  <div class="topnav">
  <div>
    <h1>Books</h1>
    <p style="color:#666;">
      Welcome,
      <b><?php echo $_SESSION['name']; ?></b>
    </p>
  </div>

  <a href="my_borrowed.php"> My Borrowed Books</a>
</div>
  <p class="subtitle">Browse our collection • <?php echo $total; ?> books • <?php echo $freeCount; ?> Free • <?php echo $rentCount; ?> Rent</p>

  <div class="search-container">
    <div class="search-box"><input type="text" id="searchInput" placeholder="Search by title, author or ISBN..." onkeyup="liveSearch()"></div>
  </div>

  <h3>Type</h3>
  <div class="type-filter">
    <button class="type-btn active" onclick="filterType('All', this)">All Types</button>
    <button class="type-btn" onclick="filterType('Free', this)">Free</button>
    <button class="type-btn" onclick="filterType('Rent', this)">Rent</button>
  </div>

  <h3>Categories</h3>
  <div class="categories">
    <button class="cat active" onclick="filterCat('All', this)">All</button>
    <?php while($c = $cats->fetch_assoc()): ?>
      <button class="cat" onclick="filterCat('<?php echo htmlspecialchars($c['category']); ?>', this)"><?php echo htmlspecialchars($c['category']); ?></button>
    <?php endwhile; ?>
  </div>

  <div class="books" id="results"><p>Loading books...</p></div>
</div>

<!-- BORROW POPUP -->
<div class="modal" id="borrowModal">
  <div class="modal-box">
    <h3 id="modalTitle" style="margin:0 0 10px;">Borrow Book</h3>
    <p style="color:#888; font-size:13px; margin:0;">Fill in to reserve. Collect at the library.</p>
    
    <label style="font-size:13px;">Collection Date</label>
    <input type="date" id="pickupDate">
    <label style="font-size:13px;">Collection Time</label>
    <select id="pickupTime">
      <option value="09:00">09:00</option>
      <option value="10:00" selected>10:00</option>
      <option value="11:00">11:00</option>
      <option value="12:00">12:00</option>
      <option value="14:00">14:00</option>
      <option value="15:00">15:00</option>
      <option value="16:00">16:00</option>
    </select>
    <button class="btn-confirm" onclick="confirmBorrow()">Confirm Reservation</button>
    <button class="btn-cancel" onclick="closeModal()">Cancel</button>
  </div>
</div>

<script>
let currentCat='All', currentType='All', timer, selectedBookId=0, selectedBookTitle='';
function filterCat(cat, btn){ currentCat=cat; document.querySelectorAll('.cat').forEach(b=>b.classList.remove('active')); btn.classList.add('active'); liveSearch(); }
function filterType(type, btn){ currentType=type; document.querySelectorAll('.type-btn').forEach(b=>b.classList.remove('active')); btn.classList.add('active'); liveSearch(); }
function liveSearch(){
  clearTimeout(timer);
  timer=setTimeout(()=>{
    let keyword=document.getElementById('searchInput').value;
    fetch('api/search_books.php?search='+encodeURIComponent(keyword)+'&category='+encodeURIComponent(currentCat)+'&type='+encodeURIComponent(currentType))
    .then(r=>r.text()).then(d=>{ document.getElementById('results').innerHTML=d; });
  },300);
}
function openBorrow(id, title){
  selectedBookId=id; selectedBookTitle=title;
  document.getElementById('modalTitle').innerText='Borrow: '+title;
  document.getElementById('pickupDate').value=new Date().toISOString().slice(0,10);
  document.getElementById('borrowModal').style.display='flex';
}
function closeModal(){ document.getElementById('borrowModal').style.display='none'; }
function confirmBorrow()
{
    let date =
    document.getElementById('pickupDate').value;

    let time =
    document.getElementById('pickupTime').value;

    if(!date)
    {
        alert("Select collection date");
        return;
    }

    fetch('api/borrow.php', {
        method:'POST',
        headers:{
            'Content-Type':
            'application/x-www-form-urlencoded'
        },
        body:
        'id=' + selectedBookId +
        '&date=' + encodeURIComponent(date) +
        '&time=' + encodeURIComponent(time)
    })
    .then(r=>r.text())
    .then(msg=>{
        alert(msg);
        closeModal();
        liveSearch();
    });
}
liveSearch();
</script>
</body>
</html>