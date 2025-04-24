<div class="container p-4">
    @if (Auth::user()->role == 'perencana')
        <a href="/perencana/prosedurpengawasan/create-cover" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm mb-4">Create Cover</a> <br><br>
        <a href="/perencana/prosedurpengawasan/create-test" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm">Create Test</a> <br><br>
        <a href="/perencana/prosedurpengawasan/create-fix" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm">Create New</a>
    @endif
</div>
