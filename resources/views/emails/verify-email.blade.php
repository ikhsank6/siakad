@php
    $aboutUs = \App\Models\AboutUs::getCached();
    $companyName = $aboutUs?->company_name;
    $companyLogo = $aboutUs?->logo;
    $companyEmail = $aboutUs?->email;
    $companyFacebook = $aboutUs?->facebook;
    $companyInstagram = $aboutUs?->instagram;
    $companyTwitter = $aboutUs?->twitter;
@endphp

<x-emails.layout :subject="$subject ?? 'Verifikasi Email'" :companyName="$companyName" :logo="$companyLogo"
    :greeting="$userName" :title="$title ?? 'Verifikasi Alamat Email Anda'" :content="$emailContent ?? 'Terima kasih telah mendaftar. Silakan klik tombol di bawah untuk memverifikasi alamat email Anda.'" :actionUrl="$verificationUrl"
    actionText="Verifikasi Email" :supportEmail="$companyEmail" :facebook="$companyFacebook"
    :instagram="$companyInstagram" :twitter="$companyTwitter">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background-color: #F7F8FA; border-radius: 12px; border: 1px solid #E5E7EB; margin: 28px 0;">
        <tr>
            <td style="padding: 24px;">
                <p
                    style="font-size: 11px; font-weight: 700; color: #03AC0E; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 16px 0;">
                    Informasi Akun
                </p>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 10px 0; border-bottom: 1px solid #E5E7EB;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-size: 14px; color: #6D7588;">Nama</td>
                                    <td style="font-size: 14px; font-weight: 600; color: #212121; text-align: right;">
                                        {{ $userName }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; border-bottom: 1px solid #E5E7EB;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-size: 14px; color: #6D7588;">Email</td>
                                    <td style="font-size: 14px; font-weight: 600; color: #212121; text-align: right;">
                                        {{ $userEmail }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @isset($password)
                        <tr>
                            <td style="padding: 10px 0; border-bottom: 1px solid #E5E7EB;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="font-size: 14px; color: #6D7588;">Password Sementara</td>
                                        <td style="font-size: 14px; font-weight: 600; color: #D32F2F; text-align: right;">
                                            {{ $password }}
                                        </td>
                                    </tr>
                                </table>
                                <p style="font-size: 12px; color: #D32F2F; margin: 8px 0 0 0; line-height: 1.4;">
                                    *Demi keamanan, harap segera ubah password Anda setelah berhasil melakukan login untuk
                                    pertama kali.
                                </p>
                            </td>
                        </tr>
                    @endisset
                    <tr>
                        <td style="padding: 10px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-size: 14px; color: #6D7588;">Tanggal Registrasi</td>
                                    <td style="font-size: 14px; font-weight: 600; color: #212121; text-align: right;">
                                        {{ now()->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="font-size: 12px; color: #9CA3AF; text-align: center; margin: 16px 0 0 0;">
        Link verifikasi ini akan kadaluarsa dalam 60 menit.
    </p>
</x-emails.layout>