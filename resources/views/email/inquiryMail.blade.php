<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PedroAID</title>
</head>

<body>
    <h1>{{ $mailData['title'] }}</h1>
 
    <p>Hi {{ $mailData['name'] }},</p>

    <p>{{ $mailData['message'] }}</p>

    <p><strong>Tracking ID:</strong></p>
    <p>{{ $mailData['tracking_id'] }}</p>

    <p>You can view your request here:</p>
    <p>{{ $mailData['link'] }}</p>

    <p>Sincerely,</p>
    <p><strong>PedroAID</strong></p>
    <p>http://127.0.0.1:8000/</p>
</body>
</html>