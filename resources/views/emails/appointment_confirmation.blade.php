<!DOCTYPE html>
<html>
<head>
    <title>Appointment Confirmed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { color: #007bff; margin: 0; }
        .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .details-table th, .details-table td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        .details-table th { background-color: #f8f9fa; color: #555; width: 40%; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #28a745; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Appointment Confirmed</h2>
        </div>

        <p>Hello <strong>{{ $appointment->patient->name }}</strong>,</p>
        <p>Your appointment has been successfully booked and payment confirmed.</p>

        <table class="details-table">
            <tr>
                <th>Doctor</th>
                <td>Dr. {{ $appointment->doctor->name ?? 'Assigned Doctor' }}</td>
            </tr>
            <tr>
                <th>Date & Time</th>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('l, M d, Y - h:i A') }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>
                    @if($appointment->type == 'telehealth')
                        📹 Virtual / Online
                    @else
                        🏥 In-Person Visit
                    @endif
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td style="color: green; font-weight: bold;">{{ ucfirst($appointment->status) }}</td>
            </tr>
            @if($appointment->consultation && $appointment->consultation->clinic)
            <tr>
                <th>Location</th>
                <td>{{ $appointment->consultation->clinic->name }}<br><small>{{ $appointment->consultation->clinic->address }}</small></td>
            </tr>
            @endif
        </table>

        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="btn">View in Dashboard</a>
        </div>

        <div class="footer">
            <p>If you need to reschedule, please contact support or log in to your dashboard.</p>
            <p>Clinical Pro Healthcare System</p>
        </div>
    </div>
</body>
</html>