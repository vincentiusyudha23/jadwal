@extends('emails.template')

@section('content')
    <table role="presentation" cellspacing="0" cellpadding="0" width="100%" dir="ltr" style="background:#fff; margin-bottom: 24px; border: 1px solid #E0DED2; border-radius: 16px;">
        <tr>
            <td>
                <table role="presentation" cellspacing="0" cellpadding="0" width="90%" style="margin: 5% auto;">
                    <tbody>
                        <tr>
                            <td>
                                <h3 style="color:#382E38; margin:0; padding:0; text-align: center;">
                                    Kode OTP Anda
                                </h3>

                                <hr style="border-top: 1px solid #382E38;margin:3% 0;opacity: .15;">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <p>Gunakan kode berikut untuk memulihkan kata sandi anda</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <p style="border: 1px solid #382E38; display: inline-block; padding: 8px; border-radius: 5px; font-size: 1.5rem;">{{ $otp }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <p style="font-size: 13px;">Kode ini berlaku selama 5 menit. Jangan berikan kode ini kepada siapa pun.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
@endsection