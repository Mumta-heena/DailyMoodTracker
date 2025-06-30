<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Mood Tracker</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* Custom CSS for a slightly more polished look, complementing Tailwind */
            body {
                font-family: 'Instrument Sans', sans-serif;
                background-color: #F5F5F5; /* Primary Background: Light Gray */
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh; /* Ensure it takes full viewport height */
                margin: 0;
            }
            .hero-section {
                background-color: #FFFFFF; /* Surface / Card Background: Pure White */
                border-radius: 1.5rem; /* More rounded corners */
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); /* Deeper, softer shadow */
                padding: 3rem 2rem;
                text-align: center;
                max-width: 600px;
                width: 100%;
                animation: fadeIn 1s ease-out; /* Simple fade-in animation */
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .btn-custom {
                /* Primary Accent: Soft Teal (#A2D9CE) and Pale Sky Blue (#B3E0FF) */
                background-image: linear-gradient(to right, #A2D9CE 0%, #B3E0FF 51%, #A2D9CE 100%);
                margin: 10px;
                padding: 15px 45px;
                text-align: center;
                text-transform: uppercase;
                transition: 0.5s;
                background-size: 200% auto;
                color: #333333; /* Primary Text: Charcoal Gray */           
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                display: inline-block;
                text-decoration: none; /* Ensure no underline */
                font-weight: 600;
            }

            .btn-custom:hover {
                background-position: right center; /* change the direction of the change here */
                color: #333333; /* Keep primary text color */
                text-decoration: none;
                transform: translateY(-3px); /* Subtle lift on hover */
            }

            .btn-outline-custom {
                background-color: transparent;
                border: 2px solid #A2D9CE; /* Primary Accent: Soft Teal */
                color: #A2D9CE; /* Primary Accent: Soft Teal */
                margin: 10px;
                padding: 15px 45px;
                text-align: center;
                text-transform: uppercase;
                transition: 0.3s;
                border-radius: 10px;
                display: inline-block;
                text-decoration: none;
                font-weight: 600;
            }

            .btn-outline-custom:hover {
                background-color: #A2D9CE; /* Primary Accent: Soft Teal */
                color: #FFFFFF; /* Pure White */
                text-decoration: none;
                transform: translateY(-3px);
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="hero-section">
            <h1 class="text-5xl font-extrabold mb-6">
                <span class="text-[#A2D9CE]">Track</span> <span class="font-extrabold text-[#333333]">Your Moods</span>
            </h1>
            <p class="text-lg text-[#777777] mb-8 leading-relaxed">
                Understand your emotional well-being with our simple and insightful mood tracker. Log your feelings daily,
                track your streaks, and visualize your emotional patterns over time.
            </p>

            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-custom">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-custom">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-outline-custom">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <div class="mt-10 text-sm text-[#777777]">
                A project by Mumtaheena Binte Ahmed
            </div>
        </div>
    </body>
</html>
