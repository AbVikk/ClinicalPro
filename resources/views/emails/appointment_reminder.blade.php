<!DOCTYPE html>
<html>
<head>
    <title>Appointment Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { padding: 20px; border: 1px solid #eee; max-width: 600px; margin: 0 auto; }
        .btn { background: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: #007bff;">Appointment Reminder</h2>
        
        <p>Hello <strong>{{ $appointment->patient->name }}</strong>,</p>
        
        <p>This is a friendly reminder about your appointment tomorrow.</p>
        
        <div style="background: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 4px solid #007bff;">
            <p><strong>Doctor:</strong> Dr. {{ $appointment->doctor->name ?? 'Assigned Doctor' }}</p>
            <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('l, M d, Y') }}</p>
            @if($appointment->consultation && $appointment->consultation->clinic)
                <p><strong>Location:</strong> {{ $appointment->consultation->clinic->name }}</p>
            @endif
        </div>

        <p>Please arrive 15 minutes early to complete any necessary check-in procedures.</p>
        
        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ route('login') }}" class="btn">View Details in Dashboard</a>
        </p>
    </div>
</body>
</html>