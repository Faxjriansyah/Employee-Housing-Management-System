<div class="sidebar">
    <div class="p-3 border-bottom">
        <h5 class="mb-0">Mess System</h5>
        <small class="text-secondary">Admin Panel</small>
    </div>

    <nav class="mt-3">
        <a href="{{ route('dashboard') }}">
            📊 Dashboard
        </a>

        <a href="{{ route('employees.index') }}">
            👤 Data Karyawan
        </a>

        <a href="/occupancies">Room Occupancy</a>

        <a href="{{ route('rooms.index') }}">
            🏠 Kamar / Mess
        </a>

        <hr class="bg-secondary mx-3">

        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            🚪 Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </nav>
</div>
