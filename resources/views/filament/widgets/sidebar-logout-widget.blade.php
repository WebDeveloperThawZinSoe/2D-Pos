<div class="px-4 py-2">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-gray-100 rounded-lg">
            Logout
        </button>
    </form>
</div>
