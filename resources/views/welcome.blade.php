<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Antasena Swimming Club - Belajar Renang Bersama Coach Berpengalaman</title>

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
            html {
                scroll-behavior: smooth;
            }
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="bg-background text-on-background font-body text-body-md antialiased selection:bg-primary-container selection:text-on-primary">

        <!-- 1. Navbar -->
        @include('partials.header')

        @php $s = $settings; @endphp

        <!-- 2. Hero Section -->
        <section class="relative pt-32 pb-16 md:pb-24 min-h-[90vh] flex items-center overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <div class="bg-cover bg-center w-full h-full"
                     style="background-image: url('{{ $s['hero_image'] ?? '' }}')"></div>
                <div class="absolute inset-0 bg-primary/80 mix-blend-multiply backdrop-blur-[2px]"></div>
            </div>

            <div class="relative z-10 w-full max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-on-primary space-y-6">
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-on-primary leading-tight">
                        {{ $s['hero_title'] ?? 'Belajar Renang Bersama ' }}<span class="text-orange-lighter">{{ $s['hero_highlight'] ?? 'Coach Berpengalaman' }}</span>
                    </h1>
                    <p class="font-body text-body-lg text-on-primary/90 max-w-xl">
                        {{ $s['hero_subtitle'] ?? 'Program latihan aman, menyenangkan, dan berorientasi pada pencapaian, didampingi secara khusus oleh coach ahli bersertifikat.' }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="{{ route('register') }}"
                            class="bg-orange text-white px-8 py-4 rounded-lg font-body text-label-md text-center hover:bg-orange-light transition-colors shadow-lg shadow-orange/30">
                            {{ $s['hero_cta_primary'] ?? 'Daftar Sekarang' }}
                        </a>
                        <a href="#program"
                            class="bg-surface/10 backdrop-blur-sm border-2 border-on-primary text-on-primary px-8 py-4 rounded-lg font-body text-label-md text-center hover:bg-surface/20 transition-colors">
                            {{ $s['hero_cta_secondary'] ?? 'Lihat Program' }}
                        </a>
                    </div>
                </div>

                <!-- Illustration/Photo -->
                <div class="hidden lg:block relative">
                    <div class="absolute inset-0 bg-orange/20 rounded-[2rem] transform rotate-3 scale-105 z-0 blur-xl"></div>
                    <img class="relative z-10 w-full h-auto rounded-[2rem] shadow-2xl object-cover aspect-[4/3] border-4 border-surface/30"
                         alt="{{ $s['hero_side_image_alt'] ?? 'Anak-anak belajar renang bersama coach ASC' }}"
                         src="{{ $s['hero_side_image'] ?? '' }}">
                </div>
            </div>
        </section>

        <!-- 3. Tentang ASC -->
        <section class="py-16 md:py-24 bg-surface" id="tentang">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6">
                        <h2 class="font-headline text-headline-lg text-primary font-bold">{{ $s['tentang_heading'] ?? 'Tentang Antasena Swimming Club' }}</h2>
                        <p class="text-on-surface-variant font-body text-body-lg">{{ $s['tentang_text'] ?? 'Berdiri sejak tahun 2010, Antasena Swimming Club (ASC) telah mendedikasikan diri untuk mencetak generasi perenang yang tangguh, percaya diri, dan berprestasi. Kami percaya bahwa berenang bukan sekadar olahraga, melainkan keterampilan hidup (life skill) yang esensial.' }}</p>
                        <div class="bg-surface-container-low p-6 rounded-xl border-l-4 border-orange space-y-4">
                            <div>
                                <h3 class="font-headline text-headline-sm text-primary font-semibold">Visi</h3>
                                <p class="text-on-surface-variant text-body-md mt-2">{{ $s['tentang_visi'] ?? 'Menjadi klub renang terbaik yang menginspirasi gaya hidup sehat dan mencetak atlet berprestasi di tingkat nasional maupun internasional.' }}</p>
                            </div>
                            <div>
                                <h3 class="font-headline text-headline-sm text-primary font-semibold">Misi</h3>
                                <ul class="list-disc list-inside text-on-surface-variant text-body-md mt-2 space-y-1">
                                    @foreach (collect(preg_split('/\r\n|\r|\n/', $s['tentang_misi'] ?? ''))->map(fn ($line) => trim($line))->filter() as $misi)
                                        <li>{{ $misi }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <img alt="Kegiatan latihan renang di ASC"
                             class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3]"
                             src="{{ $s['tentang_image'] ?? '' }}">
                        <div class="absolute -bottom-6 -left-6 bg-primary text-on-primary p-6 rounded-xl shadow-lg hidden md:block">
                            <p class="font-headline text-headline-xl text-orange">{{ $s['tentang_years'] ?? '10+' }}</p>
                            <p class="font-body text-label-sm uppercase tracking-wider">{{ $s['tentang_years_label'] ?? 'Tahun Pengalaman' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Keunggulan ASC -->
        <section class="py-16 md:py-24 bg-surface-container-lowest">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Mengapa Memilih ASC?</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Kami memberikan yang terbaik untuk perkembangan dan kenyamanan proses belajar renang Anda.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Card 1 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">sports</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Coach Berpengalaman</h3>
                        <p class="text-on-surface-variant text-body-md">Didampingi oleh pelatih profesional dan bersertifikat yang ahli di bidangnya.</p>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">mood</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Metode Belajar Menyenangkan</h3>
                        <p class="text-on-surface-variant text-body-md">Pendekatan yang ramah dan interaktif membuat proses belajar renang jadi lebih seru.</p>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">pool</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Fasilitas Lengkap</h3>
                        <p class="text-on-surface-variant text-body-md">Kolam renang berstandar dengan fasilitas pendukung yang memadai untuk berlatih.</p>
                    </div>
                    <!-- Card 4 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">health_and_safety</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Aman dan Nyaman</h3>
                        <p class="text-on-surface-variant text-body-md">Prioritas utama pada keselamatan siswa dengan pengawasan ketat selama latihan.</p>
                    </div>
                    <!-- Card 5 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">calendar_month</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Jadwal Fleksibel</h3>
                        <p class="text-on-surface-variant text-body-md">Pilihan waktu latihan yang dapat disesuaikan dengan kesibukan Anda.</p>
                    </div>
                    <!-- Card 6 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">analytics</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">E-Raport</h3>
                        <p class="text-on-surface-variant text-body-md">Pantau perkembangan kemampuan renang secara transparan melalui laporan digital berkala.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Program Kelas -->
        <section class="py-16 md:py-24 bg-surface" id="program">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">{{ $s['program_heading'] ?? 'Program Kelas Kami' }}</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">{{ $s['program_subtitle'] ?? 'Pilih program yang paling sesuai dengan kebutuhan dan target Anda.' }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                    @foreach ($programs as $program)
                        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 shadow-md hover:shadow-xl transition-shadow flex flex-col relative overflow-hidden {{ $program->featured ? 'border-t-4 border-t-primary' : '' }}">
                            @if ($program->badge)
                                <div class="absolute top-0 right-0 bg-orange text-white text-xs font-bold px-3 py-1 rounded-bl-lg">{{ $program->badge }}</div>
                            @endif
                            <div class="mb-4">
                                <h3 class="font-headline text-headline-sm text-primary font-bold">{{ $program->name }}</h3>
                                @if ($program->subtitle)
                                    <p class="text-on-surface-variant text-label-sm mt-1">{{ $program->subtitle }}</p>
                                @endif
                            </div>
                            <div class="mb-6">
                                @if ($program->price)
                                    <span class="font-headline text-headline-lg text-orange font-bold">Rp{{ number_format($program->price, 0, ',', '.') }}</span>
                                    <span class="text-on-surface-variant text-body-md">{{ $program->billing_unit }}</span>
                                @else
                                    <span class="font-headline text-headline-lg text-orange font-bold">Hubungi Kami</span>
                                @endif
                            </div>
                            <ul class="space-y-3 mb-8 flex-grow">
                                @foreach ($program->featureList() as $feature)
                                    <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                        <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('register') }}"
                                class="w-full text-center {{ $program->featured ? 'bg-primary text-on-primary hover:bg-primary-container' : ($program->badge ? 'bg-primary text-on-primary hover:bg-primary-container' : 'bg-outline text-on-primary hover:bg-outline-variant') }} py-2 rounded-lg font-body text-label-md transition-colors mt-auto">
                                {{ $program->button_label ?? 'Pilih Program' }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 6. Jadwal Latihan -->
        <section class="py-16 md:py-24 bg-surface-container-lowest" id="jadwal">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">{{ $s['jadwal_heading'] ?? 'Jadwal Latihan Reguler' }}</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">{{ $s['jadwal_subtitle'] ?? 'Untuk jadwal Private dan Mini Private dapat didiskusikan langsung dengan Coach.' }}</p>
                </div>
                <div class="overflow-x-auto rounded-xl border border-outline-variant/30 shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-primary text-on-primary font-body text-label-md">
                                <th class="p-4 border-b border-outline-variant/30">Hari</th>
                                <th class="p-4 border-b border-outline-variant/30">Jam</th>
                                <th class="p-4 border-b border-outline-variant/30">Program</th>
                                <th class="p-4 border-b border-outline-variant/30">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="text-on-surface-variant">
                            @forelse ($jadwalRows as $row)
                                <tr class="hover:bg-surface-container-low transition-colors {{ $loop->last ? '' : 'border-b border-outline-variant/20' }}">
                                    <td class="p-4 font-semibold text-primary">{{ $row['day'] }}</td>
                                    <td class="p-4">{{ $row['time'] }}</td>
                                    <td class="p-4">{{ $row['program'] }}</td>
                                    <td class="p-4">{{ $row['location'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center">Jadwal belum diatur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- 7. Coach Kami -->
        <section class="py-16 md:py-24 bg-surface">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Temui Coach Kami</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Dilatih langsung oleh para profesional bersertifikat yang berdedikasi tinggi.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @forelse ($coaches as $coach)
                        <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md group">
                            <div class="relative overflow-hidden aspect-[3/4]">
                                @if ($coach->photo_url)
                                    <img alt="{{ $coach->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         src="{{ $coach->photo_url }}">
                                @else
                                    <div class="w-full h-full bg-surface-container flex items-center justify-center">
                                        <span class="material-symbols-outlined text-on-surface-variant text-[64px]">person</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="font-headline text-headline-sm text-primary font-bold">{{ $coach->name }}</h3>
                                <p class="text-orange font-body text-label-md mb-2">{{ $coach->position }}</p>
                                <p class="text-on-surface-variant text-body-sm">{{ $coach->description }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-on-surface-variant">Belum ada coach.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- 8. Galeri -->
        <section class="py-16 md:py-24 bg-surface-container-lowest" id="galeri">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Galeri Kegiatan</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Momen-momen seru dan pencapaian membanggakan siswa-siswi ASC.</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse ($gallery as $image)
                        <a class="block relative group overflow-hidden rounded-xl aspect-square" href="{{ url('/galeri') }}">
                            <img alt="{{ $image->caption ?? 'Galeri ASC' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="{{ $image->image_url }}">
                            <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-primary text-4xl">zoom_in</span>
                            </div>
                        </a>
                    @empty
                        <p class="col-span-full text-center text-on-surface-variant">Belum ada foto galeri.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- 9. Testimoni -->
        <section class="py-16 md:py-24 bg-surface overflow-hidden" x-data="{ currentSlide: 0, slides: [0, 1, 2] }">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop text-center">
                <h2 class="font-headline text-headline-lg text-primary font-bold mb-12">Apa Kata Mereka?</h2>
                <div class="relative w-full max-w-4xl mx-auto">
                    <!-- Slides -->
                    <div class="overflow-hidden relative h-64 md:h-48">
                        <div class="flex transition-transform duration-500 ease-out h-full" x-bind:style="`transform: translateX(-${currentSlide * 100}%)`">
                            <!-- Testimonial 1 -->
                            <div class="w-full flex-shrink-0 px-4 flex flex-col items-center justify-center">
                                <div class="flex text-orange mb-4">
                                    <span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span>
                                </div>
                                <p class="text-on-surface-variant font-body text-body-lg italic mb-6">"Anak saya awalnya sangat takut air. Berkat Coach Siti dari ASC, dalam 2 bulan dia sudah berani menyelam dan belajar gaya bebas. Terima kasih ASC!"</p>
                                <p class="font-headline text-headline-sm text-primary font-semibold">- Ibu Nisa, Orang tua dari Dito (7 tahun)</p>
                            </div>
                            <!-- Testimonial 2 -->
                            <div class="w-full flex-shrink-0 px-4 flex flex-col items-center justify-center">
                                <div class="flex text-orange mb-4">
                                    <span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span>
                                </div>
                                <p class="text-on-surface-variant font-body text-body-lg italic mb-6">"Program kompetitifnya sangat terstruktur. Latihannya disiplin tapi coach-nya tetap supportif. Anak saya berhasil meraih medali di kejuaraan daerah bulan lalu."</p>
                                <p class="font-headline text-headline-sm text-primary font-semibold">- Bapak Rio, Orang tua dari Keiza (12 tahun)</p>
                            </div>
                            <!-- Testimonial 3 -->
                            <div class="w-full flex-shrink-0 px-4 flex flex-col items-center justify-center">
                                <div class="flex text-orange mb-4">
                                    <span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span>
                                </div>
                                <p class="text-on-surface-variant font-body text-body-lg italic mb-6">"Fasilitas kolamnya bersih dan aman. Sistem E-Raport juga sangat membantu saya memantau perkembangan belajar renang anak-anak. Highly recommended!"</p>
                                <p class="font-headline text-headline-sm text-primary font-semibold">- Ibu Sarah, Orang tua dari kembar Ali &amp; Alif</p>
                            </div>
                        </div>
                    </div>
                    <!-- Indicators -->
                    <div class="flex justify-center gap-2 mt-6">
                        <template x-for="slide in slides">
                            <button @click="currentSlide = slide" class="w-3 h-3 rounded-full transition-colors" x-bind:class="currentSlide === slide ? 'bg-primary' : 'bg-outline-variant'"></button>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- 10. FAQ -->
        <section class="py-16 md:py-24 bg-surface-container-lowest" id="faq">
            <div class="max-w-3xl mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Pertanyaan Umum (FAQ)</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4">Jawaban atas beberapa pertanyaan yang sering diajukan.</p>
                </div>
                <div class="space-y-4">
                    <!-- Item 1 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-4 bg-surface text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Bagaimana cara mendaftar di ASC?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-4 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Pendaftaran dapat dilakukan secara online dengan mengklik tombol "Daftar" di website ini, atau Anda bisa datang langsung ke meja pendaftaran kami di Kolam Renang Universitas Lampung pada jam operasional.
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-4 bg-surface text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Mulai usia berapa anak bisa ikut kelas renang?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-4 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Kami menerima siswa mulai dari usia 4 tahun untuk program reguler/mini reguler anak-anak. Untuk usia di bawah 4 tahun, disarankan mengikuti program private dengan pendampingan khusus.
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-4 bg-surface text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Bagaimana sistem pembayaran biayanya?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-4 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Pembayaran dapat dilakukan melalui transfer bank, e-wallet (GoPay, OVO, Dana), atau secara tunai di lokasi pendaftaran. Pembayaran dilakukan di awal sebelum sesi pertama dimulai.
                        </div>
                    </div>
                    <!-- Item 4 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-4 bg-surface text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Apakah biaya sudah termasuk tiket masuk kolam?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-4 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Ya, seluruh biaya program kelas (Private, Reguler, dll) yang tercantum sudah termasuk biaya tiket masuk kolam renang untuk siswa selama sesi latihan berlangsung. Pendamping/orang tua yang masuk area kolam namun tidak berenang mungkin dikenakan tarif masuk reguler kolam renang (bukan dari pihak ASC).
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 11. Call to Action -->
        <section class="py-16 md:py-24 bg-surface">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="bg-primary rounded-3xl p-10 md:p-16 text-center text-on-primary shadow-2xl relative overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary-container rounded-full mix-blend-multiply filter blur-3xl opacity-70 transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange/30 rounded-full mix-blend-overlay filter blur-3xl opacity-50 transform -translate-x-1/2 translate-y-1/2"></div>
                    <div class="relative z-10">
                        <h2 class="font-headline text-headline-lg-mobile md:text-headline-xl font-bold mb-4">Siap Memulai Perjalanan Renang Anda?</h2>
                        <p class="font-body text-body-lg max-w-2xl mx-auto mb-8 text-on-primary/90">Bergabunglah dengan ratusan siswa lainnya yang telah merasakan manfaat belajar renang bersama Antasena Swimming Club. Daftar sekarang dan jadilah perenang tangguh!</p>
                        <a href="{{ route('register') }}"
                            class="inline-block bg-orange text-white px-8 py-4 rounded-xl font-body text-label-md hover:bg-orange-light transition-colors shadow-lg shadow-orange/40 hover:scale-105 transform duration-200">
                            Daftar Sekarang Juga
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 12. Footer -->
        @include('partials.footer')
    </body>
</html>
