import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';

/**
 * Generates and downloads a PDF from the specified containers.
 */
function downloadContentAsPdf() {
    const coverContainer = document.getElementById('coverContainer');
    const graphContainer = document.getElementById('graphContainer');

    if (!coverContainer || !graphContainer) {
        console.error('One or both of the required containers for PDF generation were not found.');
        return;
    }

    const options = {
        scale: 2, // Use a higher scale for better resolution
        useCORS: true // This helps with loading cross-origin images, if any
    };

    const pdf = new jsPDF({
        orientation: 'landscape',
        unit: 'pt',
        format: 'legal'
    });

    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = pdf.internal.pageSize.getHeight();

    // First, process the cover container
    html2canvas(coverContainer, options).then(coverCanvas => {
        const coverImgData = coverCanvas.toDataURL('image/png');
        pdf.addImage(coverImgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

        // Add a new page for the graph content
        pdf.addPage();

        // Then, process the graph container
        return html2canvas(graphContainer, options);
    }).then(graphCanvas => {
        const graphImgData = graphCanvas.toDataURL('image/png');
        pdf.addImage(graphImgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

        // Save the generated PDF
        pdf.save('prosedur-pengawasan.pdf');
    }).catch(error => {
        console.error('An error occurred while generating the PDF:', error);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const downloadButton = document.getElementById('downloadPdf');
    if (downloadButton) {
        downloadButton.addEventListener('click', downloadContentAsPdf);
    }
});
