@if(Auth::check() && Auth::user()->canSwitchRoles())
@php
    $currentRole = Auth::user()->getCurrentRole();
    $currentRoleInfo = Auth::user()->getRoleInfo($currentRole);
    $availableRoles = Auth::user()->getAvailableRoles();
@endphp

<div class="relative inline-block text-left">
    <!-- Dropdown Button -->
    <div>
        <button type="button"
                class="inline-flex justify-center items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-white/10 hover:bg-white/20 focus:outline-none transition-colors duration-200"
                id="role-menu-button"
                aria-expanded="false"
                aria-haspopup="true"
                onclick="toggleRoleDropdown()">
            <i class="fas {{ $currentRoleInfo['icon'] }} mr-2"></i>
            <span>{{ $currentRoleInfo['name'] }}</span>
            <svg class="-mr-1 ml-2 h-4 w-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" id="dropdown-arrow">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <!-- Dropdown Menu -->
    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none transform opacity-0 scale-95 transition-all duration-200 pointer-events-none z-50"
         id="role-dropdown-menu"
         role="menu"
         aria-orientation="vertical"
         aria-labelledby="role-menu-button">

        <div class="py-1" role="none">
            <!-- Current Role Info -->
            <div class="px-4 py-2 border-b border-gray-200">
                <p class="text-sm text-gray-500">Status Role:</p>
                <div class="flex items-center justify-between mt-1">
                    <div class="flex items-center">
                        <i class="fas {{ $currentRoleInfo['icon'] }} {{ $currentRoleInfo['color'] }} mr-2"></i>
                        <span class="text-sm font-medium text-gray-900">{{ $currentRoleInfo['name'] }}</span>
                    </div>
                    @if(Auth::user()->isUsingSwitchedRole())
                        <span class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded-full">Switched</span>
                    @else
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Original</span>
                    @endif
                </div>

                @if(Auth::user()->isUsingSwitchedRole())
                    <div class="mt-2 text-xs text-gray-500">
                        <span>Role Asli: </span>
                        @php $originalRoleInfo = Auth::user()->getRoleInfo(Auth::user()->getOriginalRole()); @endphp
                        <i class="fas {{ $originalRoleInfo['icon'] }} {{ $originalRoleInfo['color'] }}"></i>
                        <span class="font-medium">{{ $originalRoleInfo['name'] }}</span>
                    </div>
                @endif
            </div>

            <!-- Available Roles -->
            <div class="py-1">
                <p class="px-4 py-2 text-xs text-gray-500 uppercase tracking-wide font-semibold">Beralih ke Role:</p>
                @foreach($availableRoles as $role)
                    @if($role !== $currentRole)
                        @php $roleInfo = Auth::user()->getRoleInfo($role); @endphp
                        <form action="{{ route('role.switch') }}" method="POST" class="inline w-full">
                            @csrf
                            <input type="hidden" name="role" value="{{ $role }}">
                            <button type="submit"
                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left transition-colors duration-150"
                                    role="menuitem">
                                <i class="fas {{ $roleInfo['icon'] }} {{ $roleInfo['color'] }} mr-3 group-hover:scale-110 transition-transform duration-150"></i>
                                <span>{{ $roleInfo['name'] }}</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400 group-hover:text-gray-600 transition-colors duration-150"></i>
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>

            <!-- Reset to Original Role -->
            @if(Auth::user()->isUsingSwitchedRole())
                <div class="border-t border-gray-200">
                    @php $originalRoleInfo = Auth::user()->getRoleInfo(Auth::user()->getOriginalRole()); @endphp
                    <a href="{{ route('role.switch.original') }}"
                       class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors duration-150"
                       role="menuitem">
                        <i class="fas fa-undo {{ $originalRoleInfo['color'] }} mr-3 group-hover:scale-110 transition-transform duration-150"></i>
                        <span>Kembali ke {{ $originalRoleInfo['name'] }}</span>
                        <span class="ml-auto text-xs bg-green-100 text-green-600 px-2 py-1 rounded">Role Asli</span>
                    </a>
                </div>
            @else
                <div class="border-t border-gray-200">
                    <div class="px-4 py-2 text-xs text-gray-500 text-center italic">
                        Anda sedang menggunakan role asli
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleRoleDropdown() {
    const dropdown = document.getElementById('role-dropdown-menu');
    const arrow = document.getElementById('dropdown-arrow');
    const isHidden = dropdown.classList.contains('opacity-0');

    if (isHidden) {
        // Show dropdown
        dropdown.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
        dropdown.classList.add('opacity-100', 'scale-100');
        arrow.classList.add('rotate-180');
    } else {
        // Hide dropdown
        dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        dropdown.classList.remove('opacity-100', 'scale-100');
        arrow.classList.remove('rotate-180');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('role-dropdown-menu');
    const button = document.getElementById('role-menu-button');

    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        dropdown.classList.remove('opacity-100', 'scale-100');
        document.getElementById('dropdown-arrow').classList.remove('rotate-180');
    }
});
</script>
@endif
