<?php
session_start();

// Admin authentication check
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}
$admin = $_SESSION['admin'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Administration — Student Gate (Full View)</title>

<style>
:root {
  --bg-main:#071126; --bg-secondary:#071024;
  --accent-primary:#3b82f6; --accent-secondary:#06b6d4;
  --text-primary:#e6eef8; --text-dark:#021124;
  --text-muted:#98a6bf; --card-bg:#27242c;
  --border-light:rgba(255,255,255,0.15);
  --radius-md:8px;
}

body {
  font-family: Inter, system-ui, Arial;
  padding:20px;
  min-height:100vh;
  background:linear-gradient(180deg,var(--bg-main),var(--bg-secondary));
  color:var(--text-primary);
}

/* ❌ REMOVED header navbar duplication */

/* 🔹 PAGE TITLE (same clean style as view_logs layout) */
.page-top {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-top: 10px;
}

.page-top h1 {
  margin: 0;
  font-size: 26px;
}

.page-top p {
  margin: 4px 0 0;
  font-size: 13px;
  color: var(--text-muted);
}

/* EXISTING STYLES (UNCHANGED) */
.controls { display:flex; margin:20px 0; gap:10px; }

.hostel-filter button,.action-btn {
  padding:8px 12px; border-radius:8px;
  border:1px solid var(--border-light);
  background:var(--card-bg); color:var(--text-primary);
  cursor:pointer;
}

.hostel-filter button.active {
  background:linear-gradient(90deg,var(--accent-primary),var(--accent-secondary));
  color:var(--text-dark);
}

table { width:100%; border-collapse:collapse; }
th,td { padding:10px; border-bottom:1px solid var(--border-light); }

th { color:var(--text-muted); }

.comment-btn {
  padding:6px 10px; border-radius:6px;
  border:none; cursor:pointer;
  background:var(--accent-secondary); color:#021124;
}

.summary {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
  gap:15px;
  margin-top:25px;
}

.stat {
  background:var(--card-bg);
  padding:15px;
  border-radius:10px;
  text-align:center;
}

footer {
  margin-top:30px;
  text-align:center;
  color:var(--text-muted);
}

/* MODAL */
.modal {
  display:none; position:fixed; inset:0;
  background:rgba(0,0,0,0.6);
  align-items:center; justify-content:center;
}

.modal-box {
  background:var(--card-bg);
  padding:20px;
  width:400px;
  border-radius:10px;
}

.modal-box textarea,
.modal-box input {
  width:100%;
  margin-bottom:10px;
  padding:8px;
  border-radius:6px;
  border:1px solid var(--border-light);
  background:#211f1f;
  color:var(--text-primary);
}

.modal-actions {
  display:flex;
  justify-content:flex-end;
  gap:10px;
}
</style>
</head>

<body>

<!-- ✅ SAME NAVBAR AS view_logs -->
<?php include '../navbar.php'; ?>

<!-- ✅ PAGE HEADER (clean, no duplicate navbar buttons) -->
<div class="page-top">
  <div>
    <h1>Administration — Main Gate</h1>
    <p>PDPM IIITDMJ student movement logs</p>
  </div>
</div>

<div class="controls">
  <div class="hostel-filter">
    <button data-hostel="All" class="active">All</button>
    <button data-hostel="Hostel 1">Hostel 1</button>
    <button data-hostel="Hostel 2">Hostel 2</button>
    <button data-hostel="Hostel 3">Hostel 3</button>
    <button data-hostel="Hostel 4">Hostel 4</button>
    <button data-hostel="Hostel 5">Hostel 5</button>
  </div>

  <div style="margin-left:auto">
    <button id="refresh" class="action-btn">Refresh</button>
    <button id="sendMailBtn" class="action-btn">Send Mail</button>
    <button id="exportCsv" class="action-btn">Export CSV</button>
  </div>
</div>

<table>
<thead>
<tr>
  <th>Name</th><th>Roll</th><th>Hostel</th><th>Room</th>
  <th>Phone</th><th>Purpose</th><th>Out Time</th><th>In Time</th><th>Comment</th>
</tr>
</thead>
<tbody id="log-body"></tbody>
</table>

<div class="summary">
  <div class="stat"><div>Total</div><div id="total">0</div></div>
  <div class="stat"><div>IN</div><div id="count-in">0</div></div>
  <div class="stat"><div>OUT</div><div id="count-out">0</div></div>
</div>

<footer>© <span id="year"></span> Main Gate — Admin Panel</footer>

<!-- MODAL -->
<div class="modal" id="commentModal">
  <div class="modal-box">
    <h3>Add Comment</h3>
    <textarea id="commentText"></textarea>
    <input type="file" id="commentFile">
    <div class="modal-actions">
      <button onclick="closeModal()" class="action-btn">Cancel</button>
      <button onclick="saveComment()" class="action-btn">Save</button>
    </div>
  </div>
</div>

<script>
const byId = id => document.getElementById(id);
let currentFilter='All', logsCache=[], activeRow=null;

function formatHuman(t){
  if(!t) return '';
  const d=new Date(t);
  return isNaN(d)?'':`${d.getHours()}:${String(d.getMinutes()).padStart(2,'0')} ${d.getDate()}/${d.getMonth()+1}/${d.getFullYear()}`;
}

async function fetchLogs(){
  const r=await fetch('api/admin_logs.php');
  const raw=await r.json();
  logsCache=raw.map(x=>({
    name:x.name||'', roll:x.roll||'', hostel:x.hostel||'',
    room:x.room||'', phone:x.phone||'', purpose:x.purpose||'',
    outTime:x.outTime||null, inTime:x.inTime||null
  }));
}

function renderTable(){
  const tb=byId('log-body'); tb.innerHTML='';
  let inC=0,outC=0;

  logsCache.filter(r=>currentFilter==='All'||r.hostel===currentFilter)
  .forEach((r,i)=>{
    tb.innerHTML+=`
    <tr>
      <td>${r.name}</td><td>${r.roll}</td><td>${r.hostel}</td>
      <td>${r.room}</td><td>${r.phone}</td><td>${r.purpose}</td>
      <td>${formatHuman(r.outTime)}</td><td>${formatHuman(r.inTime)}</td>
      <td><button class="comment-btn" onclick="openModal(${i})">Comment</button></td>
    </tr>`;
    r.inTime?inC++:outC++;
  });

  byId('total').textContent=logsCache.length;
  byId('count-in').textContent=inC;
  byId('count-out').textContent=outC;
}

function openModal(i){ activeRow=i; byId('commentModal').style.display='flex'; }
function closeModal(){
  byId('commentModal').style.display='none';
  byId('commentText').value='';
  byId('commentFile').value='';
}

async function saveComment() {
  const comment = byId('commentText').value.trim();
  const file = byId('commentFile').files[0];

  if (!comment) { alert('Comment cannot be empty'); return; }

  const row = logsCache[activeRow];
  const fd = new FormData();
  fd.append('roll', row.roll);
  fd.append('comment', comment);
  if (file) fd.append('document', file);

  const res = await fetch('api/save_comment.php', { method:'POST', body:fd });
  const data = await res.json();

  if (!data.success) { alert(data.error||'Failed'); return; }

  alert('Comment saved successfully');
  closeModal();
}

byId('sendMailBtn').onclick = () => {
  window.location.href = 'mail.php';
};

document.addEventListener('DOMContentLoaded',async()=>{
  byId('year').textContent=new Date().getFullYear();

  byId('refresh').onclick=async()=>{
    await fetchLogs();
    renderTable();
  };

  document.querySelectorAll('.hostel-filter button').forEach(b=>{
    b.onclick=()=>{
      document.querySelectorAll('.hostel-filter button').forEach(x=>x.classList.remove('active'));
      b.classList.add('active');
      currentFilter=b.dataset.hostel;
      renderTable();
    };
  });

  await fetchLogs();
  renderTable();
});
</script>

</body>
</html>