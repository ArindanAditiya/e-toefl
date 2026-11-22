<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat {{ $grade->category->name ?? 'TOEFL' }} - {{ $grade->user->name ?? 'Peserta' }}</title>
    <style>
        @page {
            size: 297mm 210mm;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html, body {
            width: 297mm;
            height: 210mm;
            max-width: 297mm;
            max-height: 210mm;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #ffffff;
            color: #1a251e;
        }
        .cert-container {
            position: absolute;
            top: 7mm;
            left: 7mm;
            width: 283mm;
            height: 196mm;
            border: 3.5px solid #0A3D24;
            background: #ffffff;
        }
        .cert-inner {
            position: absolute;
            top: 3mm;
            left: 3mm;
            width: 275mm;
            height: 188mm;
            border: 1.5px solid #C5A059;
            padding: 10px 24px;
        }
        .corner-ornament {
            position: absolute;
            width: 26px;
            height: 26px;
        }
        .corner-tl { top: 4px; left: 4px; border-top: 2.5px solid #0A3D24; border-left: 2.5px solid #0A3D24; }
        .corner-tr { top: 4px; right: 4px; border-top: 2.5px solid #0A3D24; border-right: 2.5px solid #0A3D24; }
        .corner-bl { bottom: 4px; left: 4px; border-bottom: 2.5px solid #0A3D24; border-left: 2.5px solid #0A3D24; }
        .corner-br { bottom: 4px; right: 4px; border-bottom: 2.5px solid #0A3D24; border-right: 2.5px solid #0A3D24; }

        /* Soft Watermark in center */
        .watermark {
            position: absolute;
            top: 36%;
            left: 50%;
            margin-left: -120px;
            margin-top: -50px;
            width: 240px;
            opacity: 0.07;
            z-index: 0;
            text-align: center;
        }

        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            position: relative;
            z-index: 2;
        }
        .header-logo {
            width: 88px;
            text-align: center;
            vertical-align: middle;
        }
        .header-logo img {
            max-width: 82px;
            max-height: 76px;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 88px; /* Balance logo */
        }
        .inst-kop {
            font-size: 19px;
            font-weight: 800;
            color: #0A3D24;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .inst-sub {
            font-size: 13.5px;
            font-weight: 700;
            color: #C5A059;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .inst-address {
            font-size: 9px;
            color: #4a5d51;
            margin-top: 3px;
        }
        .header-divider {
            height: 2px;
            background: #0A3D24;
            border: none;
            margin: 6px 0 10px 0;
        }

        /* Title */
        .cert-title-wrap {
            text-align: center;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }
        .cert-main-title {
            font-size: 23px;
            font-weight: 800;
            color: #0A3D24;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .cert-no {
            font-size: 10px;
            color: #64746b;
            font-weight: 600;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        /* Body */
        .cert-body {
            text-align: center;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }
        .cert-lead {
            font-size: 11.5px;
            color: #4a5568;
            font-style: italic;
        }
        .recipient-name {
            font-size: 24px;
            font-weight: 800;
            color: #0A3D24;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 4px 0 3px 0;
            border-bottom: 1.5px solid #C5A059;
            display: inline-block;
            padding: 0 30px 3px 30px;
        }
        .cert-statement {
            font-size: 11.5px;
            color: #4a5568;
            margin-top: 3px;
        }
        .exam-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #1a251e;
            font-style: italic;
            margin-top: 2px;
        }

        /* Score Table */
        .score-wrap {
            width: 84%;
            margin: 8px auto 8px auto;
            position: relative;
            z-index: 2;
        }
        .score-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .score-table th {
            background-color: #0A3D24;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: 700;
            padding: 5px 8px;
            border: 1px solid #0A3D24;
            letter-spacing: 0.5px;
        }
        .score-table td {
            background-color: #fdfdfc;
            font-size: 11.5px;
            font-weight: 600;
            color: #1a251e;
            padding: 5px 8px;
            border: 1px solid #d4ded7;
        }
        .score-total-th {
            background-color: #C5A059 !important;
            color: #ffffff !important;
        }
        .score-total-td {
            background-color: #f7f3e8 !important;
            color: #0A3D24 !important;
            font-size: 13px !important;
            font-weight: 800 !important;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            position: relative;
            z-index: 2;
        }
        .footer-left {
            width: 45%;
            vertical-align: bottom;
            text-align: left;
            padding-left: 6px;
        }
        .qr-card {
            display: inline-block;
            border: 1px solid #C5A059;
            background: #fbf9f4;
            padding: 5px 8px;
            border-radius: 4px;
        }
        .qr-card-title {
            font-size: 8.5px;
            font-weight: 800;
            color: #0A3D24;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .qr-card-text {
            font-size: 8px;
            color: #4a5d51;
            margin-top: 2px;
            line-height: 1.25;
        }
        .footer-right {
            width: 48%;
            vertical-align: bottom;
            text-align: center;
            padding-right: 6px;
        }
        .sig-city-date {
            font-size: 10px;
            color: #4a5568;
            margin-bottom: 2px;
        }
        .sig-inst {
            font-size: 10px;
            font-weight: 600;
            color: #0A3D24;
            text-transform: uppercase;
        }
        .sig-role {
            font-size: 11px;
            font-weight: 800;
            color: #0A3D24;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .sig-image-wrap {
            height: 48px;
            margin: 2px auto;
            position: relative;
        }
        .sig-image {
            max-height: 48px;
            max-width: 130px;
        }
        .sig-name {
            font-size: 12px;
            font-weight: 800;
            color: #1a251e;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="corner-ornament corner-tl"></div>
        <div class="corner-ornament corner-tr"></div>
        <div class="corner-ornament corner-bl"></div>
        <div class="corner-ornament corner-br"></div>

        <div class="cert-inner">
            <!-- Center Soft Watermark -->
            @if(file_exists(public_path('assets/images/logo-icon.png')))
                <div class="watermark">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/logo-icon.png'))) }}" style="width: 200px; height: auto;" alt="Watermark">
                </div>
            @endif

            <!-- Header Table -->
            <table class="header-table">
                <tr>
                    <td class="header-logo">
                        @if(file_exists(public_path('assets/images/logo-icon.png')))
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/logo-icon.png'))) }}" alt="Logo Ma'had Aly">
                        @elseif(file_exists(public_path('id/assets/images/logo.png')))
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('id/assets/images/logo.png'))) }}" alt="Logo Ma'had Aly">
                        @endif
                    </td>
                    <td class="header-text">
                        <div class="inst-kop">MA'HAD ALY HASYIM ASY'ARI</div>
                        <div class="inst-sub">LEMBAGA BAHASA &bull; PONDOK PESANTREN TEBUIRENG JOMBANG</div>
                        <div class="inst-address">Jl. Irian Jaya No. 10, Tebuireng, Cukir, Diwek, Jombang, Jawa Timur 61471 &bull; Portal: https://etoefl.mahatbi.or.id</div>
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
                <div class="cert-statement">has successfully completed the online English proficiency assessment on</div>
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
                            <div class="qr-card-title">&check; Official Digital Verification</div>
                            <div class="qr-card-text">
                                Verified Portal: <strong>https://etoefl.mahatbi.or.id</strong><br>
                                Issued Date: <strong>{{ date('d F Y', strtotime($grade->end_time ?? now())) }}</strong><br>
                                Valid Until: <strong>{{ date('d F Y', strtotime(($grade->end_time ?? now()) . ' + 2 years')) }}</strong>
                            </div>
                        </div>
                    </td>
                    <td class="footer-right">
                        <div class="sig-city-date">Jombang, {{ date('d F Y', strtotime($grade->end_time ?? now())) }}</div>
                        <div class="sig-inst">Ma'had Aly Hasyim Asy'ari</div>
                        <div class="sig-role">Kepala Lembaga Bahasa</div>
                        <div class="sig-image-wrap">
                            @if(file_exists(public_path('assets/images/ttd.png')))
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/ttd.png'))) }}" class="sig-image" alt="TTD & Stempel">
                            @endif
                        </div>
                        <div class="sig-name">Abdul Karim Amrulloh</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>
</html>
