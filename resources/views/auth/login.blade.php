<x-layouts.auth title="Login">

    <h2 class="text-xl font-semibold text-gray-800 mb-6">Login </h2>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-3 mb-5">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email
            </label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="example@email.com"
                class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                       focus:outline-none focus:ring-2 focus:ring-indigo-400
                       text-gray-800 text-sm @error('email') border-red-400 @enderror"
            >
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                  Password
            </label>
            <input
                type="password"
                name="password"
                placeholder="Enter password"
                class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                       focus:outline-none focus:ring-2 focus:ring-indigo-400
                       text-gray-800 text-sm @error('password') border-red-400 @enderror"
            >
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white
                   font-medium py-2.5 rounded-lg transition-colors text-sm"
        >
           Login
        </button>
    </form>

    {{-- Register link --}}
    <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">
            Create an account
        </a>
    </p>

</x-layouts.auth>