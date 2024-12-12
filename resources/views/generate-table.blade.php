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

        const startX = 10;
        const startY = 10;
        const rowHeight = 90;
        const colWidths = [30, 200, 100, 100, 100];

        // Draw table headers
        const headers = ['No', 'Aktivitas', 'Durasi (jam)', 'Aktor 1', 'Aktor 2'];
        let currentX = startX;
        for (let i = 0; i < headers.length; i++) {
            // Draw header cell
            const rect = document.createElementNS(svgNS, 'rect');
            rect.setAttribute('x', currentX);
            rect.setAttribute('y', startY);
            rect.setAttribute('width', colWidths[i]);
            rect.setAttribute('height', rowHeight);
            rect.setAttribute('stroke', 'black');
            rect.setAttribute('fill', 'none');
            svg.appendChild(rect);

            // Add header text
            const text = document.createElementNS(svgNS, 'text');
            text.setAttribute('x', currentX + 5);
            text.setAttribute('y', startY + 20);
            text.textContent = headers[i];
            svg.appendChild(text);

            currentX += colWidths[i];
        }

        // Store centers of shapes for connection lines
        const centers = [];

        // Draw table rows
        let currentY = startY + rowHeight;
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
                // Draw cell
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

                    if (shape === 'Start' || shape === 'End') {
                        // Draw oval
                        const ellipse = document.createElementNS(svgNS, 'ellipse');
                        ellipse.setAttribute('cx', centerX);
                        ellipse.setAttribute('cy', centerY);
                        ellipse.setAttribute('rx', colWidths[j] / 3);
                        ellipse.setAttribute('ry', rowHeight / 3);
                        ellipse.setAttribute('stroke', 'black');
                        ellipse.setAttribute('fill', 'none');
                        svg.appendChild(ellipse);
                    } else if (shape === 'Process') {
                        // Draw rectangle
                        const rectShape = document.createElementNS(svgNS, 'rect');
                        rectShape.setAttribute('x', centerX - colWidths[j] / 3);
                        rectShape.setAttribute('y', centerY - rowHeight / 3);
                        rectShape.setAttribute('width', (colWidths[j] * 2) / 3);
                        rectShape.setAttribute('height', (rowHeight * 2) / 3);
                        rectShape.setAttribute('stroke', 'black');
                        rectShape.setAttribute('fill', 'none');
                        svg.appendChild(rectShape);
                    } else if (shape === 'Decision') {
                        // Draw diamond
                        const diamond = document.createElementNS(svgNS, 'polygon');
                        const points = [
                            `${centerX},${centerY - rowHeight / 3}`,
                            `${centerX + colWidths[j] / 3},${centerY}`,
                            `${centerX},${centerY + rowHeight / 3}`,
                            `${centerX - colWidths[j] / 3},${centerY}`
                        ].join(' ');
                        diamond.setAttribute('points', points);
                        diamond.setAttribute('stroke', 'black');
                        diamond.setAttribute('fill', 'none');
                        svg.appendChild(diamond);
                    }

                    if (shape) {
                        centers.push({ x: centerX, y: centerY });
                    }

                } else {
                    // Add text
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

        // Draw connection lines between centers
        for (let i = 0; i < centers.length - 1; i++) {
            const start = centers[i];
            const end = centers[i + 1];
            const midX = start.x;
            const midY = end.y;

            const path = document.createElementNS(svgNS, 'path');
            const d = `M ${start.x},${start.y} L ${midX},${start.y} L ${midX},${midY} L ${end.x},${end.y}`;
            path.setAttribute('d', d);
            path.setAttribute('stroke', 'black');
            path.setAttribute('fill', 'none');
            svg.appendChild(path);
        }
    </script>
</body>
</html>
