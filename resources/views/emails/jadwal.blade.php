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
                                    Jadwal Pekerjaan Baru
                                </h3>

                                <hr style="border-top: 1px solid #382E38;margin:3% 0;opacity: .15;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Halo <strong>{{ $jadwal?->user?->name ?? '' }}</strong>,</p>
                                <p>{{ $msg_jadwal ?? 'Anda memiliki jadwal pekerjaan yang baru saja dibuat.' }} Berikut rinciannya:</p>
                                
                                <table role="presentation" border="1" cellpadding="5" cellspacing="0" width="100%" style="border-collapse: collapse; margin: 3% 0;">
                                    <tr>
                                        <td style="width: 30%;">Tanggal :</td>
                                        <td style="width: 70%;">{{ $jadwal?->tanggal?->translatedFormat('l, d F Y') ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Waktu :</td>
                                        <td style="width: 70%;">{{ $jadwal?->waktu ?? '' }} WIB</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Tujuan :</td>
                                        <td style="width: 70%;">{{ $jadwal?->tujuan ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Tugas :</td>
                                        <td style="width: 70%;">{{ $jadwal?->tugas ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Catatan :</td>
                                        <td style="width: 70%;">{{ $jadwal?->note ?? '' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <hr style="border-top: 1px solid #382E38;margin:3% 0;opacity: .15;">

                                <a href="{{ url('/') }}" style="width: 100%; display: inline-block; background: #007bff;  color: #fff; text-decoration: none; border-radius: 5px; text-align:center; padding: 10px 0; font-weight: bold;">
                                    Lihat Jadwal
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
@endsection