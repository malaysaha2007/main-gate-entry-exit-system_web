<?php
session_start();
require '../db.php';

if (!isset($_SESSION['admin'])) {
     header("Location: login.php");
     exit;
}

$collection = $db->activity_logs;
$cursor = $collection->find([], ['sort' => ['timestamp' => -1]]);

$logs = [];
foreach ($cursor as $doc) {
     $logs[] = [
          "user_id" => $doc['user_id'] ?? '',
          "role" => $doc['role'] ?? '',
          "hostel" => $doc['hostel'] ?? '',
          "action_type" => $doc['action_type'] ?? '',
          "description" => $doc['description'] ?? '',
          "timestamp" => $doc['timestamp'] ?? ''
     ];
}
?>
<!doctype html>
<html>

<head>
     <meta charset="utf-8">
     <title>Activity Logs</title>

     <style>
          :root {
               --bg-main: #071126;
               --bg-secondary: #071024;
               --accent-primary: #3b82f6;
               --accent-secondary: #06b6d4;
               --text-primary: #e6eef8;
               --text-dark: #021124;
               --text-muted: #98a6bf;
               --card-bg: #27242c;
               --border-light: rgba(255, 255, 255, 0.15);
               --radius-md: 8px;
          }

          body {
               font-family: Inter, system-ui, Arial;
               padding: 20px;
               background: linear-gradient(180deg, var(--bg-main), var(--bg-secondary));
               color: var(--text-primary);
          }

          header {
               display: flex;
               justify-content: space-between;
               align-items: center;
          }

          .logout-btn,
          .home-btn {
               padding: 8px 14px;
               border-radius: var(--radius-md);
               border: none;
               font-weight: bold;
               background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
               color: var(--text-dark);
               text-decoration: none;
          }

          .controls {
               display: flex;
               margin: 20px 0;
               gap: 15px;
               align-items: center;
          }

          button {
               padding: 8px 14px;
               border-radius: 10px;
               border: 1px solid var(--border-light);
               background: var(--card-bg);
               color: var(--text-primary);
               cursor: pointer;
          }

          button.active {
               background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
               color: var(--text-dark);
          }

          table {
               width: 100%;
               border-collapse: collapse;
          }

          th,
          td {
               padding: 10px;
               border-bottom: 1px solid var(--border-light);
               text-align: center;
          }

          th {
               color: var(--text-muted);
          }
     </style>
</head>

<body>

     <header>
          <h2>Administration — Activity Logs</h2>
          <div style="display:flex; gap:10px; align-items:center;">

               <a href="dashboard.php" class="logout-btn">Back</a>

               <form action="logout.php" method="post" style="display:inline;">
                    <button class="logout-btn">Logout</button>
               </form>

          </div>
     </header>

     <div class="controls">

          <!-- ROLE FILTER -->
          <div>
               <button data-role="All" class="active">All Roles</button>
               <button data-role="Director">Director</button>
               <button data-role="Dean Academic">Dean Academic</button>
               <button data-role="Guard">Guard</button>
          </div>

          <!-- HOSTEL FILTER -->
          <div>
               <button data-hostel="Hostel 1">Hostel 1</button>
               <button data-hostel="Hostel 2">Hostel 2</button>
               <button data-hostel="Hostel 3">Hostel 3</button>
               <button data-hostel="Hostel 4">Hostel 4</button>
               <button data-hostel="Hostel 5">Hostel 5</button>
          </div>

          <div style="margin-left:auto;">
               <button onclick="location.reload()">Refresh</button>
          </div>

     </div>

     <table>
          <thead>
               <tr>
                    <th>User ID</th>
                    <th>Role</th>
                    <th>Hostel</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Timestamp</th>
               </tr>
          </thead>
          <tbody id="log-body"></tbody>
     </table>

     <script>
          const logs = <?php echo json_encode($logs); ?>;
          let activeFilter = { type: "role", value: "All" };

          function renderTable() {
               const tbody = document.getElementById("log-body");
               tbody.innerHTML = "";

               logs.forEach(log => {

                    if (activeFilter.type === "role") {
                         if (activeFilter.value !== "All" && log.role !== activeFilter.value) return;
                    }

                    if (activeFilter.type === "hostel") {
                         if (log.hostel !== activeFilter.value) return;
                    }

                    tbody.innerHTML += `
      <tr>
        <td>${log.user_id}</td>
        <td>${log.role}</td>
        <td>${log.hostel}</td>
        <td>${log.action_type}</td>
        <td>${log.description}</td>
        <td>${log.timestamp}</td>
      </tr>
    `;
               });
          }

          /* ROLE BUTTONS */
          document.querySelectorAll('[data-role]').forEach(btn => {
               btn.onclick = () => {

                    document.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    activeFilter = { type: "role", value: btn.dataset.role };
                    renderTable();
               };
          });

          /* HOSTEL BUTTONS */
          document.querySelectorAll('[data-hostel]').forEach(btn => {
               btn.onclick = () => {

                    document.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    activeFilter = { type: "hostel", value: btn.dataset.hostel };
                    renderTable();
               };
          });

          renderTable();
     </script>

</body>

</html>