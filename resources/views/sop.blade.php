<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print SOP</title>
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
    <svg xmlns="http://www.w3.org/2000/svg" width="1115" height="788" style="border: 1px solid gray;" id="svg-sop" xmlns:xlink="http://www.w3.org/1999/xlink">
        <path fill="none" stroke="black" d="M 50,60 H 1065 V 741 H 50 V 60" />
        <text x="65" y="100" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">No</text>
        <path fill="none" stroke="black" d="M 100,60 V 741" />
        <text x="200" y="100" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Aktivitas</text>
        <path fill="none" stroke="black" d="M 350,60 V 741" />
        <text x="500" y="75" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Pelaksana</text>
        <path fill="none" stroke="black" d="M 350,80 H 685" />
        <text x="370" y="100" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Inspektur</text>
        <text x="375" y="115" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Wilayah</text>
        <path fill="none" stroke="black" d="M 450,80 V 741" />
        <text x="500" y="95" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Tim Audit</text>
        <path fill="none" stroke="black" d="M 450,100 H 616" />
        <text x="460" y="115" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Pengendali</text>
        <text x="470" y="130" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Teknis</text>
        <path fill="none" stroke="black" d="M 535,100 V 741" />
        <text x="555" y="115" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Ketua</text>
        <text x="560" y="130" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Tim</text>
        {{-- <text x="500" y="75" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Anggota Tim</text> --}}
        <path fill="none" stroke="black" d="M 615,80 V 741" />
        <text x="620" y="100" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Inspektur</text>
        <text x="625" y="115" text-anchor="left" dominant-baseline="left" font-size="14px" font-weight="bold" fill="black">Wilayah</text>

        <path fill="none" stroke="black" d="M 685,60 V 741" />

        <path fill="none" stroke="black" d="M 1015,60 V 741" />
        <path fill="none" stroke="black" d="M 50,135 H 1065" />
        <path fill="none" stroke="black" d="M 50,150 H 1065" />
        <text x="70" y="180" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">1</text>
        <text x="110" y="170" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">Merima surat permintaan reviu dan </text>
        <text x="110" y="185" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">menerbitkan surat tugas</text>
        <path fill="none" stroke="black" d="M 50,205 H 1065" />
        <image x="375" y="165" width="50" xlink:href="https://upload.wikimedia.org/wikipedia/commons/a/ad/Flowchart_Terminal.svg" alt="Logo BPS"></image>
        <path fill="none" stroke="black" d="M 425,175 H 495 V 220" />
        <text x="70" y="230" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">2</text>
        <text x="110" y="225" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">Menetapkan ruang lingkup dan menyusun</text>
        <text x="110" y="240" text-anchor="left" dominant-baseline="left" font-size="14px" fill="black">konsep surat tugas </text>
        <path fill="none" stroke="black" d="M 460,220 H 525 V 250 H 460 V 220" />
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
