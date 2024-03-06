<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .appointment-details {
            padding: 20px;
            border-top: 2px solid #ccc;
        }
        .appointment-details h2 {
            text-align: center;
            color: #35784F;
            margin-bottom: 20px;
        }
        .appointment-details h3 {
            text-align: center;
            color: #35784F;
            margin-bottom: 20px;
        }
        .contact-info {
            margin-bottom: 20px;
        }
        .message-history {
            border-top: 2px solid #ccc;
            padding-top: 20px;
            margin-bottom: 20px;
        }
        .message-history h3 {
            color: #35784F;
            margin-bottom: 20px;
        }
        .message-card {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .message-card .card-title {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .message-card .card-text {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .message-card .card-time {
            color: gray;
        }
        .message-card .text-muted {
            font-size: 14px;
        }
        .your-message-card {
            background-color: #d6e9c6;
        }
        .text-end {
            text-align: end;
        }
        .text-start {
            text-align: start;
        }
        header {
            padding: 10px;
            text-align: center;
        }
        header p {
            margin: 10px 0;
            font-size: 35px;
            color: #35784F;
        }
        .date-generation {
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <div class="date-generation">Generated on: {{ date('Y-m-d H:i:s') }}</div>
        <p><strong>PedroAID</strong></p>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card appointment-details">
                    <h3>INQUIRY DETAILS</h3>
                    <div class="appointment-info">
                        <p><strong>Inquiry ID:</strong> {{ $inquiry->inquiry_id }}</p>
                        <p><strong>Client Name:</strong> {{ $inquiry->name }}</p>
                        <p><strong>Email Address:</strong> {{ $inquiry->email }}</p>
                        <p><strong>Inquiry:</strong> {{ $inquiry->inquiry }}</p>
                        <p><strong>Status:</strong> {{ $inquiry->status }}</p>
                        <p><strong>Created At:</strong> {{ $inquiry->created_at }}</p>
                        <p><strong>Updated At:</strong> {{ $inquiry->updated_at }}</p>
                    </div>
                </div>
                <div class="message-history">
                    <h3>MESSAGE HISTORY</h3>
                    @foreach($messages as $inquiryMessage)
                        <div class="card message-card {{ !$inquiryMessage->staff_name ? 'text-start' : ($inquiryMessage->staff_name == $staffName ? 'your-message-card text-end' : '') }}">
                            <div class="card-body">
                                <h5 class="card-title {{ !$inquiryMessage->staff_name ? 'text-start' : ($inquiryMessage->staff_name == $staffName ? 'text-end' : 'text-start') }}">
                                    {{ !$inquiryMessage->staff_name ? $inquiry->name : $inquiryMessage->staff_name }}
                                </h5>
                                <p class="card-text {{ !$inquiryMessage->staff_name ? 'text-start' : ($inquiryMessage->staff_name == $staffName ? 'text-end' : 'text-start') }}">
                                    {{ $inquiryMessage->message }}
                                </p>
                                <p class="card-text text-muted card-time {{ !$inquiryMessage->staff_name ? 'text-start' : ($inquiryMessage->staff_name == $staffName ? 'text-end' : 'text-start') }}">
                                    {{ $inquiryMessage->created_at }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>