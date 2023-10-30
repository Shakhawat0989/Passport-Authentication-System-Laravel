<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password Email</title>
</head>
<body>
    <h1>Forgot Password?</h1>
    <p>You requested to reset your password. Please use the following link to reset your password:</p>

    <a href="{{ url('password/reset', $data) }}">Reset Password</a>
    <br>
    PINCODE:{{$data}}

    <p>If you didn't request this reset, you can ignore this email.</p>

    <p>Regards,<br>Your Application Team</p>
</body>
</html>
