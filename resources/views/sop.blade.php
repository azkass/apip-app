<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit SVG Text</title>
    <style>
        /* Tombol print */
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
    {{-- SOP --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="296mm" height="208mm" style="border: 1px solid gray;" id="svg-sop" xmlns:xlink="http://www.w3.org/1999/xlink">
        <path fill="none" stroke="black" d="M 50,60 H 1065 V 741 H 50 V 60" />
        <text x="540" y="270" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">Nama SOP</text>
    </svg>

    <!-- Tombol untuk mencetak SOP -->
    <button class="print-btn" style="position: absolute; top: 10px;" onclick="PrintSOP()">Print SOP</button>

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

        for (let i = 1; i <= 20; i++) {
            setupEditableText(`editable-text${i}`);
        }

        function PrintSOP() {
    const sop = document.getElementById('svg-sop').outerHTML;

    // Simpan konten asli halaman
    const originalContent = document.body.innerHTML;

    // Ganti konten halaman dengan konten yang ingin dicetak
    document.body.innerHTML = `
        <html>
            <head>
                <style>
                    @page {
                        size: A4 landscape;
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
                ${sop}
            </body>
        </html>
    `;

    // Cetak halaman
    window.print();

    // Kembalikan konten asli
    document.body.innerHTML = originalContent;
}
    </script>
</body>
</html>
