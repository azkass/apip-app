@extends('layouts.app')
@section('content')
    <h1>Generated Table</h1>
    <div class="flex">
        <div class="flex-auto">
            <svg id="resultSOP" width="1115" height="792" xmlns="http://www.w3.org/2000/svg" style="border:1px solid #000;"></svg>
        </div>
        <div class="flex-auto">
            <button id="printButton" class="print-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="PrintSOP()">Print SOP</button>
        </div>
    </div>
    <script>
        const svgNS = "http://www.w3.org/2000/svg";
        const svg = document.getElementById('resultSOP');

        const activities = @json($activities);
        const durations = @json($durations);
        const actorRoles = @json($actorRoles);
        const actors = @json($actors);

        const startX = 0;
        const startY = 0;
        const headersHeight = 50;
        const rowHeight = 80;
        const colWidths = [30, 200, 100, 100, 100];

        // Draw table headers
        const headers = ['No', 'Aktivitas', 'Waktu', 'Aktor 1', 'Aktor 2'];
        let currentX = startX;
        for (let i = 0; i < headers.length; i++) {
            const rect = document.createElementNS(svgNS, 'rect');
            rect.setAttribute('x', currentX);
            rect.setAttribute('y', startY);
            rect.setAttribute('width', colWidths[i]);
            rect.setAttribute('height', headersHeight);
            rect.setAttribute('stroke', 'black');
            rect.setAttribute('fill', 'lightgray');
            svg.appendChild(rect);

            // Membuat teks berada di tengah
            const text = document.createElementNS(svgNS, 'text');
            text.setAttribute('x', currentX + colWidths[i] / 2); // Tengah horizontal
            text.setAttribute('y', startY + headersHeight / 2); // Tengah vertikal
            text.setAttribute('text-anchor', 'middle'); // Perataan horizontal (tengah)
            text.setAttribute('dominant-baseline', 'middle'); // Perataan vertikal (tengah)
            text.textContent = headers[i];
            svg.appendChild(text);

            currentX += colWidths[i];
        }

        // Add column numbers (new row after headers)
        const columnNumbers = ['(1)', '(2)', '(3)', '(4)', '(5)'];
        currentX = startX; // Reset X position
        const numbersY = startY + headersHeight; // Position below headers
        const numbersHeight = 30; // Height of numbers row

        for (let i = 0; i < columnNumbers.length; i++) {
            // Draw the rectangle
            const rect = document.createElementNS(svgNS, 'rect');
            rect.setAttribute('x', currentX);
            rect.setAttribute('y', numbersY);
            rect.setAttribute('width', colWidths[i]);
            rect.setAttribute('height', numbersHeight);
            rect.setAttribute('stroke', 'black');
            rect.setAttribute('fill', 'lightgray');
            svg.appendChild(rect);

            // Centered text
            const text = document.createElementNS(svgNS, 'text');
            text.setAttribute('x', currentX + colWidths[i] / 2); // Horizontal center
            text.setAttribute('y', numbersY + numbersHeight / 2); // Vertical center
            text.setAttribute('text-anchor', 'middle'); // Center text horizontally
            text.setAttribute('dominant-baseline', 'middle'); // Center text vertically
            text.textContent = columnNumbers[i];
            svg.appendChild(text);

            currentX += colWidths[i];
        }


        const centers = [];

        // Write table content
        let currentY = startY + headersHeight +numbersHeight; // Start below headers and numbers
        for (let i = 0; i < activities.length; i++) {
            currentX = startX;

            const row = [
                i + 1,
                activities[i],
                durations[i] + ' jam',
                actorRoles[i] === 'Aktor 1' ? actors[i] : '',
                actorRoles[i] === 'Aktor 2' ? actors[i] : ''
            ];

            for (let j = 0; j < row.length; j++) {
                const rect = document.createElementNS(svgNS, 'rect');
                rect.setAttribute('x', currentX);
                rect.setAttribute('y', currentY);
                rect.setAttribute('width', colWidths[j]);
                rect.setAttribute('height', rowHeight);
                rect.setAttribute('stroke', 'black');
                rect.setAttribute('fill', 'none');
                svg.appendChild(rect);

                if (j === 3 || j === 4) {
                    const shape = row[j];
                    const centerX = currentX + colWidths[j] / 2;
                    const centerY = currentY + rowHeight / 2;

                    if (shape) {
                        centers.push({ x: centerX, y: centerY });
                    }
                } else {
                    const text = document.createElementNS(svgNS, 'text');
                    text.setAttribute('x', currentX + 5);
                    text.setAttribute('y', currentY + rowHeight / 2);
                    text.textContent = row[j];
                    svg.appendChild(text);
                }
                currentX += colWidths[j];
            }

            currentY += rowHeight;
        }

        // Create connection lines
        for (let i = 0; i < centers.length - 1; i++) {
            const start = centers[i];
            const end = centers[i + 1];
            const midX = start.x;
            const midY = end.y;

            // Adjusted for right-angle lines
            const path = document.createElementNS(svgNS, 'path');
            const d = `M ${start.x},${start.y} L ${start.x},${midY} L ${end.x},${end.y}`;
            path.setAttribute('d', d);
            path.setAttribute('stroke', 'black');
            path.setAttribute('fill', 'none');
            svg.appendChild(path);
        }

        // Draw shapes after lines
        for (let i = 0; i < activities.length; i++) {
            const shape = actorRoles[i] === 'Aktor 1' ? actors[i] : actorRoles[i] === 'Aktor 2' ? actors[i] : null;

            if (shape) {
                const center = centers[i];
                if (shape === 'Start' || shape === 'End') {
                    const rect = document.createElementNS(svgNS, 'rect');
                    rect.setAttribute('x', center.x - colWidths[3] / 3);
                    rect.setAttribute('y', center.y - rowHeight / 6);
                    rect.setAttribute('width', (colWidths[3] * 2) / 3);
                    rect.setAttribute('height', rowHeight / 3);
                    rect.setAttribute('rx', 15); // Membulatkan sudut secara horizontal
                    rect.setAttribute('ry', 15); // Membulatkan sudut secara vertikal
                    rect.setAttribute('stroke', 'black'); // Warna garis sesuai
                    rect.setAttribute('fill', 'white'); // Warna isi sesuai
                    svg.appendChild(rect);
                } else if (shape === 'Process') {
                    const rectShape = document.createElementNS(svgNS, 'rect');
                    rectShape.setAttribute('x', center.x - colWidths[3] / 3);
                    rectShape.setAttribute('y', center.y - rowHeight / 6);
                    rectShape.setAttribute('width', (colWidths[3] * 2) / 3);
                    rectShape.setAttribute('height', rowHeight / 3);
                    rectShape.setAttribute('stroke', 'black');
                    rectShape.setAttribute('fill', 'white');
                    svg.appendChild(rectShape);
                } else if (shape === 'Decision') {
                    const diamond = document.createElementNS(svgNS, 'polygon');
                    const points = [
                        `${center.x},${center.y - rowHeight / 3}`,
                        `${center.x + colWidths[3] / 3},${center.y}`,
                        `${center.x},${center.y + rowHeight / 3}`,
                        `${center.x - colWidths[3] / 3},${center.y}`
                    ].join(' ');
                    diamond.setAttribute('points', points);
                    diamond.setAttribute('stroke', 'black');
                    diamond.setAttribute('fill', 'white');
                    svg.appendChild(diamond);
                }
            }
        }

        // Print SVG
        function PrintSOP() {
    const cover = document.getElementById('resultSOP').outerHTML;

    // Simpan konten asli halaman
    const originalContent = document.body.innerHTML;

    // Ganti konten halaman dengan konten yang ingin dicetak
    document.body.innerHTML = `
        <html>
            <head>
                <style>
                    @page {
                        size: landscape;
                        margin: 0;
                    }

                    body {
                        margin: 0;
                        padding: 0;
                        text-align: center;
                    }

                    svg {
                        max-width: 100%;
                        max-height: 100%;
                    }
                </style>
            </head>
            <body>
                ${cover}
            </body>
        </html>
    `;

    // Cetak halaman
    window.print();

    // Kembalikan konten asli
    document.body.innerHTML = originalContent;
}


    </script>
@endsection
