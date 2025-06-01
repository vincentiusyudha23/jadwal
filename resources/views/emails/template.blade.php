<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#f4f4f4; font-family: 'Inter', sans-serif;">
    <tbody>
        <tr>
            <td>
                <table role="presentation" cellspacing="0" cellpadding="0" width="90%" style="max-width: 600px; border-collapse: collapse; margin: 0 auto;" dir="ltr">
                    <tr>
                        <td>
                            <table role="presentation" cellspacing="0" cellpadding="0" width="100%" style="background:#fff; margin: 24px 0; border: 1px solid #E0DED2; border-radius: 16px;">
                                <tr>
                                    <td>
                                        <table role="presentation" cellspacing="0" cellpadding="0" width="90%" style="margin: 5% auto;">
                                            <tr>
                                                <td>
                                                    <table role="presentation" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="vertical-align: middle;">
                                                                <a href="{{ url('/') }}">
                                                                    <img src="{{ asset('assets/img/logo-1.png') }}" alt="Logo" width="35px">
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="{{ url('/') }}" style="text-decoration:none; font-weight:bold; color: #382E38; padding: 12px;">PT. WIRA GRIYA</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            @yield('content')
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table ole="presentation" cellspacing="0" cellpadding="0" width="100%" style="background:#fff; margin-bottom: 24px; border: 1px solid #E0DED2; border-radius: 16px;">
                                <tr>
                                    <td>
                                        <table role="presentation" cellspacing="0" cellpadding="3" width="90%" style="margin: 5% auto;">
                                            <tr>
                                                <td style="text-align: center;">
                                                    <p style="font-size: 13px; color: #777;">Jika mengalami kendala, silakan hubungi tim Admin / HRD segera.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <p style="font-size: 13px; color: #777;">Terima kasih atas kedisiplinan dan kerja sama Anda! 🙌</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <p style="font-size: 13px; color: #777;">
                                                        © {{ date('Y') }} PT. WIRA GRIYA. Semua hak dilindungi.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>