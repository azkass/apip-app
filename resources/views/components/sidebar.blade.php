<div class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed left-0 top-0 w-64 h-full bg-gray-800 text-white transform -translate-x-full transition-transform duration-300">
            <div class="p-4 text-lg font-bold">Sidebar</div>
            <ul>
                <li class="p-4 hover:bg-gray-700"><a href="#">Home</a></li>
                <li class="p-4 hover:bg-gray-700"><a href="#">About</a></li>
                <li class="p-4 hover:bg-gray-700"><a href="#">Services</a></li>
                <li class="p-4 hover:bg-gray-700"><a href="#">Contact</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div id="content" class="flex-1 p-4 transition-transform duration-300">
            <button id="toggleButton" class="bg-blue-500 text-white px-4 py-2 rounded transition-transform duration-300">Toggle Sidebar</button>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('toggleButton');
        const content = document.getElementById('content');

        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            content.classList.toggle('ml-64');
        });
    </script>
</div>
