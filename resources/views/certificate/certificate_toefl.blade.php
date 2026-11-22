<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat {{ $grade->category->name ?? 'TOEFL' }} - {{ $grade->user->name ?? 'Peserta' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #fbfbfa;
            color: #1a251e;
            width: 297mm;
            height: 210mm;
            padding: 12mm;
            position: relative;
        }
        .cert-outer-border {
            width: 100%;
            height: 100%;
            border: 4px solid #0A3D24;
            padding: 5px;
            position: relative;
            background: #ffffff;
        }
        .cert-inner-border {
            width: 100%;
            height: 100%;
            border: 1.5px solid #C5A059;
            padding: 18px 30px;
            position: relative;
        }
        .corner-ornament {
            position: absolute;
            width: 32px;
            height: 32px;
            border-color: #C5A059;
        }
        .corner-tl { top: 6px; left: 6px; border-top: 3px solid #0A3D24; border-left: 3px solid #0A3D24; }
        .corner-tr { top: 6px; right: 6px; border-top: 3px solid #0A3D24; border-right: 3px solid #0A3D24; }
        .corner-bl { bottom: 6px; left: 6px; border-bottom: 3px solid #0A3D24; border-left: 3px solid #0A3D24; }
        .corner-br { bottom: 6px; right: 6px; border-bottom: 3px solid #0A3D24; border-right: 3px solid #0A3D24; }

        /* Institutional Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .header-logo {
            width: 72px;
            text-align: center;
            vertical-align: middle;
        }
        .header-logo img {
            max-width: 68px;
            max-height: 68px;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 72px; /* Balance logo */
        }
        .inst-title {
            font-size: 19px;
            font-weight: 800;
            color: #0A3D24;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .inst-sub {
            font-size: 13px;
            font-weight: 600;
            color: #C5A059;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .inst-address {
            font-size: 9.5px;
            color: #556b5d;
            margin-top: 4px;
        }
        .header-divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #0A3D24, #C5A059, #0A3D24, transparent);
            border: none;
            margin: 8px 0 14px 0;
        }

        /* Certificate Title */
        .cert-title-wrap {
            text-align: center;
            margin-bottom: 12px;
        }
        .cert-main-title {
            font-size: 24px;
            font-weight: 800;
            color: #0A3D24;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .cert-no {
            font-size: 10.5px;
            color: #77837b;
            font-weight: 600;
            letter-spacing: 1px;
            margin-top: 3px;
        }

        /* Certificate Body */
        .cert-body {
            text-align: center;
            margin-bottom: 12px;
        }
        .cert-lead {
            font-size: 12px;
            color: #4a5568;
            font-style: italic;
        }
        .recipient-name {
            font-size: 26px;
            font-weight: 800;
            color: #0A3D24;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 6px 0;
            border-bottom: 1.5px solid #C5A059;
            display: inline-block;
            padding: 0 35px 4px 35px;
        }
        .cert-statement {
            font-size: 12px;
            color: #4a5568;
            margin-top: 4px;
        }
        .exam-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a251e;
            font-style: italic;
            margin-top: 3px;
        }

        /* Score Table */
        .score-wrap {
            width: 82%;
            margin: 10px auto 12px auto;
        }
        .score-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .score-table th {
            background-color: #0A3D24;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 6px 10px;
            border: 1px solid #0A3D24;
            letter-spacing: 0.5px;
        }
        .score-table td {
            background-color: #fcfcfb;
            font-size: 12px;
            font-weight: 600;
            color: #1a251e;
            padding: 6px 10px;
            border: 1px solid #dcdedc;
        }
        .score-total-th {
            background-color: #C5A059 !important;
            color: #ffffff !important;
        }
        .score-total-td {
            background-color: #f7f3e8 !important;
            color: #0A3D24 !important;
            font-size: 14px !important;
            font-weight: 800 !important;
        }

        /* Footer & Signatures */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .footer-left {
            width: 45%;
            vertical-align: bottom;
            text-align: left;
            padding-left: 10px;
        }
        .qr-card {
            display: inline-block;
            border: 1px solid #C5A059;
            background: #fdfbf7;
            padding: 6px 10px;
            border-radius: 4px;
        }
        .qr-card-title {
            font-size: 9px;
            font-weight: 800;
            color: #0A3D24;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .qr-card-text {
            font-size: 8.5px;
            color: #556b5d;
            margin-top: 2px;
            line-height: 1.3;
        }
        .qr-card-url {
            color: #0A3D24;
            font-weight: 700;
            text-decoration: none;
        }
        .footer-right {
            width: 45%;
            vertical-align: bottom;
            text-align: center;
            padding-right: 10px;
        }
        .sig-city-date {
            font-size: 10.5px;
            color: #4a5568;
            margin-bottom: 2px;
        }
        .sig-role {
            font-size: 11px;
            font-weight: 700;
            color: #0A3D24;
            text-transform: uppercase;
        }
        .sig-space {
            height: 40px;
        }
        .sig-name {
            font-size: 12px;
            font-weight: 800;
            color: #1a251e;
            text-decoration: underline;
        }
        .sig-sub {
            font-size: 9.5px;
            color: #556b5d;
        }
    </style>
</head>
<body>
    <div class="cert-outer-border">
        <div class="corner-ornament corner-tl"></div>
        <div class="corner-ornament corner-tr"></div>
        <div class="corner-ornament corner-bl"></div>
        <div class="corner-ornament corner-br"></div>

        <div class="cert-inner-border">
            <!-- Header Table -->
            <table class="header-table">
                <tr>
                    <td class="header-logo">
                        @if(file_exists(public_path('id/assets/images/logo.png')))
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('id/assets/images/logo.png'))) }}" alt="Logo Ma'had Aly">
                        @endif
                    </td>
                    <td class="header-text">
                        <div class="inst-title">Pusat Bahasa Ma'had Aly Hasyim Asy'ari</div>
                        <div class="inst-sub">Pondok Pesantren Tebuireng Jombang</div>
                        <div class="inst-address">Jl. Irian Jaya No. 10, Tebuireng, Cukir, Diwek, Jombang, Jawa Timur 61471 &bull; https://etoefl.mahatbi.or.id</div>
                    </td>
                </tr>
            </table>

            <hr class="header-divider" />

            <!-- Title -->
            <div class="cert-title-wrap">
                <div class="cert-main-title">Certificate of Achievement</div>
                <div class="cert-no">Certificate No: MAHATBI/TOEFL/{{ date('Y', strtotime($grade->end_time ?? now())) }}/{{ strtoupper(substr(md5($grade->id ?? 'CERT'), 0, 8)) }}</div>
            </div>

            <!-- Recipient -->
            <div class="cert-body">
                <div class="cert-lead">This is to officially certify that</div>
                <div class="recipient-name">{{ $grade->user->name ?? 'Student Name' }}</div>
                <div class="cert-statement">has completed the official online assessment on</div>
                <div class="exam-title">{{ $grade->exam->title ?? 'TOEFL Prediction Test' }}</div>
            </div>

            <!-- Scores Table -->
            <div class="score-wrap">
                <table class="score-table">
                    <thead>
                        <tr>
                            @if(isset($grade->gradeDetail) && count($grade->gradeDetail) > 0)
                                @foreach($grade->gradeDetail as $detail)
                                    <th>{{ $detail->valueCategory->name ?? 'Section' }}</th>
                                @endforeach
                            @else
                                <th>Section 1 (Listening)</th>
                                <th>Section 2 (Structure)</th>
                                <th>Section 3 (Reading)</th>
                            @endif
                            <th class="score-total-th">TOTAL SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @if(isset($grade->gradeDetail) && count($grade->gradeDetail) > 0)
                                @foreach($grade->gradeDetail as $detail)
                                    <td>{{ $detail->grade }}</td>
                                @endforeach
                            @else
                                <td>{{ $grade->total_section_1 ?? '0' }}</td>
                                <td>{{ $grade->total_section_2 ?? '0' }}</td>
                                <td>{{ $grade->total_section_3 ?? '0' }}</td>
                            @endif
                            <td class="score-total-td">{{ $grade->grade ?? '0' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer & Signatures -->
            <table class="footer-table">
                <tr>
                    <td class="footer-left">
                        <div class="qr-card">
                            <div class="qr-card-title">&check; Official Online Verification</div>
                            <div class="qr-card-text">
                                Verified via: <span class="qr-card-url">https://etoefl.mahatbi.or.id</span><br>
                                Issued Date: <strong>{{ date('d F Y', strtotime($grade->end_time ?? now())) }}</strong><br>
                                Valid Until: <strong>{{ date('d F Y', strtotime(($grade->end_time ?? now()) . ' + 2 years')) }}</strong>
                            </div>
                        </div>
                    </td>
                    <td class="footer-right">
                        <div class="sig-city-date">Jombang, {{ date('d F Y', strtotime($grade->end_time ?? now())) }}</div>
                        <div class="sig-role">Pusat Bahasa Ma'had Aly Hasyim Asy'ari</div>
                        <div class="sig-space"></div>
                        <div class="sig-name">M. Farih Ridlo, M.Pd.</div>
                        <div class="sig-sub">Kepala Pusat Bahasa</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>
</html>
