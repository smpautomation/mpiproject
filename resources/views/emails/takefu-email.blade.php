<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .header img {
            max-width: 100px;     /* limits width */
            height: 50px;         /* maintain aspect ratio */
            margin-bottom: 15px;
        }
        .header h1 {
            color: #4a90e2;
            margin: 10px 0 0;
        }
        .content {
            padding: 20px 0;
        }
        .attachments {
            margin-top: 20px;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }
        .attachments p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .contact {
            margin-top: 30px;
            padding: 15px;
            background-color: #e8f4ff;
            border-left: 5px solid #4a90e2;
            border-radius: 5px;
        }
        .contact h3 {
            margin-top: 0;
            color: #4a90e2;
        }
        .contact p {
            margin: 5px 0;
            font-size: 14px;
        }
        .footer {
            font-size: 12px;
            text-align: center;
            color: #888;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .footer img {
            max-width: 50px;    /* smaller size for footer logo */
            height: auto;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            {{-- Company Logo --}}
            <img src="{{ asset('photo/smp_logo.png') }}" alt="Company Logo">
            <img src="{{ asset('photo/shinetsu.png') }}" alt="Company Logo">
            <h1>Hello!, </h1>
        </div>

        <div class="content">
            {{-- <p>This is a test email sent from your Laravel application using SMTP.</p> --}}

            @if(!empty($customMessage))
                <div class="custom-message" style="margin-top: 20px;">
                    {!! $customMessage !!}
                </div>
            @endif

            <div class="attachments">
                <p>Please find the attached PDF file(s) for your reference.</p>
                <p>If you were expecting specific documents and cannot find them, please contact support.</p>
            </div>

            <div class="contact">
                <h3>Contact Support</h3>
                <p><strong>Email:</strong> ae@smp.com.ph;misd@smp.com.ph</p>
                <p><strong>Phone:</strong> (049) 541 3190 </p>
                <p><strong>Hours:</strong> Monday – Friday, 6:00 AM – 6:00 PM (GMT+8)</p>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply directly to this email.</p>

            <p>&copy; 2025 Automation Engineering Department. All rights reserved.</p>
            {{-- Department or System Footer Logo --}}

            {{-- <img src="{{ asset('photo/AE.png') }}" alt="Department Logo"> --}}
            {{ app(\App\Services\TxtExportService::class)->exportData3('C-33', $massPro) }}
        </div>

    </div>
</body>
</html>
