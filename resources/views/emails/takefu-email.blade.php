<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GBDP MPI Online System Email</title>
    <style>
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
        <td style="background-color:#0f766e; padding:40px 40px 32px 40px;" align="left">

            <!-- LOGO WITH CONTRAST -->
            <table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                <tr>
                    <td style="background-color:#ffffff; padding:14px 18px;">
                        <img src="{{ asset('photo/shinetsu_try.png') }}" alt="Company Logo" style="display:block; max-width:170px; height:auto;">
                    </td>
                </tr>
            </table>

            <!-- TITLE -->
            <table cellpadding="0" cellspacing="0" border="0" style="margin-top:8px; border-collapse:collapse;">
                <tr>
                    <td style="font-size:20px; font-weight:600; color:#ffffff; letter-spacing:0.4px;">
                        GBDP Online System Notification
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    <!-- BODY -->
    <tr>
        <td style="padding:40px 35px; color:#334155;">

            @if(!empty($customMessage))
            <!-- DOMINANT CUSTOM MESSAGE -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                <tr>
                    <td style="background-color:#ecfeff; padding:28px; border:1px solid #bae6fd;">
                        <div style="font-size:20px; line-height:24px; font-weight:500; color:#0f172a;">
                            {!! $customMessage !!}
                        </div>
                    </td>
                </tr>
            </table>

            <table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;"><tr><td height="20"></td></tr></table>
            @endif

            <!-- ATTACHMENT INFO -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; margin-bottom:20px;">
                <tr><td height="6"></td></tr>
                <tr>
                    <td style="font-size:12.5px; line-height:16px; color:#334155; padding-left:10px;">
                        Please find the attached PDF file(s) for your reference.
                    </td>
                </tr>
                <tr><td height="4"></td></tr>
                <tr>
                    <td style="font-size:11.5px; line-height:14px; color:#64748b; padding-left:10px;">
                        If you were expecting specific documents and cannot locate them, please contact support.
                    </td>
                </tr>
                <tr><td height="8"></td></tr>
            </table>

            <!-- CONTACT SECTION (OUTLOOK SAFE) -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; background-color:#f0fdfa; border:1px solid #ccfbf1;">
                <tr><td height="8"></td></tr>
                <tr>
                    <td style="color:#0f766e; font-size:13px; font-weight:600; padding:10px; letter-spacing:0.3px;">
                        Support
                    </td>
                </tr>
                <tr><td height="4"></td></tr>
                <tr>
                    <td style="color:#475569; font-size:12px; line-height:16px; padding:10px;">
                        ae@smp.com.ph | misd@smp.com.ph<br>
                        (049) 541 3190<br>
                        Mon–Fri • 6:00 AM–6:00 PM (GMT+8)
                    </td>
                </tr>
                <tr><td height="8"></td></tr>
            </table>

        </td>
    </tr>

    @php
        $parts = explode(' ', $massPro);
        $actualFurnaceNo = $parts[0] ?? null;
        $actualMassPro = $parts[1] ?? null;
    @endphp

    <!-- FOOTER -->
    <tr>
        <td style="background-color:#f8fafc; padding:22px 30px; text-align:center; font-size:12px; color:#64748b; border-top:1px solid #e2e8f0;">
            This is an automated message. Please do not reply directly to this email.<br>
            &copy; 2025 Automation Engineering Department. All rights reserved.

            {{ app(\App\Services\TxtExportService::class)->exportData3($actualFurnaceNo, $actualMassPro) }}
            {{ app(\App\Services\TxtExportService::class)->exportData2($actualFurnaceNo, $actualMassPro) }}
            {{ app(\App\Services\TxtExportService::class)->exportData1($actualFurnaceNo, $actualMassPro) }}
        </td>
    </tr>

</table>
<!-- END WRAPPER -->

</td>
</tr>
</table>

</body>
</html>
