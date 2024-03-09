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

    <br>

    <p><strong>Tracking ID:</strong> {{ $mailData['tracking_id'] }}</p>

    <p>You can view your request here:</p>
    <p>{{ $mailData['link'] }}</p>

    <br>

    <p>Sincerely,</p>
    <p><strong>PedroAID</strong></p>
    <p>https://pedroaid.com/</p>
</body>
</html>