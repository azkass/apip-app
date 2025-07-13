// Utilitas live-search tabel (ringkas)
export default function initLiveSearchTable(inputSel, bodySel, opts = {}) {
    const input = typeof inputSel === 'string' ? document.querySelector(inputSel) : inputSel;
    const tbody = typeof bodySel === 'string' ? document.querySelector(bodySel) : bodySel;
    if (!input || !tbody) return;

    const {
        colIndex = 2,
        colspan = 7,
        rowSelector = 'tr',
        noResultText = 'Tidak ada hasil',
        debounce = 200,
    } = opts;

    // Cache baris & teks
    const rows = [...tbody.querySelectorAll(rowSelector)].map(r => ({
        r,
        t: (r.children[colIndex]?.textContent || '').toLowerCase(),
    }));

    // Baris “tak ada hasil”
    const emptyRow = Object.assign(document.createElement('tr'), {
        innerHTML: `<td colspan="${colspan}" class="border border-gray-300 px-4 py-8 text-center text-gray-500">${noResultText}</td>`,
    });
    emptyRow.style.display = 'none';
    tbody.append(emptyRow);

    const filter = q => {
        const term = q.trim().toLowerCase();
        let i = 0;
        rows.forEach(({ r, t }) => {
            const show = t.includes(term);
            r.style.display = show ? '' : 'none';
            if (show) r.children[0].textContent = ++i;
        });
        emptyRow.style.display = i ? 'none' : '';
    };

    let timer;
    input.addEventListener('input', e => {
        clearTimeout(timer);
        timer = setTimeout(() => filter(e.target.value), debounce);
    });
}
