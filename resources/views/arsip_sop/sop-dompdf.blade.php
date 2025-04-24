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
                <label>Durasi (jam):</label>
                <input type="number" name="durations[]" required min="1" step="0.1">
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
                <label>Durasi (jam):</label>
                <input type="number" name="durations[]" required min="1" step="0.1">
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
            `;
            container.appendChild(newRow);
        }
    </script>
</body>
</html>
