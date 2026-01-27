<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MediCore Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<style>
:root {
    --main-color: #22d3ee;
    --secondary-color: #3b82f6;
    --text-color: #0f172a;
    --bg-color: #f4f9ff;
    --card-bg: white;
}

* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI",sans-serif; }

body { background: var(--bg-color); }

.app { display:flex; min-height:100vh; }

/* SIDEBAR - Patients page style */
.sidebar { width:250px; background: linear-gradient(180deg,#d8eaf8); padding:24px; display:flex; flex-direction:column; }
.logo { font-weight:bold; font-size:1.5rem; margin-bottom:2rem; }
nav a { display:flex; align-items:center; gap:12px; padding:14px 12px; border-radius:12px; font-size:1rem; text-decoration:none; color:black; font-weight:500; transition:0.2s; }
nav a.active, nav a:hover { background: rgba(255,255,255,0.2); }
.logout { color:#ef4444; margin-top:auto; }

/* MAIN */
main { flex:1; padding:30px; }

/* HEADER */
.topbar { display:flex; justify-content: space-between; align-items:center; margin-bottom:30px; }
.topbar input { padding:10px; border-radius:8px; border:1px solid #dbeafe; }
.profile { display:flex; align-items:center; gap:10px; }
.profile span { font-weight:500; color: var(--text-color); }
.avatar { background: var(--main-color); color:#fff; font-weight:bold; width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:50%; flex-shrink:0; font-size:1rem; text-transform:uppercase; box-shadow:0 2px 6px rgba(0,0,0,0.2); transition:0.3s; }

/* KPI CARDS */
.cards { display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:20px; margin-bottom:30px; }
.card { background: var(--card-bg); padding:20px; border-radius:14px; box-shadow:0 8px 20px rgba(0,0,0,0.05); }
.card h2 { margin:10px 0; }
.positive { color:#22c55e; }

/* CHARTS */
.charts { display:flex; gap:20px; margin-bottom:30px; flex-wrap:wrap; }
.chart-card { flex:1 1 48%; background: var(--card-bg); padding:15px; border-radius:12px; box-shadow:0 4px 10px rgba(172,202,231,0.05); min-width:250px; }

/* GRID */
.grid { display:grid; grid-template-columns:1.3fr 1fr; gap:20px; }

/* BOXES */
.box { background: var(--card-bg); padding:20px; border-radius:14px; }
.list-item { display:flex; justify-content:space-between; padding:12px; border-radius:10px; margin-top:12px; }
.completed { background:#ecfdf5; }
.progress { background:#eff6ff; }
.scheduled { background:#f8fafc; }

/* QUICK ACTIONS */
.actions { display:flex; flex-direction:column; gap:12px; }
.actions button { display:flex; align-items:center; gap:10px; padding:10px 15px; border:none; border-radius:8px; cursor:pointer; background: var(--bg-color); color: var(--text-color); font-weight:500; width:100%; transition:0.3s; }
.actions button:hover { background: var(--secondary-color); }

@media(max-width:768px){
    .topbar { flex-direction:column; align-items:flex-start; gap:10px; }
    .topbar input { width:100%; }
    .grid { grid-template-columns:1fr; }
    .charts { flex-direction:column; }
}
</style>
</head>
<body>
<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2 class="logo">MediCore</h2>
        <nav>
            <a><i class="fa-solid fa-chart-line"></i> Dashboard</a>
            <a><i class="fa-solid fa-user"></i> Patients</a>
            <a class="active"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
            <a><i class="fa-solid fa-calendar-check"></i> Appointments</a>
            <a><i class="fa-solid fa-stethoscope"></i> Services</a>
            <a><i class="fa-solid fa-credit-card"></i> Payments</a>
            <a class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </nav>
    </aside>

    <!-- MAIN DASHBOARD CONTENT (unchanged) -->
    <main>
        <header class="topbar">
            <div>
<h1 style="font-size: 20px; font-weight: 700; color: #1e3a8a; text-align: center;">
  Welcome to MediCore Patient Record System
</h1>
            </div>
            <input type="text" placeholder="Search patient, doctor...">
            <div class="profile">
                <span>Admin</span>
                <div class="avatar">A</div>
            </div>
        </header>

        <section class="cards">
            <div class="card">
                <h4>Total Patients</h4>
                <h2>1,234</h2>
                <span class="positive"><i class="fa-solid fa-arrow-up"></i> 12% from last month</span>
            </div>
            <div class="card">
                <h4>Active Doctors</h4>
                <h2>45</h2>
                <span class="positive"><i class="fa-solid fa-arrow-up"></i> 3% from last month</span>
            </div>
            <div class="card">
                <h4>Appointments Today</h4>
                <h2>28</h2>
                <span class="positive"><i class="fa-solid fa-arrow-up"></i> 8% from last month</span>
            </div>
            <div class="card">
                <h4>Revenue (Month)</h4>
                <h2>$52,450</h2>
                <span class="positive"><i class="fa-solid fa-arrow-up"></i> 18% from last month</span>
            </div>
        </section>

        <section class="charts">
            <div class="chart-card">
                <h3>Patient Overview</h3>
                <canvas id="patientChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Revenue</h3>
                <canvas id="revenueChart"></canvas>
            </div>
        </section>

        <section class="grid">
            <div class="box">
                <h3>Today's Appointments</h3>
                <div class="list-item completed">
                    <span>John Smith</span>
                    <small>Dr. Sarah Johnson</small>
                    <label>Completed</label>
                </div>
                <div class="list-item progress">
                    <span>Emma Wilson</span>
                    <small>Dr. Michael Brown</small>
                    <label>In Progress</label>
                </div>
                <div class="list-item scheduled">
                    <span>Robert Davis</span>
                    <small>Dr. Sarah Johnson</small>
                    <label>Scheduled</label>
                </div>
            </div>
            <div class="box actions">
                <h3>Quick Actions</h3>
                <button><i class="fa-solid fa-user-plus"></i> Add Patient</button>
                <button><i class="fa-solid fa-calendar-plus"></i> Add Appointment</button>
                <button><i class="fa-solid fa-file-invoice-dollar"></i> Generate Bill</button>
            </div>
        </section>
    </main>
</div>

<script>
const patientCtx = document.getElementById('patientChart').getContext('2d');
const patientChart = new Chart(patientCtx, {
    type: 'bar',
    data: {
        labels: ['4 Jul','5 Jul','6 Jul','7 Jul','8 Jul','9 Jul','10 Jul','11 Jul'],
        datasets: [
            { label: 'Child', data: [100,120,80,105,90,115,100,105], backgroundColor: '#22d3ee' },
            { label: 'Adult', data: [120,110,100,132,115,140,130,132], backgroundColor: '#3b82f6' },
            { label: 'Elderly', data: [30,40,35,38,40,45,40,38], backgroundColor: '#38bdf8' },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'top' } } }
});
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
        datasets: [
            { label: 'Income', data: [800,1000,900,1495,1100,1200,1150], borderColor: '#0f172a', fill: false },
            { label: 'Expense', data: [400,600,500,700,550,650,600], borderColor: '#22d3ee', fill: false }
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'top' } } }
});
</script>
</body>
</html>