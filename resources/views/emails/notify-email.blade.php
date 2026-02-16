<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MPI SYSTEM REPORTS TO BE EMAILED</title>
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
<body style="margin:0; padding:0; background-color:#e6f4f7; font-family:Segoe UI, Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#e6f4f7;">
<tr>
<td align="center">

<!-- MAIN WRAPPER -->
<table width="620" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-collapse:collapse;">

    <!-- HEADER -->
    <tr>
        <td style="background-color:#0f766e; padding:30px 40px;" align="left">

            <!-- LOGO WITH CONTRAST -->
            <table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; margin-bottom:15px;">
                <tr>
                    <td style="background-color:#ffffff; padding:12px 16px;">
                        <img src="{{ asset('photo/smp_logo.png') }}" alt="Company Logo" style="display:block; max-width:150px; height:auto;">
                    </td>
                </tr>
            </table>

            <!-- HEADER TITLE -->
            <table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                <tr>
                    <td style="font-size:20px; font-weight:600; color:#ffffff; letter-spacing:0.4px;">
                        MPI Reports Email Notification
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- BODY -->
    <tr>
        <td style="padding:30px 35px; color:#334155;">

            <!-- SHORT DESCRIPTION -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; margin-bottom:20px;">
                <tr>
                    <td style="font-size:14px; line-height:20px;">
                        You have MPI reports that are pending to be sent via email.
                    </td>
                </tr>
            </table>

            <!-- DOMINANT CUSTOM MESSAGE -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; margin-bottom:20px;">
                <tr>
                    <td style="background-color:#ecfeff; padding:25px; border:1px solid #bae6fd;">
                        <div style="font-size:18px; line-height:24px; font-weight:500; color:#0f172a;">
                            <p>Listed below are the serial numbers that need to be sent:</p>
                            @foreach($serial as $serialNumber)
                                <p style="font-weight:bold; margin:4px 0;">{{ $serialNumber }}</p>
                            @endforeach
                        </div>
                    </td>
                </tr>
            </table>

            <!-- CONTACT SECTION -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; background-color:#f0fdfa; border:1px solid #ccfbf1; margin-top:10px;">
                <tr><td height="6"></td></tr>
                <tr>
                    <td style="color:#0f766e; font-size:13px; font-weight:600; padding:8px; letter-spacing:0.3px;">
                        Support
                    </td>
                </tr>
                <tr><td height="4"></td></tr>
                <tr>
                    <td style="color:#475569; font-size:12px; line-height:16px; padding:8px;">
                        ae@smp.com.ph<br>
                        Phone: Local 619/206<br>
                        Mon–Sun • 6:00 AM–6:00 PM (GMT+8)
                    </td>
                </tr>
                <tr><td height="6"></td></tr>
            </table>

        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td style="background-color:#f8fafc; padding:20px 30px; text-align:center; font-size:12px; color:#64748b; border-top:1px solid #e2e8f0;">
            This is an automated message. Please do not reply directly to this email.<br>
            &copy; 2025 Automation Engineering Department. All rights reserved.
        </td>
    </tr>

</table>
<!-- END WRAPPER -->

</td>
</tr>
</table>
</body>
</html>
