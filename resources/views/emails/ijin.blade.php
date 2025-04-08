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
                                    Pengajuan Izin Karyawan
                                </h3>

                                <hr style="border-top: 1px solid #382E38;margin:3% 0;opacity: .15;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Karyawan atas nama <strong>{{ $ijin?->user?->name ?? 'hanggar' }}</strong>, telah melakukan pengajuan ijin.</p>

                                <table role="presentation" border="1" cellpadding="5" cellspacing="0" width="100%" style="border-collapse: collapse; margin: 3% 0;">
                                    <tr>
                                        <td style="width: 30%;">Nama :</td>
                                        <td style="width: 70%;">{{ $ijin?->user?->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">ID Karyawan :</td>
                                        <td style="width: 70%;">{{ $ijin?->user?->id_karyawan ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Jabatan :</td>
                                        <td style="width: 70%;">{{ $ijin?->user?->karyawan?->jabatan ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Divisi :</td>
                                        <td style="width: 70%;">{{ $ijin?->user?->karyawan?->divisi ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Tanggal Pengajuan :</td>
                                        <td style="width: 70%;">{{ $ijin?->created_at->format('d/m/Y') ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Tipe Ijin :</td>
                                        <td style="width: 70%;">{{ \App\Enums\IjinEnum::getLabel(($ijin?->type ?? 1)) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Dari :</td>
                                        <td style="width: 70%;">{{ $ijin?->from_date->format('d/m/Y') ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Sampai :</td>
                                        <td style="width: 70%;">{{ $ijin?->to_date->format('d/m/Y') ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Keterangan :</td>
                                        <td style="width: 70%;">{{ $ijin?->keterangan ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%;">Surat :</td>
                                        <td style="width: 70%; word-break: break-word;">
                                            @if ($ijin?->surat)
                                                <a href="{{ asset('assets/surat_ijin/' . $ijin?->surat) }}">{{ asset('assets/surat_ijin/' . $ijin?->surat) }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <hr style="border-top: 1px solid #382E38;margin:3% 0;opacity: .15;">

                                <a href="{{ url('/admin') }}" style="width: 100%; display: inline-block; background: #007bff;  color: #fff; text-decoration: none; border-radius: 5px; text-align:center; padding: 10px 0; font-weight: bold;">
                                    Lihat Pengajuan
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
@endsection