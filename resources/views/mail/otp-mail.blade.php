<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Verification Code</title>
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

                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 20px 40px;">
                            <p style="margin: 0; font-size: 16px; color: #3f3f46; line-height: 1.6;">
                                Hello,
                            </p>
                            <p style="margin: 12px 0 0 0; font-size: 16px; color: #3f3f46; line-height: 1.6;">
                                Use the following code to verify your email address:
                            </p>
                        </td>
                    </tr>

                    <!-- OTP Code -->
                    <tr>
                        <td style="padding: 30px 40px;">
                            <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 12px; padding: 30px; text-align: center;">
                                <span style="font-size: 36px; font-weight: 700; letter-spacing: 8px; color: #ffffff; font-family: ui-monospace, SFMono-Regular, 'SF Mono', Menlo, Consolas, monospace;">
                                    {{ $otp }}
                                </span>
                            </div>
                        </td>
                    </tr>

                    <!-- Expiry Notice -->
                    <tr>
                        <td style="padding: 0 40px 20px 40px;">
                            <p style="margin: 0; font-size: 14px; color: #71717a; text-align: center;">
                                This code will expire in <strong>{{ $expiresInMinutes }} minutes</strong>.
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="border-top: 1px solid #e4e4e7;"></div>
                        </td>
                    </tr>

                    <!-- Security Notice -->
                    <tr>
                        <td style="padding: 20px 40px;">
                            <p style="margin: 0; font-size: 13px; color: #a1a1aa; line-height: 1.6;">
                                <strong style="color: #71717a;">Security tip:</strong> Never share this code with anyone. Our team will never ask for your verification code.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 40px 40px 40px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #a1a1aa;">
                                If you didn't request this code, you can safely ignore this email.
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
