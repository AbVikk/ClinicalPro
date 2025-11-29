<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClinicalPro Queue Monitor</title>
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
    
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-y: hidden; /* Prevent scrolling on TV */
        }
        .top-bar {
            background: #007bff;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .hospital-name { font-size: 28px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .clock { font-size: 24px; font-weight: 500; }

        .queue-container {
            display: flex;
            height: calc(100vh - 80px); /* Full height minus header */
            padding: 20px;
            gap: 20px;
        }

        /* Left Side: Serving Now */
        .section-serving {
            flex: 6; /* Takes up 60% width */
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }
        .section-header {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Right Side: Up Next */
        .section-waiting {
            flex: 4; /* Takes up 40% width */
            background: #343a40;
            color: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        .section-waiting .section-header {
            color: #28a745;
            border-bottom: 3px solid #28a745;
        }

        /* Cards */
        .serving-card {
            background: #e3f2fd;
            border-left: 10px solid #007bff;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            animation: fadeIn 0.5s ease-in;
        }
        .patient-name { font-size: 36px; font-weight: 800; color: #333; margin: 0; }
        .doctor-name { font-size: 22px; color: #666; margin-top: 5px; }
        .room-number { float: right; font-size: 40px; font-weight: bold; color: #007bff; }

        .waiting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #4b545c;
            font-size: 20px;
        }
        .waiting-row:last-child { border-bottom: none; }
        .waiting-row strong { color: #fff; font-size: 24px; }
        .waiting-row span { color: #adb5bd; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .empty-state { text-align: center; color: #999; font-style: italic; margin-top: 50px; font-size: 24px; }
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="hospital-name">🏥 Clinical Pro - Queue Monitor</div>
        <div class="clock" id="clock">00:00:00</div>
    </div>

    <div class="queue-container" id="queue-content">
        @include('monitor.content')
    </div>

    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
    <script>
        // 1. Digital Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString();
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 2. Auto-Refresh Queue Data (Every 10 seconds)
        setInterval(function() {
            fetch("{{ route('monitor.content') }}")
                .then(response => response.text())
                .then(html => {
                    document.getElementById('queue-content').innerHTML = html;
                });
        }, 10000);
    </script>
</body>
</html>