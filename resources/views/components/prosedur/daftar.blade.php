<div class="container p-8">
    @if (Auth::user()->role == 'perencana')
        <a href="/perencana/prosedurpengawasan/create-cover-new" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm mb-4">Create Cover Testing Code</a> <br><br>
        <a href="/perencana/prosedurpengawasan/create-cover" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm mb-4">Create Cover Fix Code</a> <br><br>
        <a href="/perencana/prosedurpengawasan/create-test" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm">Create Body Testing Code</a> <br><br>
        <a href="/perencana/prosedurpengawasan/create-fix" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm">Create Body Fix Code</a>
    @endif
</div>
