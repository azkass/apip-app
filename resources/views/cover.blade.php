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
            font-size: 14px;
            width: 100px;
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
    <svg xmlns="http://www.w3.org/2000/svg" width="1115" height="791" style="border: 1px solid gray;" id="svg-content" xmlns:xlink="http://www.w3.org/1999/xlink">
        <path fill="none" stroke="black" d="M 50,60 H 1065 V 290 H 50 V 60"  />
        <image x="225" y="80" width="120" height="120" xlink:href="https://sistem-sop.vercel.app/img/Logo%20BPS.png" alt="Logo BPS"></image>
        <text x="135" y="230" text-anchor="left" dominant-baseline="left" font-size="24px" font-weight= "bold" font-style= "italic" fill="black">BADAN PUSAT STATISTIK</text>
        <text x="150" y="260" text-anchor="left" dominant-baseline="left" font-size="24px" font-weight= "bold" fill="black">INSPEKTORAT UTAMA</text>
        <path fill="none" stroke="black" d="M 525,60 V 290" />
        <path fill="none" stroke="black" d="M 690,60 V 290" />

        <text x="540" y="75" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">Nomor SOP</text>
        <text x="700" y="75" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black" id="editable-text1">EditMe</text>
        <path fill="none" stroke="black" d="M 525,79 H 1065" />
        <text x="540" y="92" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">Tanggal Pembuatan</text>
        <text x="700" y="92" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black" id="editable-text2">EditMe</text>
        <path fill="none" stroke="black" d="M 525,96 H 1065" />
        <text x="540" y="109" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">Tanggal Revisi</text>
        <text x="700" y="109" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black" id="editable-text3">EditMe</text>
        <path fill="none" stroke="black" d="M 525,113 H 1065" />
        <text x="540" y="126" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">Tanggal Efektif</text>
        <text x="700" y="126" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black" id="editable-text4">EditMe</text>
        <path fill="none" stroke="black" d="M 525,130 H 1065" />
        <text x="540" y="143" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">Disahkan Oleh</text>
        <text x="700" y="143" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black" id="editable-text5">Plt. Inspektur Utama</text>
        <text x="700" y="225" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black" id="editable-text6">Drs. Akhmad Jaelani, M.Si</text>
        <text x="700" y="240" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black" id="editable-text7">NIP 196306211986011001</text>
        <path fill="none" stroke="black" d="M 525,245 H 1065" />
        <text x="540" y="270" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">Nama SOP</text>
        <text x="700" y="260" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black" id="editable-text8">EditMe</text>
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
                input.style.width = `200px`;

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

        for (let i = 1; i <= 10; i++) {
            setupEditableText(`editable-text${i}`);
        }

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
