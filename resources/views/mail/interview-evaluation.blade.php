<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Evaluation Ready</title>
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
                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                            <tr>
                                <td style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #059669 0%, #10b981 100%); text-align: center; vertical-align: middle;">
                                    <span style="font-size: 28px; color: #ffffff; line-height: 56px;">&#10003;</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Greeting -->
                <tr>
                    <td style="padding: 10px 40px;">
                        <p style="margin: 0; font-size: 16px; color: #3f3f46; line-height: 1.6;">
                            Hi {{ $name }},
                        </p>
                        <p style="margin: 12px 0 0 0; font-size: 16px; color: #3f3f46; line-height: 1.6;">
                            Your mock interview evaluation is ready!
                        </p>
                    </td>
                </tr>

                <!-- Score Card -->
                <tr>
                    <td style="padding: 30px 40px;">
                        <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-radius: 12px; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 4px 0; font-size: 13px; font-weight: 500; color: rgba(255, 255, 255, 0.8); text-transform: uppercase; letter-spacing: 1px;">
                                Overall Score
                            </p>
                            <span style="font-size: 40px; font-weight: 700; color: #ffffff;">
                                {{ $score }}/100
                            </span>
                            <p style="margin: 8px 0 0 0; font-size: 16px; color: rgba(255, 255, 255, 0.9); font-weight: 600;">
                                Grade: {{ $grade }}
                            </p>
                        </div>
                    </td>
                </tr>

                <!-- Summary -->
                <tr>
                    <td style="padding: 0 40px 10px 40px;">
                        <p style="margin: 0 0 8px 0; font-size: 13px; color: #71717a;">Summary</p>
                        <p style="margin: 0; font-size: 15px; color: #18181b; line-height: 1.6;">
                            {{ Str::limit($summary, 300) }}
                        </p>
                    </td>
                </tr>

                <!-- Strengths -->
                @if(is_array($strengths) && count($strengths) > 0)
                <tr>
                    <td style="padding: 10px 40px;">
                        <p style="margin: 0 0 8px 0; font-size: 13px; color: #71717a;">Top Strengths</p>
                        <ul style="margin: 0; padding: 0 0 0 20px;">
                            @foreach(array_slice($strengths, 0, 3) as $strength)
                            <li style="font-size: 14px; color: #18181b; line-height: 1.6; margin-bottom: 4px;">{{ $strength }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endif

                <!-- CTA -->
                <tr>
                    <td style="padding: 20px 40px;" align="center">
                        <a href="{{ route('ai.interview') }}" style="display: inline-block; padding: 12px 32px; background-color: #18181b; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600;">
                            View Full Results
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
