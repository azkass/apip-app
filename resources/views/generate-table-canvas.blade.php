<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Table</title>
</head>
<body>
    <h1>Generated Table</h1>
    <canvas id="resultCanvas" width="800" height="600" style="border:1px solid #000;"></canvas>
    <script>
        const canvas = document.getElementById('resultCanvas');
        const ctx = canvas.getContext('2d');

        const activities = @json($activities);
        const durations = @json($durations);
        const actorRoles = @json($actorRoles);
        const actors = @json($actors);

        // Table Line Drawing
        const startX = 10;
        const startY = 30;
        const rowHeight = 90;
        const colWidths = [30, 200, 100, 100, 100];

        ctx.font = '16px Arial';

        // Header Table
        const headers = ['No', 'Aktivitas', 'Durasi (jam)', 'Aktor 1', 'Aktor 2'];
        let currentX = startX;
        for (let i = 0; i < headers.length; i++) {
            ctx.fillText(headers[i], currentX + 5, startY + 20);
            ctx.strokeRect(currentX, startY, colWidths[i], rowHeight);
            currentX += colWidths[i];
        }

        // Store center points of shapes
        const centers = [];

        // Data Rows
        let currentY = startY + rowHeight;
        for (let i = 0; i < activities.length; i++) {
            currentX = startX;

            // Prepare data for each row
            const row = [
                i + 1,
                activities[i],
                durations[i] + ' jam',
                actorRoles[i] === 'Aktor 1' ? actors[i] : '',
                actorRoles[i] === 'Aktor 2' ? actors[i] : ''
            ];

            // Insert data on cell
            for (let j = 0; j < row.length; j++) {
                ctx.strokeRect(currentX, currentY, colWidths[j], rowHeight);

                if (j === 3 || j === 4) { // Column for Aktor 1 and Aktor 2
                    const shape = row[j];
                    const centerX = currentX + colWidths[j] / 2;
                    const centerY = currentY + rowHeight / 2;

                    if (shape === 'Start' || shape === 'End') {
                        // Draw oval
                        ctx.beginPath();
                        ctx.ellipse(centerX, centerY, colWidths[j] / 3, rowHeight / 3, 0, 0, 2 * Math.PI);
                        ctx.stroke();
                    } else if (shape === 'Process') {
                        // Draw rectangle
                        ctx.strokeRect(centerX - colWidths[j] / 3, centerY - rowHeight / 3, colWidths[j] * 2 / 3, rowHeight * 2 / 3);
                    } else if (shape === 'Decision') {
                        // Draw diamond
                        ctx.beginPath();
                        ctx.moveTo(centerX, centerY - rowHeight / 3);
                        ctx.lineTo(centerX + colWidths[j] / 3, centerY);
                        ctx.lineTo(centerX, centerY + rowHeight / 3);
                        ctx.lineTo(centerX - colWidths[j] / 3, centerY);
                        ctx.closePath();
                        ctx.stroke();
                    }

                    // Store the center point of the shape
                    if (shape) {
                        centers.push({ x: centerX, y: centerY });
                    }

                } else {
                    // Write Normal text
                    ctx.fillText(row[j], currentX + 5, currentY + 20);
                    ctx.strokeRect(currentX, currentY, colWidths[j], rowHeight);
                }
                currentX += colWidths[j];
            }

            currentY += rowHeight;
        }

        // Draw elbow lines connecting shape centers
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        for (let i = 0; i < centers.length - 1; i++) {
            const start = centers[i];
            const end = centers[i + 1];
            const midX = start.x;
            const midY = end.y;

            ctx.beginPath();
            ctx.moveTo(start.x, start.y); // Start point
            ctx.lineTo(midX, start.y);   // Horizontal line
            ctx.lineTo(midX, midY);     // Vertical line
            ctx.lineTo(end.x, end.y);   // Final horizontal line
            ctx.stroke();
        }
    </script>
</body>
</html>
