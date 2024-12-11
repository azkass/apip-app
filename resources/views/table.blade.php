<!DOCTYPE html>
<html>
<head>
    <title>Tabel Aktivitas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Tabel Aktivitas</h1>
    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Aktivitas</th>
                <th rowspan="2">Durasi (jam)</th>
                <th colspan="2">Aktor</th>
            </tr>
            <tr>
                <th>Aktor 1</th>
                <th>Aktor 2</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $activity }}</td>
                    <td>{{ $durations[$index] }}</td>
                    <td>{{ $actor1[$index] }}</td>
                    <td>{{ $actor2[$index] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form method="POST" action="{{ route('generate-pdf') }}">
        @csrf
        @foreach ($activities as $activity)
            <input type="hidden" name="activities[]" value="{{ $activity }}">
        @endforeach
        @foreach ($durations as $duration)
            <input type="hidden" name="durations[]" value="{{ $duration }}">
        @endforeach
        @foreach ($actor1 as $value)
            <input type="hidden" name="actor1[]" value="{{ $value }}">
        @endforeach
        @foreach ($actor2 as $value)
            <input type="hidden" name="actor2[]" value="{{ $value }}">
        @endforeach
        <button type="submit">Download PDF</button>
    </form>
</body>
</html>
