<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 0; }
        h2 { font-size: 14px; margin-top: 20px; border-bottom: 1px solid #ccc; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        td { padding: 4px 8px; border-bottom: 1px solid #eee; }
        .label { width: 40%; color: #666; }
    </style>
</head>
<body>
    <h1>E-Raport Antasena Swimming Club</h1>
    <p>Periode: {{ $development->period }}</p>

    <h2>Identitas Siswa</h2>
    <table>
        <tr><td class="label">Nama Siswa</td><td>{{ $student->full_name }}</td></tr>
        <tr><td class="label">Coach</td><td>{{ $development->coach->name }}</td></tr>
        <tr><td class="label">Program</td><td>{{ $development->schoolClass->program->name }}</td></tr>
        <tr><td class="label">Kehadiran</td><td>{{ $attendanceCount }} pertemuan</td></tr>
    </table>

    <h2>Penilaian Perkembangan</h2>
    <table>
        @foreach (\App\Models\Development::aspects() as $key => $label)
            <tr>
                <td class="label">{{ $label }}</td>
                <td>{{ str_replace('_', ' ', ucfirst($development->$key)) }}</td>
            </tr>
        @endforeach
    </table>

    @if ($development->coach_note)
        <h2>Catatan Coach</h2>
        <p>{{ $development->coach_note }}</p>
    @endif
</body>
</html>
