<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Table</title>
</head>
<body>
    <h1>Generated Table</h1>
    <svg id="resultSvg" width="800" height="600" xmlns="http://www.w3.org/2000/svg" style="border:1px solid #000;"></svg>
    <script>
        const svgNS = "http://www.w3.org/2000/svg";
        const svg = document.getElementById('resultSvg');

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
        const headers = ['No', 'Aktivitas', 'Durasi (jam)', 'Aktor 1', 'Aktor 2'];
        let currentX = startX;
        for (let i = 0; i < headers.length; i++) {
            const rect = document.createElementNS(svgNS, 'rect');
            rect.setAttribute('x', currentX);
            rect.setAttribute('y', startY);
            rect.setAttribute('width', colWidths[i]);
            rect.setAttribute('height', headersHeight);
            rect.setAttribute('stroke', 'black');
            rect.setAttribute('fill', 'none');
            svg.appendChild(rect);

            const text = document.createElementNS(svgNS, 'text');
            text.setAttribute('x', currentX + 5);
            text.setAttribute('y', startY + 20);
            text.textContent = headers[i];
            svg.appendChild(text);

            currentX += colWidths[i];
        }

        // Write Column Numbers

        const centers = [];

        // Write table content
        let currentY = startY + headersHeight;
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
                    text.setAttribute('y', currentY + 20);
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
    </script>
</body>
</html>
