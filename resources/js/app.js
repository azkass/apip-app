import "./bootstrap";
// import { Graph, CellStyle, Color } from "@maxgraph/core";
import * as MaxGraph from "@maxgraph/core";

let activityIndex = 0;
let graph;

// Initialize MaxGraph
function initGraph() {
    graph = new Graph({
        container: document.getElementById("graphContainer"),
        panning: true,
        gridEnabled: true,
        connecting: false,
    });

    // Default style for cells
    const defaultVertexStyle = new CellStyle();
    defaultVertexStyle.strokeColor = "#d1d5db";
    defaultVertexStyle.fillColor = "#ffffff";
    graph.getStylesheet().putDefaultVertexStyle(defaultVertexStyle);
}

function addActivity() {
    activityIndex++;
    const newActivity = `
        <div class="activity bg-gray-100 p-4 rounded mt-2" data-index="${activityIndex}">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Aktivitas</label>
                    <input type="text" name="activities[${activityIndex}][name]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">Waktu</label>
                    <input type="text" name="activities[${activityIndex}][time]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
        </div>
    `;
    document
        .getElementById("activitiesContainer")
        .insertAdjacentHTML("beforeend", newActivity);
}

function generateTable() {
    const formData = new FormData(document.getElementById("activityForm"));
    const activities = formData.getAll("activities");

    // Clear previous graph
    graph.clearCells();

    // Create header style
    const headerStyle = new CellStyle();
    headerStyle.fillColor = "#f3f4f6";
    headerStyle.strokeColor = "#374151";
    headerStyle.fontStyle = "bold";

    try {
        // Create header row
        const headers = ["No", "Aktivitas", "Pelaksana", "Waktu", "Output"];
        let x = 0,
            y = 0;
        const colWidth = 150;
        const rowHeight = 40;

        headers.forEach((header, col) => {
            graph.insertVertex({
                position: [x + col * colWidth, y],
                size: [colWidth, rowHeight],
                value: header,
                style: headerStyle,
            });
        });

        y += rowHeight + 10;

        // Create data rows
        activities.forEach((activity, row) => {
            x = 0;
            const rowData = [
                row + 1,
                activity.name,
                "▼",
                activity.time,
                "Output",
            ];

            rowData.forEach((data, col) => {
                graph.insertVertex({
                    position: [x + col * colWidth, y + row * (rowHeight + 10)],
                    size: [colWidth, rowHeight],
                    value: data,
                });
            });
        });

        // Auto-fit view
        graph.fit(20);
    } catch (error) {
        console.error("Error generating table:", error);
    }
}

// Initialize graph when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    initGraph();
});
