@extends('layouts.app')
@section('content')
    <h1 class="font-bold text-lg mb-2">Activity Form</h1>
    <form action="{{ route('activity.generate') }}" method="POST">
        @csrf
        <div id="activity-container">
            <div class="activity-row flex items-center space-x-2">
                <label>Aktivitas:</label>
                <input type="text" name="activities[]" required class="border-2 border-blue-400 rounded-md mb-2 px-1">
                <label>Waktu (jam):</label>
                <input type="number" name="durations[]" required min="1" step="1" class="border-2 border-blue-400 rounded-md mb-2 px-1">
                <label>Aktor:</label>
                <select name="actor_roles[]" required class="border-2 border-blue-400 rounded-md mb-2 px-1">
                    <option value="Aktor 1">Aktor 1</option>
                    <option value="Aktor 2">Aktor 2</option>
                </select>
                <select name="actors[]" required class="border-2 border-blue-400 rounded-md mb-2 px-1">
                    <option value="Start">Start</option>
                    <option value="Process">Process</option>
                    <option value="Decision">Decision</option>
                    <option value="End">End</option>
                </select>
                <button type="button" onclick="removeRow(this)" class="border-2 border-red-400 rounded-md px-1 mb-2 hover:bg-red-300 text-red-700">Hapus</button>
            </div>
        </div>
        <button type="button" onclick="addRow()" class="border-2 border-blue-400 rounded-md p-1 hover:bg-blue-300 mt-2">Tambah Baris</button>
        <button type="submit" class="border-2 border-blue-400 rounded-md p-1 hover:bg-blue-300 mt-2">Generate</button>
    </form>

    <script>
        function addRow() {
            const container = document.getElementById('activity-container');
            const newRow = document.querySelector('.activity-row').cloneNode(true);
            newRow.querySelectorAll('input, select').forEach(field => field.value = '');
            container.appendChild(newRow);
        }

        function removeRow(button) {
            const row = button.parentElement; // Mengambil elemen parent dari tombol hapus
            const container = document.getElementById('activity-container');
            if (container.children.length > 1) { // Pastikan minimal satu baris tetap ada
                row.remove();
            } else {
                alert('Minimal terdapat satu aktivitas.');
            }
        }
    </script>
@endsection
