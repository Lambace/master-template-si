<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Master Template SI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h1 class="text-2xl font-bold text-center mb-6">Login Sistem</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border px-3 py-2 rounded-md mb-4">

            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full border px-3 py-2 rounded-md mb-4">

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>