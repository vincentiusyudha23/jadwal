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
                                    ⏰ Pengingat Absen {{ $type ?? 'Masuk' }}
                                </h3>

                                <hr style="border-top: 1px solid #382E38;margin:3% 0;opacity: .15;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-align: center;">Halo <strong>{{ $nama_karyawan ?? 'Hanggar' }}</strong>,</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-align: center;">
                                    Semoga hari Anda menyenangkan! 😊
                                    <br>
                                    <br>
                                    Kami ingin mengingatkan bahwa Anda perlu melakukan 
                                    @if($type == 'Masuk')
                                        <strong>absen masuk sebelum pukul 08.00 pagi</strong>. 
                                    @endif
                                    @if($type == 'Pulang')
                                        <strong>absen pulang sebelum pukul 05.00 sore</strong>. 
                                    @endif

                                    Pastikan Anda melakukan absen tepat waktu untuk menghindari keterlambatan.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @if($type == 'Masuk')
                                    <p style="margin: 3% 1% 0 0;"><strong>📍 Waktu Absen Masuk:</strong> 08.00 WIB</p>
                                @endif
                                @if($type == 'Pulang')
                                    <p style="margin: 3% 1% 0 0;"><strong>📍 Waktu Absen Pulang:</strong> 17.00 WIB</p>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin: 1% 3% 0 0;"><strong>📍 Metode Absen:</strong> Absen Online via Web</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <hr style="border-top: 1px solid #382E38;margin:3% 0;opacity: .15;">

                                <a href="{{ $link_absen ?? '#' }}" style="width: 100%; display: inline-block; background: #007bff;  color: #fff; text-decoration: none; border-radius: 5px; text-align:center; padding: 10px 0; font-weight: bold;">
                                    Melakukan Absen
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

    </table>
@endsection