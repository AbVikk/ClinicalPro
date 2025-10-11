<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Healthcare System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .navbar {
            background-color: #1e2a38;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }
        .navbar a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }
        .navbar-nav {
            display: flex;
            gap: 0.5rem;
        }
        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #1e2a38;
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }
        .card-body {
            padding: 1.5rem;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: background-color 0.2s;
        }
        .btn-primary {
            background-color: #1e2a38;
            color: white;
        }
        .btn-primary:hover {
            background-color: #334155;
        }
        .alert {
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }
        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.25rem;
            font-family: 'Instrument Sans', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #1e2a38;
            box-shadow: 0 0 0 3px rgba(30, 42, 56, 0.1);
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.75rem;
        }
        .col-md-4, .col-md-6, .col-md-8 {
            padding: 0 0.75rem;
        }
        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        .col-md-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
        .mb-3 {
            margin-bottom: 1rem;
        }
        .text-md-end {
            text-align: right;
        }
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            .col-md-4, .col-md-6, .col-md-8 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 1rem;
            }
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <a href="{{ url('/') }}">{{ config('app.name', 'Healthcare System') }}</a>
        </div>
        <div class="navbar-nav">
            @auth
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    </nav>

    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>