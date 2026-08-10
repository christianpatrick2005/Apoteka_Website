<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Apoteka</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class=" text-slate-800 antialiased bg-[#fde402]/30 selection:text-slate-900 min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
            Masuk ke Akun Anda
        </h2>
        <p class="mt-2 text-center text-sm ">
            Atau
            <a href="{{ route('MainPage') }}" class="font-medium text-[#284fa0] hover:text-[#eb2128] transition-colors">
                kembali ke halaman utama
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-sm border border-slate-100 sm:rounded-2xl sm:px-10">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">
                        Email
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-[#fde402] focus:border-[#fde402] sm:text-sm transition-colors">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-[#fde402] focus:border-[#fde402] sm:text-sm transition-colors">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-full shadow-sm text-sm font-medium text-slate-900 bg-[#fde402] hover:bg-[#284fa0] hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#fde402] transition-all">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
