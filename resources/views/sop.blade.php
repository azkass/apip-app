<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Flowchart with Table</title>
</head>
<body>
    <table class="border-collapse border border-slate-400 ...">
        <thead>
            <tr>
                <th class="border border-slate-300">State</th>
                <th class="border border-slate-300">City</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="border border-slate-300">
                    <div id="shape1" class="w-24 h-24 border-[3px] border-black flex items-center justify-center text-black font-bold">
                    Kotak 1
                    </div></td>
                <td class="border border-slate-300">Indianapolis</td>
            </tr>
            <tr>
                <td class="border border-slate-300">Ohio</td>
                <td class="border border-slate-300">Columbus</td>
            </tr>
            <tr>
                <td class="border border-slate-300">Michigan</td>
                <td class="border border-slate-300 pl-2">
                    <div id="shape2" class="w-24 h-24 border-[3px] border-black flex items-center justify-center text-black font-bold">
                    Kotak 2
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div><br><br><br></div>

    <div id="shape-container" class="relative">
        <!-- Kotak Pertama -->
        {{-- <div id="shape1" class="w-24 h-24 border-[3px] border-black flex items-center justify-center text-black font-bold">
            Kotak 1
        </div>

        <div><br><br><br></div>

        <!-- Kotak Kedua -->
        <div id="shape2" class="w-24 h-24 border-[3px] border-black flex items-center justify-center text-black font-bold">
            Kotak 2
        </div> --}}

        <!-- Canvas untuk Garis -->
        <svg id="connector" class="absolute top-0 left-0 w-full h-full">
            <line x1="0" y1="0" x2="0" y2="0" stroke="black" stroke-width="2" />
        </svg>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const shape1 = document.getElementById('shape1').getBoundingClientRect();
            const shape2 = document.getElementById('shape2').getBoundingClientRect();
            const connector = document.querySelector('#connector line');

            // Hitung posisi tengah setiap shape
            const x1 = shape1.left + shape1.width / 2;
            const y1 = shape1.height;
            const x2 = shape2.left + shape2.width / 2;
            const y2 = shape2.top;

            // Set koordinat garis
            connector.setAttribute('x1', x1);
            connector.setAttribute('y1', y1);
            connector.setAttribute('x2', x2);
            connector.setAttribute('y2', y2);
        });
    </script>
</body>
</html>
