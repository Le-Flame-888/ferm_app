<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ferm App - Gestion des Équipements</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .hero {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="hero">
        <div class="text-center p-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-6">Bienvenue sur Ferm App</h1>
            <p class="text-xl text-gray-600 mb-8">Gérez facilement vos équipements et ressources</p>
            <div class="space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn-primary inline-block">
                            Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary inline-block">
                            Se connecter
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-block bg-white text-gray-800 font-semibold py-2 px-6 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                                S'inscrire
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</body>
</html>
