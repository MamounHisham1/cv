<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice Interviews with AI — Now Live</title>
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
                                <td style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); text-align: center; vertical-align: middle;">
                                    <span style="font-size: 28px; color: #ffffff; line-height: 56px;">🎤</span>
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
                            Great news — <strong>AI Mock Interviews</strong> are now live on {{ config('app.name') }}!
                        </p>
                    </td>
                </tr>

                <!-- Hero Card -->
                <tr>
                    <td style="padding: 24px 40px;">
                        <div style="background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); border-radius: 12px; padding: 28px; text-align: center;">
                            <p style="margin: 0 0 6px 0; font-size: 20px; font-weight: 700; color: #ffffff;">
                                Practice with a real AI interviewer
                            </p>
                            <p style="margin: 0; font-size: 14px; color: rgba(255, 255, 255, 0.85); line-height: 1.5;">
                                Voice-based mock interviews tailored to your CV and job description. Get instant feedback and a scored evaluation.
                            </p>
                        </div>
                    </td>
                </tr>

                <!-- Features -->
                <tr>
                    <td style="padding: 10px 40px;">
                        <p style="margin: 0 0 14px 0; font-size: 13px; color: #71717a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            What's included
                        </p>
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="padding: 8px 0; width: 28px; vertical-align: top;">
                                    <span style="font-size: 16px;">🎯</span>
                                </td>
                                <td style="padding: 8px 0 8px 8px; font-size: 14px; color: #18181b; line-height: 1.5;">
                                    <strong>CV-based questions</strong> — the AI reads your CV and asks targeted technical and behavioral questions
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; width: 28px; vertical-align: top;">
                                    <span style="font-size: 16px;">🗣️</span>
                                </td>
                                <td style="padding: 8px 0 8px 8px; font-size: 14px; color: #18181b; line-height: 1.5;">
                                    <strong>Voice conversation</strong> — speak naturally, just like a real interview, using your microphone
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; width: 28px; vertical-align: top;">
                                    <span style="font-size: 16px;">📊</span>
                                </td>
                                <td style="padding: 8px 0 8px 8px; font-size: 14px; color: #18181b; line-height: 1.5;">
                                    <strong>Scored evaluation</strong> — get a detailed breakdown of your performance with strengths and areas to improve
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; width: 28px; vertical-align: top;">
                                    <span style="font-size: 16px;">✨</span>
                                </td>
                                <td style="padding: 8px 0 8px 8px; font-size: 14px; color: #18181b; line-height: 1.5;">
                                    <strong>Choose your voice</strong> — pick from multiple AI voices (American, British, male, female)
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Free Trial Highlight -->
                <tr>
                    <td style="padding: 20px 40px;">
                        <div style="background-color: #faf5ff; border: 1px solid #ede9fe; border-radius: 10px; padding: 20px;">
                            <p style="margin: 0 0 6px 0; font-size: 14px; font-weight: 600; color: #7c3aed;">
                                🎁 Try it free — on us
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #3f3f46; line-height: 1.5;">
                                Your first interview is completely free — a 3-minute practice session so you can see how it works before committing to a plan.
                            </p>
                        </div>
                    </td>
                </tr>

                <!-- CTA -->
                <tr>
                    <td style="padding: 20px 40px;" align="center">
                        <a href="{{ $ctaUrl }}" style="display: inline-block; padding: 14px 36px; background-color: #18181b; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 15px; font-weight: 600;">
                            Start Your Free Interview
                        </a>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 4px 40px 10px 40px; text-align: center;">
                        <p style="margin: 0; font-size: 12px; color: #a1a1aa;">
                            or paste this link: {{ $ctaUrl }}
                        </p>
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
                            You're receiving this because you have an account on {{ config('app.name') }}.
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
