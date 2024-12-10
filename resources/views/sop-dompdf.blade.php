<!DOCTYPE html>
<html>
<head>
    <title>Form Aktivitas</title>
</head>
<body>
    <h1>Input Aktivitas</h1>
    <form method="POST" action="{{ route('generate-table') }}">
        @csrf
        <div id="activity-container">
            <div class="activity-row">
                <label>Aktivitas:</label>
                <input type="text" name="activities[]" required>
                <label>Aktor 1</label>
                <select name="symbols1[] required">
                    <option value="start">Start</option>
                    <option value="process">Process</option>
                    <option value="decision">Decision</option>
                    <option value="end">End</option>
                </select>
                <label>Aktor 2</label>
                <select name="symbols2[]">
                    <option value="start">Start</option>
                    <option value="process">Process</option>
                    <option value="decision">Decision</option>
                    <option value="end">End</option>
                </select>
                <label>Durasi (jam)</label>
                <input type="number" name="durations[]" required min="1" step="1">
            </div>
        </div>
        <button type="button" onclick="addActivity()">Tambah Aktivitas</button>
        <button type="submit">Generate Tabel</button>
    </form>

    <script>
        function addActivity() {
            const container = document.getElementById('activity-container');
            const newRow = document.createElement('div');
            newRow.classList.add('activity-row');
            newRow.innerHTML = `
                <label>Aktivitas:</label>
                <input type="text" name="activities[]" required>
                <label>Aktor 1</label>
                <select name="symbols1[] required">
                    <option value="start">Start</option>
                    <option value="process">Process</option>
                    <option value="decision">Decision</option>
                    <option value="end">End</option>
                </select>
                <label>Aktor 2</label>
                <select name="symbols2[]">
                    <option value="start">Start</option>
                    <option value="process">Process</option>
                    <option value="decision">Decision</option>
                    <option value="end">End</option>
                </select>
                <label>Durasi (jam):</label>
                <input type="number" name="durations[]" required min="1" step="1">
            `;
            container.appendChild(newRow);
        }
    </script>
</body>
</html>
