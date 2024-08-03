<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LinkVault') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .bg-gradient-light { background: linear-gradient(135deg, #f6f8fb 0%, #e5ebf4 100%); }
        .bg-gradient-dark { background: linear-gradient(135deg, #1f2937 0%, #111827 100%); }
        .text-shadow { text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-center" 
      x-data="{ darkMode: false }" 
      :class="{ 'bg-gradient-light text-gray-900': !darkMode, 'bg-gradient-dark text-white': darkMode }">
    <div class="absolute top-0 right-0 mt-4 mr-4">
        <button @click="darkMode = !darkMode" class="p-2 rounded-full transition-colors duration-200" :class="{ 'bg-gray-200 hover:bg-gray-300': !darkMode, 'bg-gray-700 hover:bg-gray-600': darkMode }">
            <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg x-show="darkMode" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold mb-4 text-shadow" :class="{ 'text-gray-900': !darkMode, 'text-white': darkMode }">
                Link<span class="text-indigo-600">Vault</span>
            </h1>
            <p class="text-xl" :class="{ 'text-gray-600': !darkMode, 'text-gray-300': darkMode }">
                Secure, Fast, and Efficient Link Management
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-shadow duration-300 hover:shadow-xl" :class="{ 'bg-white': !darkMode, 'bg-gray-800': darkMode }">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <h2 class="ml-4 text-xl font-semibold">Lightning Fast</h2>
                    </div>
                    <p class="text-sm" :class="{ 'text-gray-600': !darkMode, 'text-gray-400': darkMode }">
                        Generate, manage, and track your links with unprecedented speed. Our system is designed for efficiency, allowing you to focus on what matters most.
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-shadow duration-300 hover:shadow-xl" :class="{ 'bg-white': !darkMode, 'bg-gray-800': darkMode }">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <h2 class="ml-4 text-xl font-semibold">Secure & Reliable</h2>
                    </div>
                    <p class="text-sm" :class="{ 'text-gray-600': !darkMode, 'text-gray-400': darkMode }">
                        Rest easy knowing your link data is protected with industry-standard security measures. Our system ensures your information remains confidential and accessible only to authorized personnel.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-center mb-16">
            @auth
                <a href="{{ url('/dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 shadow-lg">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg mr-4 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 shadow-lg">
                    Log in
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 shadow-lg">
                        Register
                    </a>
                @endif
            @endauth
        </div>

        <div class="text-center text-sm" :class="{ 'text-gray-500': !darkMode, 'text-gray-400': darkMode }">
            LinkVault v{{ config('app.version', '1.0') }} | &copy; {{ date('Y') }} 
        </div>
    </div>
</body>
</html>