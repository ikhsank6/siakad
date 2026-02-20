@props([
    'subject' => null,
    'companyName' => null,
    'logo' => null,
    'greeting' => null,
    'title' => null,
    'content' => null,
    'actionUrl' => null,
    'actionText' => 'Klik Di Sini',
    'footerMessage' => null,
    'supportEmail' => null,
    'facebook' => null,
    'instagram' => null,
    'twitter' => null,
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            color: #212121;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }
        
        /* Container */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Header */
        .email-header {
            padding: 28px 32px;
            background: linear-gradient(135deg, #03AC0E 0%, #00D817 100%);
            text-align: center;
        }
        
        .logo {
            height: 40px;
            width: auto;
        }
        
        .logo-text {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff !important;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        
        /* Content */
        .email-content {
            padding: 40px 32px;
        }
        
        .greeting {
            font-size: 15px;
            color: #6D7588;
            margin-bottom: 12px;
        }
        
        .greeting strong {
            color: #212121;
            font-weight: 600;
        }
        
        .title {
            font-size: 22px;
            font-weight: 700;
            color: #212121;
            margin-bottom: 16px;
            line-height: 1.3;
        }
        
        .message {
            font-size: 15px;
            color: #6D7588;
            margin-bottom: 28px;
            line-height: 1.7;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 14px 40px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #03AC0E 0%, #00D817 100%);
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 14px 0 rgba(3, 172, 14, 0.39);
        }
        
        /* Info Box */
        .info-box {
            background-color: #F7F8FA;
            border-radius: 12px;
            padding: 24px;
            margin: 28px 0;
            border: 1px solid #E5E7EB;
        }
        
        .info-box-title {
            font-size: 11px;
            font-weight: 700;
            color: #03AC0E;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        
        .info-box-content {
            font-size: 14px;
            color: #212121;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .info-label {
            font-size: 14px;
            color: #6D7588;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #212121;
            text-align: right;
        }
        
        .info-value.highlight {
            color: #03AC0E;
        }
        
        /* Divider */
        .divider {
            height: 1px;
            background-color: #E5E7EB;
            margin: 28px 0;
        }
        
        /* Footer */
        .email-footer {
            background-color: #F7F8FA;
            padding: 28px 32px;
            text-align: center;
            border-top: 1px solid #E5E7EB;
        }
        
        .footer-text {
            font-size: 13px;
            color: #6D7588;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .footer-text a {
            color: #03AC0E;
            text-decoration: none;
            font-weight: 500;
        }
        
        .footer-text a:hover {
            text-decoration: underline;
        }
        
        .social-links {
            margin-bottom: 20px;
        }
        
        .social-link {
            display: inline-block;
            width: 36px;
            height: 36px;
            background-color: #03AC0E;
            border-radius: 50%;
            margin: 0 6px;
            text-decoration: none;
            line-height: 36px;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
        }
        
        .copyright {
            font-size: 12px;
            color: #9CA3AF;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                margin: 0 !important;
                border-radius: 0 !important;
            }
            
            .email-header,
            .email-content,
            .email-footer {
                padding: 24px 20px !important;
            }
            
            .title {
                font-size: 20px !important;
            }
            
            .btn {
                display: block !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" class="email-wrapper" width="600" cellpadding="0" cellspacing="0" align="center" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td class="email-header" style="padding: 28px 32px; background: linear-gradient(135deg, #03AC0E 0%, #00D817 100%); text-align: center;">
                            @if($logo)
                                <img src="{{ url('storage/' . $logo) }}" alt="{{ $companyName ?? config('app.name') }}" class="logo" style="height: 40px; width: auto;">
                            @else
                                <span class="logo-text" style="font-size: 28px; font-weight: 700; color: #ffffff; text-decoration: none; letter-spacing: -0.5px;">{{ $companyName ?? config('app.name') }}</span>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="email-content" style="padding: 40px 32px;">
                            @if($greeting)
                                <p class="greeting" style="font-size: 15px; color: #6D7588; margin: 0 0 12px 0;">
                                    Hai <strong style="color: #212121; font-weight: 600;">{{ $greeting }}</strong>,
                                </p>
                            @endif
                            
                            @if($title)
                                <h1 class="title" style="font-size: 22px; font-weight: 700; color: #212121; margin: 0 0 16px 0; line-height: 1.3;">{{ $title }}</h1>
                            @endif
                            
                            @if($content)
                                <p class="message" style="font-size: 15px; color: #6D7588; margin: 0 0 28px 0; line-height: 1.7;">{{ $content }}</p>
                            @endif
                            
                            {{ $slot }}
                            
                            @if($actionUrl)
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin: 32px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $actionUrl }}" class="btn btn-primary" style="display: inline-block; padding: 14px 40px; font-size: 15px; font-weight: 600; text-decoration: none; border-radius: 8px; text-align: center; background: linear-gradient(135deg, #03AC0E 0%, #00D817 100%); color: #ffffff; box-shadow: 0 4px 14px 0 rgba(3, 172, 14, 0.39);">
                                                {{ $actionText }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                            
                            @if($footerMessage)
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding-top: 28px; border-top: 1px solid #E5E7EB;">
                                            <p style="font-size: 14px; color: #9CA3AF; margin: 0; line-height: 1.6;">{{ $footerMessage }}</p>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="email-footer" style="background-color: #F7F8FA; padding: 28px 32px; text-align: center; border-top: 1px solid #E5E7EB;">
                            <p class="footer-text" style="font-size: 13px; color: #6D7588; margin: 0 0 20px 0; line-height: 1.6;">
                                E-mail ini dibuat secara otomatis, mohon tidak membalas.<br>
                                Jika butuh bantuan, silakan 
                                <a href="mailto:{{ $supportEmail ?? 'support@example.com' }}" style="color: #03AC0E; text-decoration: none; font-weight: 500;">hubungi kami</a>.
                            </p>
                            
                            @if($facebook || $instagram || $twitter)
                                <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin-bottom: 20px;">
                                    <tr>
                                        @if($facebook)
                                            <td style="padding: 0 6px;">
                                                <a href="{{ $facebook }}" class="social-link" style="display: inline-block; width: 36px; height: 36px; background-color: #03AC0E; border-radius: 50%; text-decoration: none; line-height: 36px; color: #ffffff; font-size: 14px; font-weight: 600; text-align: center;">f</a>
                                            </td>
                                        @endif
                                        @if($twitter)
                                            <td style="padding: 0 6px;">
                                                <a href="{{ $twitter }}" class="social-link" style="display: inline-block; width: 36px; height: 36px; background-color: #03AC0E; border-radius: 50%; text-decoration: none; line-height: 36px; color: #ffffff; font-size: 14px; font-weight: 600; text-align: center;">𝕏</a>
                                            </td>
                                        @endif
                                        @if($instagram)
                                            <td style="padding: 0 6px;">
                                                <a href="{{ $instagram }}" class="social-link" style="display: inline-block; width: 36px; height: 36px; background-color: #03AC0E; border-radius: 50%; text-decoration: none; line-height: 36px; color: #ffffff; font-size: 14px; font-weight: 600; text-align: center;">IG</a>
                                            </td>
                                        @endif
                                    </tr>
                                </table>
                            @endif
                            
                            <p class="copyright" style="font-size: 12px; color: #9CA3AF; margin: 0;">
                                &copy; {{ date('Y') }} {{ $companyName ?? config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
