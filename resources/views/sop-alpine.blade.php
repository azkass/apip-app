<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOP</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.5/cdn.js" defer></script>
    <style>
        .shape-box, .shape-circle {
            position: absolute;
        }
    </style>
</head>
<body class="p-4">

    <h1 class="text-2xl font-bold mb-4">Halaman SOP</h1>

    <div x-data="{
        inputs: [
            { id: 1, type: 'box', x: 0, y: 0 },
            { id: 2, type: 'circle', x: 0, y: 0 }
        ],
        shapes: [],
        linePath: ''
    }">
        <!-- Input untuk Bentuk -->
        <template x-for="input in inputs" :key="input.id">
            <div class="mb-4">
                <h2 class="text-lg font-medium">Bentuk Ke-<span x-text="input.id"></span></h2>
                <label for="shape-selector" class="block">Pilih Bentuk:</label>
                <select x-model="input.type" class="px-4 py-2 border rounded mb-2">
                    <option value="box">Kotak</option>
                    <option value="circle">Lingkaran</option>
                </select>

                <label for="x-position" class="block">Posisi X:</label>
                <input type="number" x-model="input.x" class="px-4 py-2 border rounded mb-2" placeholder="Masukkan X" />

                <label for="y-position" class="block">Posisi Y:</label>
                <input type="number" x-model="input.y" class="px-4 py-2 border rounded" placeholder="Masukkan Y" />
            </div>
        </template>

        <!-- Tombol Generate -->
        <button
            @click="
                shapes = inputs.map(input => ({ ...input, id: Date.now() + Math.random() }));
                if (shapes.length === 2) {
                    const [first, second] = shapes;

                    // Hitung titik tengah berdasarkan jenis bentuk
                    const startX = first.x + (first.type === 'box' ? 50 : 25);
                    const startY = first.y + (first.type === 'box' ? 25 : 25);
                    const endX = second.x + (second.type === 'box' ? 50 : 25);
                    const endY = second.y + (second.type === 'box' ? 25 : 25);

                    // Menyusun path untuk garis sudut siku
                    linePath = `M ${startX},${startY} L ${startX},${endY} L ${endX},${endY}`;

                    console.log(linePath);  // Debugging output untuk memastikan linePath terisi
                }
            "
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Generate Bentuk
        </button>

        <!-- Area untuk Menampilkan Bentuk dan Garis -->
        <div class="relative w-full h-[1080px] border mt-4">
            <!-- Garis -->
            <svg class="absolute top-0 left-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" x-cloak>
                <!-- Menampilkan path jika linePath tidak kosong -->
                <path
                    x-show="linePath !== ''"
                    :d="linePath"
                    stroke="black"
                    fill="transparent"
                    stroke-width="2" />
            </svg>

            <!-- Bentuk -->
            <template x-for="shape in shapes" :key="shape.id">
                <div
                    :style="`position: absolute; top: ${shape.y}px; left: ${shape.x}px;`"
                    class="shape-container">
                    <template x-if="shape.type === 'box'">
                        <x-box />
                    </template>
                    <template x-if="shape.type === 'circle'">
                        <x-circle />
                    </template>
                </div>
            </template>
        </div>
    </div>

</body>
</html>
