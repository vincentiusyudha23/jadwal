@props(['name'])

<div class="bg-gray-300 py-2 px-4 d-flex align-items-center justify-content-between">
    <span class="fw-bold fs-5">{{ getCurrentTimeOfDay($name) }}</span>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="las la-user fs-5"></i>
            {{ $name }}
        </button>
        <ul class="dropdown-menu">
            <li>
                <div class="dropdown-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <span onclick="event.preventDefault(); this.closest('form').submit();">Log Out</span>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
<div class="container-fluid px-4">
    {{ $slot }}
</div>
