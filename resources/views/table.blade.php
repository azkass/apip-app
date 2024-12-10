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
                <th>No</th>
                <th>Aktivitas</th>
                <th>Simbol 1</th>
                <th>Simbol 2</th>
                <th>Durasi (jam)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $activity }}</td>
                    <td>{{ $symbols1[$index] }}</td>
                    <td>{{ $symbols2[$index] }}</td>
                    <td>{{ $durations[$index] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form method="POST" action="{{ route('generate-pdf') }}">
        @csrf
        @foreach ($activities as $activity)
            <input type="hidden" name="activities[]" value="{{ $activity }}">
        @endforeach
        @foreach ($symbols1 as $symbol1)
            <input type="hidden" name="symbols1[]" value="{{ $symbol1 }}">
        @endforeach
        @foreach ($symbols2 as $symbol2)
            <input type="hidden" name="symbols2[]" value="{{ $symbol2 }}">
        @endforeach
        @foreach ($durations as $duration)
            <input type="hidden" name="durations[]" value="{{ $duration }}">
        @endforeach
        <button type="submit">Download PDF</button>
    </form>
</body>
</html>
