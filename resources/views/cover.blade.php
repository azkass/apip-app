<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit SVG Text</title>
    <style>
        .editable-text {

        }
        /* Untuk menghilangkan border, padding, dan margin pada input */
        .editable-input {
            border: none;
            outline: none;
            background: transparent;
            position: absolute;
            font-family: inherit; /* Gunakan font yang sama dengan teks */
            font-size: 12px;
        }
        /* Menyembunyikan tombol print */
        .print-btn {
            margin: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <svg xmlns="http://www.w3.org/2000/svg" width="1115" height="791" style="border: 1px solid black;" id="svg-content" xmlns:xlink="http://www.w3.org/1999/xlink">
        <image x="10" y="10" width="100" height="100" xlink:href="sistem-sop.vercel.app/img/Logo BPS.png" alt="Logo BPS"></image>
        <text x="150" y="110" text-anchor="left" dominant-baseline="left" font-size="12px" fill="black" id="editable-text">Edit Me</text>
        <text x="150" y="130" text-anchor="left" dominant-baseline="left" font-size="12px" fill="black" id="editable-text2">Edit Me Too</text>
    </svg>


    <!-- Tombol untuk mencetak SVG -->
    <button class="print-btn" onclick="printSVG()">Print SVG</button>

    <script>
        const setupEditableText = (id) => {
            const textElement = document.getElementById(id);

            textElement.addEventListener('click', () => {
                const bbox = textElement.getBoundingClientRect(); // Mendapatkan dimensi elemen teks
                const fontSize = textElement.getAttribute('font-size'); // Mendapatkan ukuran font

                // Sembunyikan elemen teks
                textElement.style.visibility = 'hidden';

                // Buat elemen input untuk menggantikan teks
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'editable-input';
                input.value = textElement.textContent;

                // Atur posisi dan ukuran input sesuai dengan teks
                input.style.left = `${bbox.left}px`;
                input.style.top = `${bbox.top}px`;
                input.style.width = `${bbox.width}px`;
                // input.style.height = `${bbox.height}px`;
                input.style.fontSize = `${fontSize}px`;
                input.style.textAlign = 'left'; // Sesuaikan dengan properti `text-anchor`

                document.body.appendChild(input);
                input.focus();

                // Simpan perubahan saat kehilangan fokus
                input.addEventListener('blur', () => {
                    if (input.value.trim() !== '') {
                        textElement.textContent = input.value;
                    }
                    document.body.removeChild(input);
                    textElement.style.visibility = 'visible'; // Tampilkan kembali teks asli
                });

                // Simpan perubahan saat tekan Enter
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        input.blur();
                    }
                });
            });
        };

        setupEditableText('editable-text');
        setupEditableText('editable-text2');

        // Fungsi untuk mencetak SVG
        function printSVG() {
            const svgContent = document.getElementById('svg-content').outerHTML;
            const printWindow = window.open('', '', 'width=950,height=650');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print SVG</title>
                        <style>
                            /* Menentukan orientasi landscape untuk print */
                            @page {
                                size: A4 landscape;
                                margin: 0;
                            }

                            /* Atur ukuran konten agar pas di halaman */
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
                        ${svgContent}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>
</html>
