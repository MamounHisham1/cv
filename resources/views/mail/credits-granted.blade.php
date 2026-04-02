<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credits Added</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f4f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 480px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 20px 40px; text-align: center;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #18181b;">
                                {{ config('app.name') }}
                            </h1>
                        </td>
                    </tr>

                    <!-- Icon -->
                    <tr>
                        <td style="padding: 10px 40px 20px 40px; text-align: center;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #059669 0%, #10b981 100%); display: inline-flex; align-items: center; justify-content: center;">
                                <span style="font-size: 28px; color: #ffffff;">&#10003;</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 10px 40px;">
                            <p style="margin: 0; font-size: 16px; color: #3f3f46; line-height: 1.6;">
                                Hi {{ $name }},
                            </p>
                            <p style="margin: 12px 0 0 0; font-size: 16px; color: #3f3f46; line-height: 1.6;">
                                Credits have been added to your account!
                            </p>
                        </td>
                    </tr>

                    <!-- Credits Amount -->
                    <tr>
                        <td style="padding: 30px 40px;">
                            <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-radius: 12px; padding: 30px; text-align: center;">
                                <p style="margin: 0 0 4px 0; font-size: 13px; font-weight: 500; color: rgba(255, 255, 255, 0.8); text-transform: uppercase; letter-spacing: 1px;">
                                    Credits Added
                                </p>
                                <span style="font-size: 40px; font-weight: 700; color: #ffffff;">
                                    +{{ number_format($amount) }}
                                </span>
                            </div>
                        </td>
                    </tr>

                    <!-- Details -->
                    <tr>
                        <td style="padding: 0 40px 10px 40px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e4e4e7;">
                                        <p style="margin: 0; font-size: 13px; color: #71717a;">Reason</p>
                                        <p style="margin: 4px 0 0 0; font-size: 15px; color: #18181b; font-weight: 500;">{{ $reason }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <p style="margin: 0; font-size: 13px; color: #71717a;">New Balance</p>
                                        <p style="margin: 4px 0 0 0; font-size: 15px; color: #18181b; font-weight: 500;">{{ number_format($newBalance) }} credits</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- CTA -->
                    <tr>
                        <td style="padding: 20px 40px;" align="center">
                            <a href="{{ route('credits.history') }}" style="display: inline-block; padding: 12px 32px; background-color: #18181b; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600;">
                                View Credit History
                            </a>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 10px 40px;">
                            <div style="border-top: 1px solid #e4e4e7;"></div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 40px 40px 40px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #a1a1aa;">
                                If you didn't expect this, please contact support.
                            </p>
                            <p style="margin: 12px 0 0 0; font-size: 12px; color: #a1a1aa;">
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
