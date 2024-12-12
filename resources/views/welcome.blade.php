<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Form</title>
</head>
<body>
    <h1>Activity Form</h1>
    <form action="{{ route('activity.generate') }}" method="POST">
        @csrf
        <div id="activity-container">
            <div class="activity-row">
                <label>Aktivitas:</label>
                <input type="text" name="activities[]" required>
                <label>Durasi (jam):</label>
                <input type="number" name="durations[]" required min="1" step="1">
                <label>Aktor:</label>
                <select name="actor_roles[]" required>
                    <option value="Aktor 1">Aktor 1</option>
                    <option value="Aktor 2">Aktor 2</option>
                </select>
                <select name="actors[]" required>
                    <option value="Start">Start</option>
                    <option value="Process">Process</option>
                    <option value="Decision">Decision</option>
                    <option value="End">End</option>
                </select>
            </div>
        </div>
        <button type="button" onclick="addRow()">Tambah Baris</button>
        <button type="submit">Generate</button>
    </form>

    <script>
        function addRow() {
            const container = document.getElementById('activity-container');
            const newRow = document.querySelector('.activity-row').cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(newRow);
        }
    </script>
</body>
</html>
