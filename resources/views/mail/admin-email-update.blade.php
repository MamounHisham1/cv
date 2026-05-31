<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f4f5; padding: 40px 20px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" style="max-width: 520px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); overflow: hidden;">
                <!-- Header -->
                <tr>
                    <td style="padding: 28px 32px; border-bottom: 1px solid #f4f4f5;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <p style="margin: 0; font-size: 20px; font-weight: 700; color: #18181b; letter-spacing: -0.3px;">
                                        {{ config('app.name') }}
                                    </p>
                                </td>
                                <td style="text-align: right;">
                                    <p style="margin: 0; font-size: 12px; color: #71717a; font-weight: 400;">
                                        AI-Powered Resume Builder
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Tag -->
                <tr>
                    <td style="padding: 24px 32px 0 32px;">
                        <span style="display: inline-block; background-color: #ecfdf5; color: #065f46; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                            {{ $emailSubject }}
                        </span>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding: 16px 32px 32px 32px;">
                        <div style="font-size: 15px; color: #3f3f46; line-height: 1.7;">
                            {!! $emailBody !!}
                        </div>
                    </td>
                </tr>

                <!-- Divider -->
                <tr>
                    <td style="padding: 0 32px;">
                        <div style="border-top: 1px solid #e4e4e7;"></div>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding: 20px 32px 32px 32px; text-align: center;">
                        <p style="margin: 0 0 4px 0; font-size: 12px; color: #a1a1aa;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
