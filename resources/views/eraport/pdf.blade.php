<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>E-Raport {{ $student->full_name }} — {{ $development->period }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #121b2e; background: #ffffff; }
        .page { padding: 32px; }

        /* Header */
        .header { border-bottom: 3px solid #0047a9; padding-bottom: 16px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; color: #0047a9; margin-bottom: 4px; }
        .header .period { font-size: 11px; color: #00687a; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }

        h2 { font-size: 13px; color: #0047a9; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
        h3 { font-size: 12px; color: #00687a; margin: 14px 0 6px; text-transform: uppercase; letter-spacing: 0.5px; }

        .profile { border: 1px solid #c2c6d6; border-radius: 8px; padding: 14px 16px; margin-bottom: 18px; }
        .profile table { width: 100%; border-collapse: collapse; }
        .profile td { padding: 4px 0; vertical-align: top; }
        .profile .label { color: #737785; width: 28%; }

        .summary { display: table; width: 100%; margin-bottom: 18px; }
        .summary-box { display: table-cell; border: 1px solid #c2c6d6; border-radius: 8px; padding: 12px 16px; }
        .summary-box:first-child { margin-right: 12px; }
        .summary-box .title { font-size: 9px; color: #737785; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .summary-box .value { font-size: 18px; font-weight: bold; color: #0047a9; }
        .summary-box .sub { font-size: 10px; color: #737785; margin-top: 2px; }

        table.matrix { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        table.matrix th { background: #0047a9; color: #ffffff; text-align: left; padding: 8px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        table.matrix td { padding: 7px 10px; border-bottom: 1px solid #e1e8ff; font-size: 11px; }
        table.matrix tr.section td { background: #e9edff; color: #00687a; font-weight: bold; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; }

        .score { display: inline-block; padding: 2px 10px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .score-sangat_baik { background: #d9e2ff; color: #001945; }
        .score-baik { background: #acedff; color: #001f26; }
        .score-cukup { background: #e1e8ff; color: #424654; }
        .score-kurang { background: #ffdad6; color: #93000a; }

        .note { border: 1px solid #b0c6ff; background: #f1f3ff; border-left: 4px solid #0047a9; border-radius: 6px; padding: 12px 16px; font-style: italic; color: #121b2e; }
        .note .coach { font-style: normal; font-size: 10px; color: #737785; margin-bottom: 6px; }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <h1>E-Raport ASC Academy</h1>
            <p class="period">Periode: {{ $development->period }}</p>
        </div>

        <!-- Identitas Siswa -->
        <h2>Identitas Siswa</h2>
        <div class="profile">
            <table>
                <tr><td class="label">Nama Siswa</td><td>{{ $student->full_name }}</td></tr>
                <tr><td class="label">Kelas / Level</td><td>{{ $development->schoolClass->name }}{{ $development->schoolClass->level_label ? ' · '.$development->schoolClass->level_label : '' }}</td></tr>
                <tr><td class="label">Program</td><td>{{ $development->schoolClass->program->name }}</td></tr>
                <tr><td class="label">Coach</td><td>{{ $development->coach->name }}</td></tr>
                @if ($scheduleLabel)
                    <tr><td class="label">Jadwal</td><td>{{ $scheduleLabel }}</td></tr>
                @endif
            </table>
        </div>

        <!-- Ringkasan -->
        <div class="summary">
            <div class="summary-box" style="margin-right: 12px;">
                <div class="title">Tingkat Kehadiran</div>
                <div class="value">{{ $attendancePercent !== null ? $attendancePercent.'%' : $attendanceCount.' pertemuan' }}</div>
                <div class="sub">{{ $attendanceCount }} dari {{ $totalSessions ?? '-' }} sesi hadir</div>
            </div>
            <div class="summary-box">
                <div class="title">Penilaian Keseluruhan</div>
                <div class="value">{{ $overallScore['label'] }}</div>
                <div class="sub">Rata-rata seluruh aspek penilaian</div>
            </div>
        </div>

        <!-- Penilaian -->
        <h2>Penilaian Perkembangan</h2>

        <h3>Penilaian Umum</h3>
        <table class="matrix">
            <tr>
                <th style="width:70%;">Aspek Penilaian</th>
                <th>Nilai</th>
            </tr>
            @foreach (\App\Models\Development::umumAspects() as $key => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td><span class="score score-{{ $development->$key }}">{{ \App\Models\Development::scoreLabel($development->$key) }}</span></td>
                </tr>
            @endforeach
        </table>

        @foreach (\App\Models\Development::styles() as $style => $styleLabel)
            <h3>{{ $styleLabel }}</h3>
            <table class="matrix">
                <tr>
                    <th style="width:70%;">Aspek Penilaian</th>
                    <th>Nilai</th>
                </tr>
                @foreach (\App\Models\Development::khususAspects() as $key => $label)
                    @php
                        $field = \App\Models\Development::styleAspectKey($style, $key);
                    @endphp
                    <tr>
                        <td>{{ $label }}</td>
                        <td><span class="score score-{{ $development->$field }}">{{ \App\Models\Development::scoreLabel($development->$field) }}</span></td>
                    </tr>
                @endforeach
            </table>
        @endforeach

        <!-- Catatan Coach -->
        @if ($development->coach_note)
            <h2>Catatan Coach</h2>
            <div class="note">
                <p class="coach">— {{ $development->coach->name }}</p>
                {{ $development->coach_note }}
            </div>
        @endif
    </div>
</body>
</html>
