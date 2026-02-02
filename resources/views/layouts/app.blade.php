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
            --accent-color: #0d6efd;
            --text-color: #0f172a;
            --bg-color: #f4f9ff;
            --card-bg: #fff;
        }

        body {
            background: var(--bg-color);
            font-family: "Segoe UI", sans-serif;
            margin: 0;
        }

        /* APP LAYOUT */
        .app {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
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

        /* MAIN */
        main {
            flex: 1;
            padding: 30px;
        }

        /* HEADER */
        .main-header {
            display: flex;
            justify-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .main-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent-color);
        }

        /* CONTROLS */
        .flex-controls {
            display: flex;
            justify-between;
            flex-wrap: wrap;
            margin-bottom: 15px;
            gap: 10px;
        }

        input.search {
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 8px 12px;
            width: 220px;
        }

        button.add-payment,
        button.export-btn {
            background: var(--main-color);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: 0.3s;
        }

        button.add-payment:hover,
        button.export-btn:hover {
            background: var(--secondary-color);
        }

        /* TABLE */
        .table-container {
            overflow-x-auto;
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(132, 104, 12, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        thead tr {
            background: #b4e4fb;
            color: #0f172a;
            cursor: pointer;
        }

        th,
        td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        /* ACTION BUTTONS */
        button.action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: 0.3s;
        }

        button.action-btn:hover {
            background: #084298;
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 50;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 25px;
            width: 400px;
            max-width: 90%;
        }

        .modal-content h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: var(--accent-color);
        }

        .modal-content label {
            display: block;
            margin: 8px 0 4px;
            color: #0f172a;
        }

        .modal-content input {
            width: 100%;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #dbeafe;
            margin-bottom: 10px;
        }

        .modal-content button {
            background: var(--accent-color);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .modal-content button:hover {
            background: #084298;
        }

        .close-btn {
            background: #ef4444;
            margin-left: 10px;
        }

        .close-btn:hover {
            background: #b91c1c;
        }
    </style>
    @stack('head')
</head>

<body class="bg-blue">
    @yield('content')
    @stack('scripts')
</body>

</html>
