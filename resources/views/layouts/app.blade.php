<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome & ChartJS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --main-color: #22d3ee;
            --secondary-color: #3b82f6;
            --text-color: #0f172a;
            --bg-color: #f4f9ff;
            --card-bg: white;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: var(--bg-color);
        }

        /* SIDEBAR & DASHBOARD STYLES */
        .app {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #d8eaf8);
            padding: 24px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 12px;
            border-radius: 12px;
            font-size: 1rem;
            text-decoration: none;
            color: black;
            font-weight: 500;
            transition: 0.2s;
        }

        nav a.active,
        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .logout {
            color: #ef4444;
            margin-top: auto;
        }

        main {
            flex: 1;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .topbar input {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #dbeafe;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile span {
            font-weight: 500;
            color: var(--text-color);
        }

        .avatar {
            background: var(--main-color);
            color: #fff;
            font-weight: bold;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
            font-size: 1rem;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .positive {
            color: #22c55e;
        }

        .charts {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .chart-card {
            flex: 1 1 48%;
            background: var(--card-bg);
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(172, 202, 231, 0.05);
            min-width: 250px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 20px;
        }

        .box {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 14px;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            border-radius: 10px;
            margin-top: 12px;
        }

        .completed {
            background: #ecfdf5;
        }

        .progress {
            background: #eff6ff;
        }

        .scheduled {
            background: #f8fafc;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .actions button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background: var(--bg-color);
            color: var(--text-color);
            font-weight: 500;
            width: 100%;
            transition: 0.3s;
        }

        .actions button:hover {
            background: var(--secondary-color);
        }

        @media(max-width:768px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .topbar input {
                width: 100%;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .charts {
                flex-direction: column;
            }
        }
    </style>
    @stack('head')
</head>
<<<<<<< HEAD
<body class="bg-blue">
=======

<body>
>>>>>>> 921fcf8 (My updates on eaindra branch)
    @yield('content')
    @stack('scripts')
</body>

</html>
