<div class="container p-4">
    @if (Auth::user()->role == 'perencana')
        <a href="/perencana/prosedurpengawasan/create-cover" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm mb-4">Create Cover</a>
        <a href="/perencana/prosedurpengawasan/create" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm">Create Body</a>
    @endif
</div>
