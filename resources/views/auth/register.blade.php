<x-guest-card-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="font-headline text-headline-md text-primary mb-2">AantassenaSwimClub</h1>
        <p class="font-body text-body-sm text-on-surface-variant">Buat akun Anda untuk bergabung dengan platform performa elit.</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5">
        @csrf

        <!-- Full Name -->
        <div class="flex flex-col gap-1">
            <label class="text-label-sm text-on-surface" for="name">Nama Lengkap</label>
            <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="John Doe" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div class="flex flex-col gap-1">
            <label class="text-label-sm text-on-surface" for="email">Email</label>
            <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Phone -->
        <div class="flex flex-col gap-1">
            <label class="text-label-sm text-on-surface" for="phone">No. HP</label>
            <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="08xx xxxx xxxx" />
            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <!-- Full Address -->
        <div class="flex flex-col gap-1">
            <label class="text-label-sm text-on-surface" for="address">Alamat Lengkap</label>
            <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="address" name="address" type="text" value="{{ old('address') }}" placeholder="Jl. Contoh No. 12, Kecamatan, Kota" />
            <x-input-error :messages="$errors->get('address')" />
        </div>

        <!-- Passwords -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="flex flex-col gap-1">
                <label class="text-label-sm text-on-surface" for="password">Password</label>
                <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="password" name="password" type="password" placeholder="••••••••" required autocomplete="new-password" />
                <div class="flex gap-1 mt-1">
                    <div class="h-1 w-full bg-error rounded-full opacity-30"></div>
                    <div class="h-1 w-full bg-outline-variant rounded-full"></div>
                    <div class="h-1 w-full bg-outline-variant rounded-full"></div>
                </div>
                <x-input-error :messages="$errors->get('password')" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-label-sm text-on-surface" for="password_confirmation">Konfirmasi Password</label>
                <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <!-- T&C -->
        <div class="flex items-start gap-3 mt-2" x-data="{ readTerms: @js(old('terms') ? true : false) }">
            <div class="flex items-center h-5">
                <input id="terms" name="terms" type="checkbox" value="1"
                       x-bind:disabled="!readTerms"
                       x-bind:class="readTerms ? '' : 'opacity-40 cursor-not-allowed'"
                       class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary bg-white" {{ old('terms') ? 'checked' : '' }} />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-body-sm text-on-surface-variant cursor-pointer" for="terms">
                    Saya telah membaca dan menyetujui <a class="text-primary font-semibold hover:underline" href="#" x-on:click.prevent="readTerms = true; $dispatch('open-modal', 'syarat-ketentuan')">Syarat &amp; Ketentuan</a>.
                </label>
                <p x-show="!readTerms" class="text-label-sm text-outline">Klik "Syarat &amp; Ketentuan" untuk membaca sebelum menyetujui.</p>
            </div>
        </div>
        <x-input-error :messages="$errors->get('terms')" />

        <!-- Submit -->
        <button type="submit" class="w-full bg-primary-container text-white text-label-md py-3 px-6 rounded-lg mt-4 hover:bg-primary transition-colors flex justify-center items-center gap-2 active:scale-[0.98]">
            Daftar Sekarang
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>
    </form>

    <!-- Modal Syarat & Ketentuan -->
    <x-modal name="syarat-ketentuan" maxWidth="2xl">
        <div class="flex flex-col max-h-[85vh]">
            <!-- Header -->
            <div class="flex items-start justify-between gap-4 px-6 py-4 border-b border-outline-variant/30 bg-surface-container-lowest sticky top-0 z-10">
                <div>
                    <h3 class="font-headline text-headline-sm text-on-surface">Syarat &amp; Ketentuan</h3>
                    <p class="font-body-sm text-body-sm text-outline mt-1">Pendaftaran &amp; Kegiatan Latihan Renang AantassenaSwimClub</p>
                </div>
                <button type="button" @click="$dispatch('close-modal', 'syarat-ketentuan')" class="text-outline hover:text-on-surface transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 overflow-y-auto text-body-sm leading-relaxed text-on-surface-variant space-y-5">
                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">A. Ketentuan Umum</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Peserta latihan renang adalah siswa yang telah melakukan pendaftaran dan memenuhi persyaratan yang ditetapkan oleh klub.</li>
                        <li>Orang tua/wali wajib membaca, memahami, dan menyetujui seluruh Syarat dan Ketentuan sebelum mendaftarkan siswa.</li>
                        <li>Pendaftaran dianggap sah setelah data peserta lengkap dan pembayaran sesuai program telah diterima oleh klub.</li>
                        <li>Penempatan siswa dalam kelompok latihan ditentukan berdasarkan usia, kemampuan berenang, hasil observasi, dan pertimbangan pelatih.</li>
                        <li>Klub berhak melakukan evaluasi dan memindahkan siswa ke kelompok latihan yang sesuai dengan perkembangan kemampuan siswa.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">B. Data dan Kondisi Kesehatan Siswa</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Orang tua/wali wajib memberikan informasi yang benar mengenai kondisi kesehatan, riwayat cedera, alergi, maupun kondisi khusus yang dapat memengaruhi aktivitas berenang.</li>
                        <li>Siswa tidak diperkenankan mengikuti latihan apabila sedang mengalami kondisi yang dapat membahayakan diri sendiri maupun peserta lain, seperti demam, penyakit menular, luka terbuka, atau kondisi kesehatan lainnya.</li>
                        <li>Orang tua/wali bertanggung jawab untuk memastikan siswa dalam kondisi layak mengikuti latihan.</li>
                        <li>Apabila siswa memiliki kondisi kesehatan khusus, orang tua/wali wajib berkonsultasi dengan tenaga medis dan memberitahukan rekomendasi yang relevan kepada klub/pelatih.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">C. Pembayaran</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Biaya latihan dibayarkan sesuai paket/program yang dipilih pada saat pendaftaran.</li>
                        <li>Pembayaran yang telah dilakukan tidak dapat dikembalikan (non-refundable), kecuali terdapat kebijakan khusus yang ditetapkan oleh klub.</li>
                        <li>Keterlambatan pembayaran dapat menyebabkan siswa tidak dapat mengikuti sesi latihan sampai kewajiban pembayaran diselesaikan.</li>
                        <li>Biaya latihan tidak termasuk perlengkapan pribadi siswa dan biaya lain di luar program yang telah ditentukan.</li>
                        <li>Perubahan harga atau ketentuan pembayaran akan diinformasikan terlebih dahulu kepada orang tua/wali.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">D. Kehadiran dan Ketidakhadiran</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Siswa diharapkan hadir sesuai jadwal latihan yang telah ditentukan.</li>
                        <li>Orang tua/wali wajib menginformasikan kepada klub/pelatih apabila siswa tidak dapat mengikuti latihan.</li>
                        <li>Ketidakhadiran siswa atas alasan pribadi, kegiatan lain, lupa jadwal, atau alasan lainnya tidak otomatis mendapatkan penggantian sesi.</li>
                        <li>Penggantian sesi hanya dapat diberikan apabila memenuhi ketentuan make-up class yang berlaku di klub.</li>
                        <li>Siswa yang datang terlambat tetap mengikuti latihan sesuai sisa waktu yang tersedia dan tidak mendapatkan tambahan waktu sebagai kompensasi keterlambatan.</li>
                        <li>Klub berhak membatalkan atau memindahkan jadwal latihan apabila terdapat kondisi tertentu, seperti cuaca buruk, kondisi kolam, kegiatan klub, atau keadaan darurat.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">E. Make-Up Class / Penggantian Sesi</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Penggantian sesi hanya dapat dilakukan sesuai kebijakan dan ketersediaan jadwal klub.</li>
                        <li>Pengajuan penggantian sesi wajib disampaikan sebelum jadwal latihan berlangsung.</li>
                        <li>Sesi yang tidak digunakan dalam batas waktu program dapat dinyatakan hangus.</li>
                        <li>Make-up class tidak dapat diuangkan atau dialihkan menjadi bentuk kompensasi lainnya.</li>
                        <li>Klub berhak menentukan jadwal, kelompok, dan pelatih untuk sesi pengganti sesuai dengan ketersediaan.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">F. Peraturan Selama Latihan</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Siswa wajib mengikuti instruksi pelatih selama kegiatan berlangsung.</li>
                        <li>Siswa wajib menjaga ketertiban, kebersihan, dan keamanan lingkungan kolam.</li>
                        <li>Siswa dilarang melakukan tindakan yang dapat membahayakan diri sendiri, pelatih, maupun peserta lain.</li>
                        <li>Siswa wajib menggunakan pakaian dan perlengkapan renang yang sesuai.</li>
                        <li>Siswa wajib menjaga dan bertanggung jawab atas barang pribadi yang dibawa.</li>
                        <li>Penggunaan fasilitas kolam wajib mengikuti peraturan yang ditetapkan oleh pengelola kolam.</li>
                        <li>Siswa yang melakukan tindakan tidak disiplin atau membahayakan dapat diberikan teguran dan/atau tindakan sesuai kebijakan klub.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">G. Tanggung Jawab Orang Tua/Wali</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Orang tua/wali bertanggung jawab atas pengantaran dan penjemputan siswa sesuai jadwal.</li>
                        <li>Orang tua/wali wajib memastikan siswa datang tepat waktu dan membawa perlengkapan latihan.</li>
                        <li>Orang tua/wali tidak diperkenankan mengganggu proses latihan dengan memberikan instruksi langsung kepada siswa selama sesi berlangsung.</li>
                        <li>Komunikasi mengenai program latihan, perkembangan siswa, jadwal, dan administrasi dilakukan melalui kanal komunikasi resmi klub.</li>
                        <li>Orang tua/wali wajib memberikan informasi yang benar dan terbaru terkait data siswa serta kondisi yang dapat memengaruhi proses latihan.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">H. Keamanan dan Tanggung Jawab Klub</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Klub dan pelatih akan berupaya menjalankan kegiatan latihan dengan memperhatikan aspek keselamatan dan prosedur latihan yang sesuai.</li>
                        <li>Pelatih bertanggung jawab memberikan instruksi dan pengawasan selama sesi latihan sesuai dengan kondisi dan program yang diberikan.</li>
                        <li>Aktivitas renang memiliki risiko yang melekat pada olahraga air. Orang tua/wali memahami dan menyetujui bahwa risiko cedera atau kejadian tidak terduga tetap dapat terjadi meskipun prosedur keselamatan telah diterapkan.</li>
                        <li>Klub akan mengambil tindakan pertolongan awal sesuai kemampuan dan prosedur yang tersedia apabila terjadi keadaan darurat selama latihan.</li>
                        <li>Apabila diperlukan, klub dapat meminta bantuan tenaga medis atau fasilitas kesehatan dan akan menghubungi orang tua/wali.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">I. Perlengkapan Siswa</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Siswa wajib membawa perlengkapan latihan yang diperlukan, antara lain: pakaian renang, kacamata renang, topi renang apabila diwajibkan, handuk, perlengkapan mandi, botol minum, serta perlengkapan latihan tambahan sesuai instruksi pelatih.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">J. Dokumentasi dan Publikasi</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Klub dapat melakukan dokumentasi berupa foto atau video selama kegiatan latihan, pertandingan, maupun kegiatan klub.</li>
                        <li>Dokumentasi dapat digunakan untuk kebutuhan informasi, evaluasi, dokumentasi kegiatan, dan promosi klub.</li>
                        <li>Orang tua/wali yang tidak mengizinkan penggunaan dokumentasi siswa untuk keperluan publikasi wajib menyampaikan keberatan kepada pihak klub pada saat pendaftaran.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">K. Disiplin dan Etika</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Setiap siswa wajib menghormati pelatih, staf, teman latihan, pengelola kolam, dan pihak lainnya.</li>
                        <li>Perundungan (bullying), pelecehan, kekerasan, penghinaan, diskriminasi, dan tindakan yang merugikan orang lain tidak diperbolehkan.</li>
                        <li>Pelanggaran terhadap peraturan dapat diberikan teguran, pembinaan, pembatasan mengikuti latihan, hingga penghentian keanggotaan sesuai tingkat pelanggaran.</li>
                        <li>Orang tua/wali diharapkan turut mendukung pembentukan sikap disiplin, sportivitas, tanggung jawab, dan sikap saling menghormati.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">L. Pengunduran Diri</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Pengunduran diri peserta wajib disampaikan oleh orang tua/wali kepada pihak klub.</li>
                        <li>Pengunduran diri tidak otomatis menyebabkan pengembalian biaya latihan yang telah dibayarkan.</li>
                        <li>Sisa sesi latihan tidak dapat dialihkan kepada orang lain tanpa persetujuan klub.</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <h4 class="font-headline text-body-md font-semibold text-on-surface">M. Perubahan Jadwal dan Keadaan Khusus</h4>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Klub dapat melakukan perubahan jadwal latihan karena kondisi kolam, cuaca, kegiatan kompetisi, hari libur, keadaan darurat, atau alasan operasional lainnya.</li>
                        <li>Perubahan jadwal akan diinformasikan melalui kanal komunikasi resmi klub.</li>
                        <li>Klub akan berupaya memberikan jadwal pengganti apabila perubahan berasal dari pihak klub dan memungkinkan untuk dilakukan.</li>
                    </ol>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-outline-variant/30 bg-surface-container-lowest sticky bottom-0 z-10">
                <button type="button" @click="$dispatch('close-modal', 'syarat-ketentuan')"
                        class="w-full inline-flex items-center justify-center gap-2 bg-primary-container text-white text-label-md py-3 px-6 rounded-lg hover:bg-primary transition-colors">
                    Saya Sudah Membaca
                    <span class="material-symbols-outlined text-[18px]">check</span>
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Footer Link -->
    <div class="text-center mt-6 pt-6 border-t border-outline-variant/20">
        <p class="text-body-sm text-on-surface-variant">
            Sudah punya akun? <a class="text-primary font-semibold hover:underline" href="{{ route('login') }}">Masuk</a>
        </p>
    </div>
</x-guest-card-layout>
