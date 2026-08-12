<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Program Pelatihan - Antasena Swimming Club</title>

        <!-- Fonts: Montserrat, Inter & Material Symbols -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Montserrat:wght@100..900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            }
            .font-body {
                font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            }
            .font-headline {
                font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif;
            }
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
            .material-symbols-outlined.filled {
                font-variation-settings: 'FILL' 1;
            }
            .pool-shadow {
                box-shadow: 0 10px 25px -5px rgba(0, 71, 169, 0.08);
            }
            html {
                scroll-behavior: smooth;
            }
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="bg-background text-on-background font-body text-body-md antialiased min-h-screen flex flex-col pt-24">

        <!-- Navbar -->
        @include('partials.header')

        <!-- Main Content -->
        <main class="flex-grow pb-16 md:pb-24 px-margin_mobile md:px-margin_desktop max-w-container_max_width mx-auto w-full">
            <section class="text-center mb-12">
                <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-4">{{ $settings['program_heading'] }}</h1>
                <p class="font-body text-body-lg text-on-surface-variant max-w-2xl mx-auto">{{ $settings['program_subtitle'] }}</p>
            </section>

            <!-- Programs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($programs as $program)
                    <div class="bg-surface rounded-xl p-6 pool-shadow border border-outline/10 flex flex-col hover:-translate-y-1 transition-transform duration-300 {{ $program->featured ? 'md:col-span-2 lg:col-span-2 relative overflow-hidden' : 'relative overflow-hidden' }}">
                        @if ($program->badge)
                            <div class="absolute top-0 right-0 bg-orange text-white px-3 py-1 rounded-bl-lg font-body text-label-md text-[12px]">{{ $program->badge }}</div>
                        @endif
                        <div class="mb-4 {{ $program->featured ? 'mt-2' : '' }}">
                            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-4 text-primary">
                                <span class="material-symbols-outlined filled">{{ $program->featured ? 'emoji_events' : 'school' }}</span>
                            </div>
                            <h2 class="font-headline text-headline-sm text-primary mb-2">{{ $program->name }}</h2>
                            <p class="font-body text-label-md text-on-surface-variant mb-1">{{ $program->subtitle }}</p>
                            <p class="font-headline text-headline-md text-orange mb-4">Rp{{ number_format($program->price, 0, ',', '.') }}<span class="font-body text-body-md text-on-surface-variant"> {{ $program->billing_unit }}</span></p>
                        </div>
                        <div class="flex-grow">
                            <ul class="space-y-3 mb-6 text-on-surface-variant">
                                @foreach ($program->featureList() as $feature)
                                    <li class="flex items-start gap-2">
                                        <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <a href="{{ route('register') }}"
                            class="w-full text-center {{ $program->featured ? 'bg-primary text-on-primary hover:bg-primary-container' : 'border-2 border-primary text-primary hover:bg-primary hover:text-on-primary' }} py-3 rounded-lg font-body text-label-md transition-colors mt-auto">
                            {{ $program->button_label }}
                        </a>
                    </div>
                @empty
                    <p class="col-span-full text-center text-on-surface-variant">Belum ada program.</p>
                @endforelse
            </div>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
