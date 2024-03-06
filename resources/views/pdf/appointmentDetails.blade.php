<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
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
        .feedback-section {
            border-top: 2px solid #ccc;
            padding-top: 20px;
        }
        .feedback-section h3 {
            text-align: center;
            color: #35784F;
            margin-bottom: 20px;
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
                    <h3>APPOINTMENT DETAILS</h3>
                    <div class="appointment-info">
                        <p><strong>Appointment ID:</strong> {{ $appointment->appointment_id }}</p>
                        <p><strong>Status:</strong> {{ $appointment->appointment_status }}</p>
                        <p><strong>Appointment Date:</strong> {{ $appointment->appointment_date }}</p>
                        <p><strong>Appointment Time:</strong> {{ $appointment->appointment_time }}</p>
                        <p><strong>Date Finished:</strong> {{ $appointment->date_finished }}</p>
                        <p><strong>Created At:</strong> {{ $appointment->created_at }}</p>
                        <p><strong>Updated At:</strong> {{ $appointment->updated_at }}</p>
                    </div>
                    <hr>
                    <div class="contact-info">
                        <h3>CLIENT INFORMATION</h3>
                        <p><strong>Client Name:</strong> {{ $appointment->name }}</p>
                        <p><strong>Address:</strong> {{ $appointment->address }}</p>
                        <p><strong>Contact Number:</strong> {{ $appointment->cellphone_number }}</p>
                        <p><strong>Email Address:</strong> {{ $appointment->email }}</p>
                    </div>
                </div>
                <div class="feedback-section">
                    <h3>FEEDBACK</h3>
                    <p><strong>Rating:</strong> {{ $rating }}</p>
                    <p><strong>Comment:</strong> {{ $comment }}</p>
                </div>
                <div class="message-history">
                    <h3>MESSAGE HISTORY</h3>
                    @foreach($messages as $appointmentMessage)
                        <div class="card message-card {{ !$appointmentMessage->staff_name ? 'text-start' : ($appointmentMessage->staff_name == $staffName ? 'your-message-card text-end' : '') }}">
                            <div class="card-body">
                                <h5 class="card-title {{ !$appointmentMessage->staff_name ? 'text-start' : ($appointmentMessage->staff_name == $staffName ? 'text-end' : 'text-start') }}">
                                    {{ !$appointmentMessage->staff_name ? $appointment->name : $appointmentMessage->staff_name }}
                                </h5>
                                <p class="card-text {{ !$appointmentMessage->staff_name ? 'text-start' : ($appointmentMessage->staff_name == $staffName ? 'text-end' : 'text-start') }}">
                                    {{ $appointmentMessage->message }}
                                </p>
                                <p class="card-text text-muted card-time {{ !$appointmentMessage->staff_name ? 'text-start' : ($appointmentMessage->staff_name == $staffName ? 'text-end' : 'text-start') }}">
                                    {{ $appointmentMessage->created_at }}
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
