<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusLife - {{ $title ?? ''  }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">

        <div class="w-full max-w-md px-4">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-600">FocusLife</h1>
            <p class="text-gray-500 text-sm mt-1">Organize your time, achieve your goals</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            {{ $slot }}
        </div>
    </div>

    
</body>
</html>