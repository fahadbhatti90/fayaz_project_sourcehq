<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
<h1>{{ $details['title'] }}</h1>
<p>Click <a href="{{ $details['body'] }}" target="_blank">Here</a> Or open below link to reset password</p>
<a href="{{ $details['body'] }}" target="_blank">{{ $details['body'] }}</a>

<p>Thank you</p>
</body>
</html>
