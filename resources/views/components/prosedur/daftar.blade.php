<div>
    <p>Daftar Prosedur Pengawasan</p>
    @if (Auth::user()->role == 'perencana')
    <a href="/perencana/daftarprosedurpengawasan/create-cover" class="">Create New Cover</a>
    <br>
    <a href="/perencana/daftarprosedurpengawasan/create" class="">Create New Body</a>
    @endif
</div>
