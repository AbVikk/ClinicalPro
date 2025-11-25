<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Clinical Pro</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #007bff;">Welcome, {{ $user->name }}!</h2>
        
        <p>Your account at <strong>Clinical Pro</strong> has been successfully created.</p>
        
        @if($password)
        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 0;"><strong>Your Login Credentials:</strong></p>
            <p style="margin: 5px 0;">Email: {{ $user->email }}</p>
            <p style="margin: 5px 0;">Password: <strong>{{ $password }}</strong></p>
        </div>
        <p style="color: red; font-size: 12px;">Please change your password immediately after logging in.</p>
        @endif

        <p>You can now log in to your dashboard to view appointments and update your profile.</p>
        
        <a href="{{ route('login') }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Login Now</a>
        
        <p style="margin-top: 30px; font-size: 12px; color: #777;">
            This is an automated message. Please do not reply.
        </p>
    </div>
</body>
</html>