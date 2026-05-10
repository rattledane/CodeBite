<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CodeBite') }} — Login</title>
    <meta name="description" content="Login to CodeBite — the educational coding game platform for high school students.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --neo-yellow: #FFE500;
            --neo-black: #1a1a1a;
            --neo-white: #ffffff;
            --neo-border: 3px solid var(--neo-black);
            --neo-shadow: 6px 6px 0px var(--neo-black);
            --neo-shadow-sm: 4px 4px 0px var(--neo-black);
            --neo-shadow-hover: 8px 8px 0px var(--neo-black);
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: var(--neo-yellow);
            margin: 0;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Floating decorative code blocks */
        .floating-block {
            position: absolute;
            background: var(--neo-white);
            border: var(--neo-border);
            box-shadow: var(--neo-shadow-sm);
            padding: 10px 16px;
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            color: var(--neo-black);
            user-select: none;
            pointer-events: none;
        }

        .floating-block.pink { background: #FF6B9D; color: var(--neo-white); }
        .floating-block.blue { background: #4ECDC4; }
        .floating-block.purple { background: #A855F7; color: var(--neo-white); }
        .floating-block.orange { background: #FF8C42; }
        .floating-block.green { background: #2ECC71; }

        /* Login card */
        .neo-card {
            background: var(--neo-white);
            border: var(--neo-border);
            box-shadow: var(--neo-shadow);
            border-radius: 0px;
            position: relative;
            z-index: 10;
        }

        /* Google button */
        .neo-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: var(--neo-white);
            border: var(--neo-border);
            box-shadow: var(--neo-shadow-sm);
            border-radius: 0px;
            padding: 14px 28px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--neo-black);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
            width: 100%;
        }

        .neo-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: var(--neo-shadow-hover);
        }

        .neo-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--neo-black);
        }

        /* Logo */
        .logo-text {
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 48px;
            letter-spacing: -2px;
            color: var(--neo-black);
            line-height: 1;
        }

        .logo-bite {
            background: var(--neo-yellow);
            padding: 2px 8px;
            border: var(--neo-border);
            display: inline-block;
        }

        /* Tagline */
        .tagline {
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            font-weight: 400;
            color: #555;
            letter-spacing: 0.5px;
        }

        /* Divider */
        .neo-divider {
            height: 3px;
            background: var(--neo-black);
            width: 100%;
        }

        /* Badge */
        .neo-badge {
            display: inline-block;
            background: var(--neo-yellow);
            border: 2px solid var(--neo-black);
            padding: 4px 12px;
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Pulse dot */
        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #2ECC71;
            border: 2px solid var(--neo-black);
            display: inline-block;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(var(--rotate, 0deg)); }
            50% { transform: translateY(-12px) rotate(var(--rotate, 0deg)); }
        }

        .float-1 { animation: float 5s ease-in-out infinite; --rotate: -6deg; }
        .float-2 { animation: float 4s ease-in-out 0.5s infinite; --rotate: 4deg; }
        .float-3 { animation: float 6s ease-in-out 1s infinite; --rotate: -3deg; }
        .float-4 { animation: float 4.5s ease-in-out 1.5s infinite; --rotate: 7deg; }
        .float-5 { animation: float 5.5s ease-in-out 0.8s infinite; --rotate: -5deg; }
        .float-6 { animation: float 3.8s ease-in-out 2s infinite; --rotate: 3deg; }

        /* Error alert */
        .neo-alert {
            background: #FF6B6B;
            border: var(--neo-border);
            box-shadow: var(--neo-shadow-sm);
            padding: 12px 16px;
            font-weight: 600;
            color: var(--neo-white);
        }
    </style>
</head>
<body x-data="{ loading: false }">

    <!-- Floating decorative blocks -->
    <div class="floating-block pink float-1" style="top: 8%; left: 5%;">if (true) {</div>
    <div class="floating-block blue float-2" style="top: 15%; right: 8%;">console.log("🚀")</div>
    <div class="floating-block float-3" style="bottom: 20%; left: 8%;">return 42;</div>
    <div class="floating-block purple float-4" style="top: 40%; right: 5%;">while (learning)</div>
    <div class="floating-block orange float-5" style="bottom: 12%; right: 12%;">const x = [];</div>
    <div class="floating-block green float-6" style="top: 60%; left: 4%;">// level up!</div>

    <!-- Main container -->
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md">

            <!-- Login card -->
            <div class="neo-card">

                <!-- Card header -->
                <div class="px-10 pt-10 pb-6 text-center">
                    <!-- Logo -->
                    <div class="logo-text mb-4">
                        Code<span class="logo-bite">Bite</span>
                    </div>

                    <!-- Tagline -->
                    <p class="tagline">Learn to code, one bite at a time</p>
                </div>

                <!-- Divider -->
                <div class="neo-divider"></div>

                <!-- Card body -->
                <div class="px-10 py-8">

                    <!-- Error message -->
                    @if(session('error'))
                        <div class="neo-alert mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Description -->
                    <p class="text-center mb-6" style="font-size: 15px; color: #333; font-weight: 500;">
                        Masuk menggunakan akun Google sekolahmu untuk mulai bermain dan belajar coding.
                    </p>

                    <!-- Google login button -->
                    <a
                        href="{{ route('auth.google') }}"
                        class="neo-btn"
                        id="google-login-btn"
                        x-on:click="loading = true"
                    >
                        <template x-if="!loading">
                            <span class="flex items-center gap-3">
                                <!-- Google Icon -->
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Login dengan Google
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center gap-3">
                                <!-- Loading spinner -->
                                <svg class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="10" stroke="#1a1a1a" stroke-width="3" stroke-dasharray="31.4 31.4" stroke-linecap="round"/>
                                </svg>
                                Menghubungkan...
                            </span>
                        </template>
                    </a>
                </div>

                <!-- Divider -->
                <div class="neo-divider"></div>

                <!-- Card footer -->
                <div class="px-10 py-5 flex items-center justify-between">
                    <div class="neo-badge">
                        <span class="pulse-dot mr-1"></span> Beta
                    </div>
                    <span style="font-family: 'Space Mono', monospace; font-size: 12px; font-weight: 700; color: #888;">
                        v1.0.0
                    </span>
                </div>
            </div>

            <!-- Bottom text -->
            <p class="text-center mt-6" style="font-family: 'Space Mono', monospace; font-size: 12px; font-weight: 700; color: var(--neo-black);">
                &copy; {{ date('Y') }} CodeBite &mdash; Built for students who dare to code.
            </p>
        </div>
    </div>

</body>
</html>
