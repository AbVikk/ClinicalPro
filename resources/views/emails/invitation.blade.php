<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <h2>Hello,</h2>
    <p>You have been invited to join <strong>Clinical Pro</strong> as a <strong>{{ ucfirst(str_replace('_', ' ', $invitation->role)) }}</strong>.</p>
    
    <p>Click the button below to set up your account:</p>
    
    <a href="{{ $url }}" style="display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Accept Invitation</a>
    
    <p style="margin-top: 20px;">Or copy this link: <br> {{ $url }}</p>
</body>
</html>