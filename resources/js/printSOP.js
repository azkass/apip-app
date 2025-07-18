function setupPrintFunctionality() {
    document
        .getElementById("printSopBtn")
        .addEventListener("click", function () {
            printSopDocument();
        });
}

function printSopDocument() {
    // Clone the containers
    const coverContainer = document
        .getElementById("coverContainer")
        .cloneNode(true);
    const graphContainer = document
        .getElementById("graphContainer")
        .cloneNode(true);

    // Create an iframe instead of new window
    const iframe = document.createElement("iframe");
    iframe.style.display = "none";
    document.body.appendChild(iframe);

    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    iframeDoc.open();
    iframeDoc.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                @page {
                    size: legal landscape;
                    margin: 0;
                    @top-left { content: none; }
                    @top-center { content: none; }
                    @top-right { content: none; }
                    @bottom-left { content: none; }
                    @bottom-center { content: none; }
                    @bottom-right { content: none; }
                }

                @media print {
                    @page {
                        margin: 0;
                        size: legal landscape;
                    }
                    body::before,
                    body::after {
                        display: none !important;
                    }
                }

                body {
                    margin: 0;
                    padding: 0;
                    background-color: #fff;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .print-page {
                    page-break-after: always;
                    width: 100%;
                    min-height: 100vh;
                    box-sizing: border-box;
                    margin: 0;
                    padding: 0;
                    display: block;
                }

                .print-page:last-child {
                    page-break-after: auto;
                }

                svg {
                    max-width: 100%;
                    height: auto;
                }
            </style>
        </head>
        <body>
            <div class="print-page">
                ${coverContainer.innerHTML}
            </div>
            <div class="print-page">
                ${graphContainer.innerHTML}
            </div>
        </body>
        </html>
    `);
    iframeDoc.close();

    // Wait for content to load
    iframe.onload = function () {
        setTimeout(function () {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            document.body.removeChild(iframe); // Clean up
        }, 500);
    };
}

// Make functions available globally
window.setupPrintFunctionality = setupPrintFunctionality;
window.printSopDocument = printSopDocument;
