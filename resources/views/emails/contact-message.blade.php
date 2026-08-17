<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,sans-serif;color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb;padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;max-width:600px;width:100%;border:1px solid #e5e7eb;">
                    <tr style="background:#0f172a;">
                        <td style="padding:24px 32px;color:#ffffff;font-size:24px;font-weight:700;">
                            New contact message
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 18px;font-size:16px;line-height:1.6;">
                                You have received a new inquiry from the website contact form.
                            </p>

                            <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-weight:700;width:140px;">Name</td>
                                    <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;">{{ $name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-weight:700;">Email</td>
                                    <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;">{{ $email }}</td>
                                </tr>
                                @if($subject)
                                    <tr>
                                        <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-weight:700;">Subject</td>
                                        <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;">{{ $subject }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:10px 0;vertical-align:top;font-weight:700;">Message</td>
                                    <td style="padding:10px 0;line-height:1.7;">{{ $message }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 32px; font-size:12px; color:#6b7280;">
                            Sent from {{ config('app.name') }} contact form.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
