<?php

namespace Database\Seeders;

use App\Models\LandingCoach;
use App\Models\LandingGalleryImage;
use App\Models\LandingProgram;
use App\Models\LandingSetting;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hero
            'hero_title' => 'Belajar Renang Bersama ',
            'hero_highlight' => 'Coach Berpengalaman',
            'hero_subtitle' => 'Program latihan aman, menyenangkan, dan berorientasi pada pencapaian, didampingi secara khusus oleh coach ahli bersertifikat.',
            'hero_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD4cneCRj7gVWVu5MoJSEhNOT2StGeVi4mEs0NzDt4j1OYkICwUtTlG34CCrhjYMwIpfst55mmAikPMfxcDcK6d1lRiMN1T1Z19N1fj_uIOsnkK5bjOOdNzAx-E1lGEXFF4kLGImmMHVkKKapB5CWSRcKt01UrlKnVgjTstB46ckJtLbIg0rKS4nFkTtJgn1dK5saaGvQn-0xCmi6IRQUth5XPAT4TK1sYC-fTnSwI5ZIjbNPZA_jgn',
            'hero_side_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCwN_z28IRGM_zCNn-d4plYCjWDys2KEkgg-mLNuv7eSWtkTBNKbHWlgXM30wsMxpc6WaGOeAjJ1zshXMZKwbYoo1OJKE0pBoFk0CidghkeUEoTZmyW49KD-aaF-oulIWzmxHFG2eT0xk5OaWjY9527tTyD4LMsL5OxgjVMvV62xAP4d7SSRiP4DUM54m72iBDRQZilVq5VVA1-yQXenm9TCxm62ccbOiqIhbSKeZSh9oIG3KJoC3ES',
            'hero_side_image_alt' => 'Anak-anak belajar renang bersama coach ASC',
            'hero_cta_primary' => 'Daftar Sekarang',
            'hero_cta_secondary' => 'Lihat Program',

            // Tentang
            'tentang_heading' => 'Tentang AantassenaSwimClub',
            'tentang_text' => 'Berdiri sejak tahun 2010, AantassenaSwimClub (ASC) telah mendedikasikan diri untuk mencetak generasi perenang yang tangguh, percaya diri, dan berprestasi. Kami percaya bahwa berenang bukan sekadar olahraga, melainkan keterampilan hidup (life skill) yang esensial.',
            'tentang_visi' => 'Menjadi klub renang terbaik yang menginspirasi gaya hidup sehat dan mencetak atlet berprestasi di tingkat nasional maupun internasional.',
            'tentang_misi' => "Menyediakan metode pelatihan yang aman, terstruktur, dan menyenangkan.\nMengembangkan potensi setiap individu melalui pendekatan personal.\nMenumbuhkan karakter disiplin, sportivitas, dan pantang menyerah.",
            'tentang_years' => '10+',
            'tentang_years_label' => 'Tahun Pengalaman',
            'tentang_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmiadFbEY1R71f-xV10Cxoiqukf6VBfRXqj7WAZDt-9S6Qsaz14Ulc-fu2uW0Musp9wb2TDiJAnbNlef8AaXiOgPpxm8_YD4j-2Q2M_DCiQw4xeQVj4ZJ_loKzBmTJdlo7aKKbvLKtMznAVcrjHc8KykjKgfGvluY1i6IhJlR8AH0AaM-RitVo7ZINwDDQFJf99rWANhjcTy3ywYqqXGoy8sdBD396_dEBeG7v-MPlOLBzu9KEVlrz',

            // Program
            'program_heading' => 'Program Kelas Kami',
            'program_subtitle' => 'Pilih program yang paling sesuai dengan kebutuhan dan target Anda.',

            // Galeri
            'galeri_heading' => 'Galeri Kegiatan',
            'galeri_subtitle' => 'Momen-momen seru dan pencapaian membanggakan siswa-siswi ASC.',

            // Jadwal Latihan Reguler
            'jadwal_heading' => 'Jadwal Latihan Reguler',
            'jadwal_subtitle' => 'Untuk jadwal Private dan Mini Private dapat didiskusikan langsung dengan Coach.',
            'jadwal_reguler' => json_encode([
                ['day' => 'Senin & Rabu', 'time' => '15:30 - 17:00', 'program' => 'Reguler Pemula & Lanjutan', 'location' => 'Kolam Renang Universitas Lampung'],
                ['day' => 'Selasa & Kamis', 'time' => '16:00 - 18:00', 'program' => 'Kompetitif (Atlet)', 'location' => 'Kolam Renang Universitas Lampung'],
                ['day' => 'Jumat', 'time' => '15:00 - 16:30', 'program' => 'Mini Reguler', 'location' => 'Kolam Renang Universitas Lampung'],
                ['day' => 'Sabtu & Minggu', 'time' => '07:00 - 09:00', 'program' => 'Semua Kelas Reguler', 'location' => 'Kolam Renang Universitas Lampung'],
            ], JSON_UNESCAPED_UNICODE),

            // Kontak
            'kontak_email' => 'gilangaudiokorgiepangestu@gmail.com',
            'kontak_instagram' => 'https://www.instagram.com/asc_lampung/',
            'kontak_instagram_handle' => '@asc_lampung',
            'kontak_maps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.3473943868075!2d105.23627687474358!3d-5.363862494614921!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40c54bbc12a533%3A0xf38f052a38ab7537!2sKolam%20Renang%20Universitas%20Lampung!5e0!3m2!1sid!2sid!4v1786456619529!5m2!1sid!2sid',
            'kontak_hours_weekday' => 'Senin – Jumat: 08.00 – 20.00',
            'kontak_hours_weekend' => 'Sabtu – Minggu: 07.00 – 18.00',

            // Syarat & Ketentuan
            'syarat_ketentuan' => <<<'TEXT'
A. Ketentuan Umum
1. Peserta latihan renang adalah siswa yang telah melakukan pendaftaran dan memenuhi persyaratan yang ditetapkan oleh klub.
2. Orang tua/wali wajib membaca, memahami, dan menyetujui seluruh Syarat dan Ketentuan sebelum mendaftarkan siswa.
3. Pendaftaran dianggap sah setelah data peserta lengkap dan pembayaran sesuai program telah diterima oleh klub.
4. Penempatan siswa dalam kelompok latihan ditentukan berdasarkan usia, kemampuan berenang, hasil observasi, dan pertimbangan pelatih.
5. Klub berhak melakukan evaluasi dan memindahkan siswa ke kelompok latihan yang sesuai dengan perkembangan kemampuan siswa.

B. Data dan Kondisi Kesehatan Siswa
1. Orang tua/wali wajib memberikan informasi yang benar mengenai kondisi kesehatan, riwayat cedera, alergi, maupun kondisi khusus yang dapat memengaruhi aktivitas berenang.
2. Siswa tidak diperkenankan mengikuti latihan apabila sedang mengalami kondisi yang dapat membahayakan diri sendiri maupun peserta lain, seperti demam, penyakit menular, luka terbuka, atau kondisi kesehatan lainnya.
3. Orang tua/wali bertanggung jawab untuk memastikan siswa dalam kondisi layak mengikuti latihan.
4. Apabila siswa memiliki kondisi kesehatan khusus, orang tua/wali wajib berkonsultasi dengan tenaga medis dan memberitahukan rekomendasi yang relevan kepada klub/pelatih.

C. Pembayaran
1. Biaya latihan dibayarkan sesuai paket/program yang dipilih pada saat pendaftaran.
2. Pembayaran yang telah dilakukan tidak dapat dikembalikan (non-refundable), kecuali terdapat kebijakan khusus yang ditetapkan oleh klub.
3. Keterlambatan pembayaran dapat menyebabkan siswa tidak dapat mengikuti sesi latihan sampai kewajiban pembayaran diselesaikan.
4. Biaya latihan tidak termasuk perlengkapan pribadi siswa dan biaya lain di luar program yang telah ditentukan.
5. Perubahan harga atau ketentuan pembayaran akan diinformasikan terlebih dahulu kepada orang tua/wali.

D. Kehadiran dan Ketidakhadiran
1. Siswa diharapkan hadir sesuai jadwal latihan yang telah ditentukan.
2. Orang tua/wali wajib menginformasikan kepada klub/pelatih apabila siswa tidak dapat mengikuti latihan.
3. Ketidakhadiran siswa atas alasan pribadi, kegiatan lain, lupa jadwal, atau alasan lainnya tidak otomatis mendapatkan penggantian sesi.
4. Penggantian sesi hanya dapat diberikan apabila memenuhi ketentuan make-up class yang berlaku di klub.
5. Siswa yang datang terlambat tetap mengikuti latihan sesuai sisa waktu yang tersedia dan tidak mendapatkan tambahan waktu sebagai kompensasi keterlambatan.
6. Klub berhak membatalkan atau memindahkan jadwal latihan apabila terdapat kondisi tertentu, seperti cuaca buruk, kondisi kolam, kegiatan klub, atau keadaan darurat.

E. Make-Up Class / Penggantian Sesi
1. Penggantian sesi hanya dapat dilakukan sesuai kebijakan dan ketersediaan jadwal klub.
2. Pengajuan penggantian sesi wajib disampaikan sebelum jadwal latihan berlangsung.
3. Sesi yang tidak digunakan dalam batas waktu program dapat dinyatakan hangus.
4. Make-up class tidak dapat diuangkan atau dialihkan menjadi bentuk kompensasi lainnya.
5. Klub berhak menentukan jadwal, kelompok, dan pelatih untuk sesi pengganti sesuai dengan ketersediaan.

F. Peraturan Selama Latihan
1. Siswa wajib mengikuti instruksi pelatih selama kegiatan berlangsung.
2. Siswa wajib menjaga ketertiban, kebersihan, dan keamanan lingkungan kolam.
3. Siswa dilarang melakukan tindakan yang dapat membahayakan diri sendiri, pelatih, maupun peserta lain.
4. Siswa wajib menggunakan pakaian dan perlengkapan renang yang sesuai.
5. Siswa wajib menjaga dan bertanggung jawab atas barang pribadi yang dibawa.
6. Penggunaan fasilitas kolam wajib mengikuti peraturan yang ditetapkan oleh pengelola kolam.
7. Siswa yang melakukan tindakan tidak disiplin atau membahayakan dapat diberikan teguran dan/atau tindakan sesuai kebijakan klub.

G. Tanggung Jawab Orang Tua/Wali
1. Orang tua/wali bertanggung jawab atas pengantaran dan penjemputan siswa sesuai jadwal.
2. Orang tua/wali wajib memastikan siswa datang tepat waktu dan membawa perlengkapan latihan.
3. Orang tua/wali tidak diperkenankan mengganggu proses latihan dengan memberikan instruksi langsung kepada siswa selama sesi berlangsung.
4. Komunikasi mengenai program latihan, perkembangan siswa, jadwal, dan administrasi dilakukan melalui kanal komunikasi resmi klub.
5. Orang tua/wali wajib memberikan informasi yang benar dan terbaru terkait data siswa serta kondisi yang dapat memengaruhi proses latihan.

H. Keamanan dan Tanggung Jawab Klub
1. Klub dan pelatih akan berupaya menjalankan kegiatan latihan dengan memperhatikan aspek keselamatan dan prosedur latihan yang sesuai.
2. Pelatih bertanggung jawab memberikan instruksi dan pengawasan selama sesi latihan sesuai dengan kondisi dan program yang diberikan.
3. Aktivitas renang memiliki risiko yang melekat pada olahraga air. Orang tua/wali memahami dan menyetujui bahwa risiko cedera atau kejadian tidak terduga tetap dapat terjadi meskipun prosedur keselamatan telah diterapkan.
4. Klub akan mengambil tindakan pertolongan awal sesuai kemampuan dan prosedur yang tersedia apabila terjadi keadaan darurat selama latihan.
5. Apabila diperlukan, klub dapat meminta bantuan tenaga medis atau fasilitas kesehatan dan akan menghubungi orang tua/wali.

I. Perlengkapan Siswa
1. Siswa wajib membawa perlengkapan latihan yang diperlukan, antara lain: pakaian renang, kacamata renang, topi renang apabila diwajibkan, handuk, perlengkapan mandi, botol minum, serta perlengkapan latihan tambahan sesuai instruksi pelatih.

J. Dokumentasi dan Publikasi
1. Klub dapat melakukan dokumentasi berupa foto atau video selama kegiatan latihan, pertandingan, maupun kegiatan klub.
2. Dokumentasi dapat digunakan untuk kebutuhan informasi, evaluasi, dokumentasi kegiatan, dan promosi klub.
3. Orang tua/wali yang tidak mengizinkan penggunaan dokumentasi siswa untuk keperluan publikasi wajib menyampaikan keberatan kepada pihak klub pada saat pendaftaran.

K. Disiplin dan Etika
1. Setiap siswa wajib menghormati pelatih, staf, teman latihan, pengelola kolam, dan pihak lainnya.
2. Perundungan (bullying), pelecehan, kekerasan, penghinaan, diskriminasi, dan tindakan yang merugikan orang lain tidak diperbolehkan.
3. Pelanggaran terhadap peraturan dapat diberikan teguran, pembinaan, pembatasan mengikuti latihan, hingga penghentian keanggotaan sesuai tingkat pelanggaran.
4. Orang tua/wali diharapkan turut mendukung pembentukan sikap disiplin, sportivitas, tanggung jawab, dan sikap saling menghormati.

L. Pengunduran Diri
1. Pengunduran diri peserta wajib disampaikan oleh orang tua/wali kepada pihak klub.
2. Pengunduran diri tidak otomatis menyebabkan pengembalian biaya latihan yang telah dibayarkan.
3. Sisa sesi latihan tidak dapat dialihkan kepada orang lain tanpa persetujuan klub.

M. Perubahan Jadwal dan Keadaan Khusus
1. Klub dapat melakukan perubahan jadwal latihan karena kondisi kolam, cuaca, kegiatan kompetisi, hari libur, keadaan darurat, atau alasan operasional lainnya.
2. Perubahan jadwal akan diinformasikan melalui kanal komunikasi resmi klub.
3. Klub akan berupaya memberikan jadwal pengganti apabila perubahan berasal dari pihak klub dan memungkinkan untuk dilakukan.
TEXT,
        ];

        foreach ($settings as $key => $value) {
            LandingSetting::set($key, $value);
        }

        $coaches = [
            [
                'name' => 'Budi Santoso',
                'position' => 'Head Coach',
                'description' => 'Mantan atlet nasional dengan pengalaman melatih lebih dari 15 tahun. Spesialisasi pada program kompetitif dan pembentukan teknik dasar.',
                'photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCeo77mtiXI7SW9GOwmS9QuwGRGDjxWA16W2Ls0kayebuujMQ-kwoohS4jdKR_-7Vzb1hhl-CIspsO1bGP5np4UGcprJ-pWWmxTy6gmIdmuKUFZtliz_I1guflrgDtYyIskNfXeSlwv3nWADfTQi69Ijslnr-I8dfc0s_GbYJkUouVn7NKWY5pz_0CKdfZcN4fHHx4flGed_q9XRj1lSuM-yyvkqjVg48WjdZpMbeAKsYA4Dux67O_7',
                'sort_order' => 1,
            ],
            [
                'name' => 'Siti Rahmawati',
                'position' => 'Senior Coach',
                'description' => 'Ahli dalam pendekatan anak-anak usia dini. Sabar, telaten, dan selalu membuat suasana belajar renang menjadi sangat menyenangkan.',
                'photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCaujJ0KbaRnEnDCCceyva2PTCxK2SzHQrlIIMScWeRyLcPR3vJgwl_sg8ujslrvIlCKh6rgxrDZ2PUZLVUq2-fJfVOR7qoQJiPl2VfZ-miTCg4pbhCb9MGiQB-1MPMnpAlknxNVUfTcyIEpZWWV0GrrjS7U4jTQosEkD4NCkytcIHfgzGTjkVgIFHc4fulH1hZrXgq6iqKGbWfZAPEYrAcoYwx_8fDF_RDuM4WjzsRv9yLG1V7k0ZU',
                'sort_order' => 2,
            ],
            [
                'name' => 'Andi Pratama',
                'position' => 'Coach',
                'description' => 'Spesialis gaya bebas dan kupu-kupu. Fokus pada peningkatan stamina dan perbaikan detail teknik berenang.',
                'photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuABPMGvLp2zbPzfSSw72GWAj1BCTS-N1vhJs-lr-7heikBLSQUEpzzl9sjGIyItbNnN350Yt1RFNUi1U5V4TR-9kkRvz9aolqzHzCIoGR-xtTFa3h88PJ69jYKqE2rhlpYYfHLKL1OJFYje6svxeIgbaBva-AuEYcLD4FIoYQExQ_eCqTydZZTj9YHqDIil46UtsMGaZ6koqcPz2Q5n7wQOV_NQoel8pnNxazRHNaibmP_MFjfmaxRE',
                'sort_order' => 3,
            ],
            [
                'name' => 'Maya Sari',
                'position' => 'Coach',
                'description' => 'Bersertifikat khusus penyelamatan air (water rescue). Sangat memperhatikan aspek keamanan dan keselamatan selama berlatih.',
                'photo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB5WDNHwIogwoeeLcW4e35W2GKQWwtA4u-GZN9mEKMZ47HOvmQscThegdvkyQ-0uQfIabYUf7wfBppnzVqY-kAy2hwnxMYpIQyOOHxLVMUmAVuboM9MRgvrmBNB7JP5W87-lq4XfnxwT4iD4jk3OeN6oXo1azJxuDAHi4siYGDBsAZYKwD3HBQ0MUcHewHbqZCOhf_6quLQbIGW3aLZR41aGMnF_kUi6jHRZP_4U0BhYv1cayC0p5ll',
                'sort_order' => 4,
            ],
        ];

        foreach ($coaches as $coach) {
            LandingCoach::create($coach);
        }

        $programs = [
            [
                'name' => 'Private',
                'subtitle' => '1 Coach : 1 Siswa',
                'price' => 500000,
                'billing_unit' => '/4 Sesi',
                'features' => "Fokus intensif 1 on 1\nJadwal sangat fleksibel\nProgres lebih cepat",
                'badge' => null,
                'button_label' => 'Pilih Program',
                'featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mini Private',
                'subtitle' => '1 Coach : 2-3 Siswa',
                'price' => 300000,
                'billing_unit' => '/4 Sesi',
                'features' => "Cocok untuk keluarga/teman\nPerhatian tetap optimal\nLebih hemat",
                'badge' => 'POPULER',
                'button_label' => 'Pilih Program',
                'featured' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Reguler',
                'subtitle' => '1 Coach : Max 8 Siswa',
                'price' => 350000,
                'billing_unit' => '/Bulan',
                'features' => "4x pertemuan/bulan\nBelajar bersama teman sebaya\nKurikulum terstruktur",
                'badge' => null,
                'button_label' => 'Pilih Program',
                'featured' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Mini Reguler',
                'subtitle' => '1 Coach : Max 5 Siswa',
                'price' => 200000,
                'billing_unit' => '/8 Sesi',
                'features' => "Kelompok kecil\nFokus lebih baik dari reguler\nInteraksi sosial terjaga",
                'badge' => null,
                'button_label' => 'Pilih Program',
                'featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Kompetitif',
                'subtitle' => 'Program Khusus Atlet',
                'price' => 300000,
                'billing_unit' => '/Bulan',
                'features' => "Latihan intensif\nPersiapan kejuaraan\nEvaluasi berkala ketat",
                'badge' => null,
                'button_label' => 'Pilih Program',
                'featured' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($programs as $program) {
            LandingProgram::create($program);
        }

        $gallery = [
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDCFjAR19WNvx9tJfzMIQU7C-ZAA5JTQpUY1ebjhg4yfBmGOSy4fAOhfZRTEYT17Yg6PyphQKOHSho3UzsYKpqE5l4T7_MEVAb_JKw0jLrAvepaQCy8svP5spDYcLc8EqHPJeiOf4ZrwlSgMgmV2AFw56WMTMYnSFvCjyJIm_L5TTQ3sOtwELddhZnBbIrE2L6LTcCZltanivo6URhU2ojZdVrDvqYouLdku9D7FU7IwD_Xwpe4jdiD',
                'title' => 'Kelas Pemula',
                'description' => 'Siswa pemula belajar beradaptasi dengan air.',
                'category' => 'Latihan',
                'aspect' => 'square',
                'sort_order' => 1,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB4P-5_4Y5GizDpwCwV9xBjXIWg8mUjFl81Omq9KtY2DyCdDv6au1RXmLIDVkFsHCe4T6t6VO3DTBs7RV-ZM4iK4lePLqtKiUXSaD71dIUT04MZZq-2j7uRq5u-QH8geBTh5CKv6OShNx8O9rMdUyVYWaUS5vjYtzDCedZH5LK_vpZjy97dDwieHZeXyrpE-4EQRgQadb7WO4ucrs2w28xudGaOVqPfrFui-1O1z_axAI7hpT882Wcn',
                'title' => 'Latihan Atlet',
                'description' => 'Atlet melatih kekuatan dan ketahanan di air.',
                'category' => 'Kejuaraan',
                'aspect' => 'square',
                'sort_order' => 2,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBzigC_t94uOPkI0tnm69pMGC7_smYqXWZ9SRMSohLKzHI9zmLeNlV1U4hMEaGkI4-9-CSrf425YQw0HE_lHaPvyilkF3al3V66j6mSHg4UqZYNPp8IF8MY-SSGpV7XeXkA2Xr4AH2_JRHP4gh9kdVZp-czfmD_7poetKY_alGNE005YUHMxZx1LnRPLUTrYJu6OwhxME2bOFAdQnDBuIFmGibLY29oKNCiVU8ER7pnBG0hB1r9vO9Y',
                'title' => 'Keceriaan Siswa',
                'description' => 'Momen menyenangkan setelah sesi latihan.',
                'category' => 'Keceriaan',
                'aspect' => 'square',
                'sort_order' => 3,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBNHW2v0H8rjqk_809Rul0iwTiPtoVHs9C3DtxxogAITRO9qT0nVMtYSK_qb0s6SdGbiUOUYiBuzfuIZfH-_Xba8vfmojv-IkMOBWJ3hoQbe1AWLo4YyG2NlxBkDYAoyemuezVNO_LqytCC4MR2qbKZFhccx15kjD6rSJzZ0FnQWzZGq5ik6Hece0tZzqmCmgJwc_y_-JEeTkbQ-4YyjAWL1O_PI5tV5RnV7Wb7NB1Zh_4owC63cHYv',
                'title' => 'Persiapan Kejuaraan',
                'description' => 'Latihan intensif menjelang kompetisi.',
                'category' => 'Kejuaraan',
                'aspect' => 'square',
                'sort_order' => 4,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAfmTpBJqswEQZ9ekHqNMgu6_I0z0dO8R2ImxD8Psl_jF9e_5wU7E5NxttMx0cckm2tsZXtcuQTRCqLQNwAg3uMhQf22bl-MD4hwzyYnKkQWuiTzSrfk6VcX3jjb2HzkYJFmbhbJF6qa5KC8izRTOmX2wRSnbvPTEgZ937jh1rmZ41MdgqFyqE7pjquuiJuRL0oxA-MUjCTZtys4qcwQXEFNnAYgS2RnIYDMUEMuQre3yEaQSNlKkPf',
                'title' => 'Evaluasi Individu',
                'description' => 'Coach memberikan evaluasi teknik secara personal.',
                'category' => 'Latihan',
                'aspect' => 'square',
                'sort_order' => 5,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCDuO0KiEFUUcQrDEco2eDycR7OYs6kZTdubh39OM_JiZjFk4h0Ay8OYAtNEnAwuEkkgZrZhu8qmqcsXV6XUXH66Tw4fVbuLHqN5rcLqFGqPvXpmUeIF6rDjDkNmOvE4dtYUqnLaaBjtJx10wl0R79T7NOzn_hm-WmT3MB1QNhsBd5ddoAYt17Fcl-g6_auQ1QLUBQVIb4ahPFLtuUqJbRClVASAzSuo2nqmBHyVLKklP8Nd0BA_vGS',
                'title' => 'Latihan Bersama',
                'description' => 'Sesi latihan kelompok yang menyenangkan.',
                'category' => 'Keceriaan',
                'aspect' => 'square',
                'sort_order' => 6,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBThTzuWI5rBBwhO7oDgVytmutMzhy3fa7Zm69UlhWl7gWjOqKFvCECbsPdF8R8IZ1DhKXv_Y4wyeSzB3CMk8SjfvPPJDahjUeE41RaEvk67Gga93EHDVDrt8oL4b68MYzox5p3c6m2e3rzjFEICWtJdjuJqAW8noPVIX-eLmX2x0miuZV73yWsz5SJpob1M6XcliuO-Rwhd5TPgpEKlnNq45lb4WaPjRYELlJ2-HTTSUEMT8p5i1pC',
                'title' => 'Dokumentasi Atlet',
                'description' => 'Atlet berpose bersama trofi kemenangan.',
                'category' => 'Kejuaraan',
                'aspect' => 'square',
                'sort_order' => 7,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAYOHuZo9ysAmIHkpPED6uJw-hDQRSvOW6Um4uUIDoLcVbB58rg8Lss2YoEARULU8ZUsTxK5ktLjEx79uybYNkz8fiUScpUxDN5k8pq6Ws1TovgMVZ7SLNBS2XemjSnzuEmUrJJhDLiCOHNe3fw3vJoaJiDM8_TYZRxNfA_EbV15Y3y8oiG2Yl2ZqPUNJ3FJloDyrggep7vcEUtuDYjozhZpc_WRqfGEVnPeIwIMSiGhGlJKOeZcLHd',
                'title' => 'Sesi Latihan Sore',
                'description' => 'Latihan rutin sore bersama seluruh kelas.',
                'category' => 'Latihan',
                'aspect' => 'square',
                'sort_order' => 8,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCUcNvSxFzh0EKgSmjoo3O6vdZAoTq-2ZwUV3_Lo3tBtnUziRD7zRR8ItBch5z5BqPC3BxKLUFAVmWVyXrO5bF86DPThQnuijVZ3gY02Nb4QXRPRQqWP5hpLzLq0UjFfZEIqVS5j6N156xrxWbQ_JnBNBELEquo-l2Qvr9rRZZBD5y0MEf8aVKM_sPZcsw9wXc3yqWLH6J1jJgeVEljjSiY9qxeHkHroOIqKsWAg60ig4SZAmc5z6-l',
                'title' => 'Kelas Pemula Minggu Pagi',
                'description' => 'Fokus pada teknik pernapasan dasar dan kenyamanan di dalam air.',
                'category' => 'Latihan',
                'aspect' => '4/3',
                'sort_order' => 9,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAZYP9DrL2p6CDEddW13ROaFkY8ujinkz0tPE7bQsp3098O6yAbvIMw8JISMZCiEL1_B2ZWfZeJQv0h_PCkgzASN8ie7PhWzfggt6PhiBBjZUNCVs9JGEK7YgmgwetbGAEA4DuM6jpbihFRB870XTtgEOFAFG_N0yicTFKz9T-HQYMljZL_b_kuxGXa-6SNJmtv-Qflax9BHuhJJO70V0M0lf8--4VTLF01VJGU4SBtCtfFuSJVR26I',
                'title' => 'Kejuaraan Renang Antar Klub 2024',
                'description' => 'Atlet elit kami menunjukkan performa terbaik di gaya kupu-kupu.',
                'category' => 'Kejuaraan',
                'aspect' => '3/4',
                'sort_order' => 10,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAjTXnqdAtnvb9bjHFFIHJj9zRpvN4sB5CZ6a5E4tAnDZ9ieHGzYEFNuXSDm-hzBY0L_NPQoNDgTiexhs7d9kNwHekjMkcpwM7KvnZD-TjwB6lG7IwS7OpzD-lj1zLBC8UNLp0vdsfgGDvhcs8DoC4B9JuGQjNcqdXjOrHz35WYjZuK3mewBwydGDlIP6Dbg6i5_g2T9ggbhtItEaCCiY7Xdbe7p0uTWuWwcZ2iLliTXue6hXMx3_jU',
                'title' => 'Sesi Bermain Setelah Latihan',
                'description' => 'Membangun keakraban antar siswa setelah sesi latihan yang intens.',
                'category' => 'Keceriaan',
                'aspect' => 'square',
                'sort_order' => 11,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBMp6VdrjP2owj06zwgIsl1PeQX6f8BnsLBZc8-3XPGHo1MxuwGvj_m6bs4-b3BwDjTY8WeG7ra8Ga_f530QlNv5bDeNXbdOzW6L-0jvhvWL44sUvz0dskUOzVzAltCBKFuavg81mS1dV4XGjt_hveZT-Rf0Zq7UoDwk1YkW-tHLDDKk0yp7SU310vXLcq20CiblRFQhsw68mpHu-yzHMZGBlpjdx2xrNW66MhEO3s7rAobUwwQNo6q',
                'title' => 'Drill Teknik Gaya Bebas',
                'description' => 'Fokus perbaikan rotasi tubuh untuk efisiensi gaya bebas.',
                'category' => 'Latihan',
                'aspect' => 'video',
                'sort_order' => 12,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBzeQrFWUiVySl2r_fHSA50I1bjLEwtTpJP8fOfhW5FLua3WIAkbhu5qxzpxt-MlXvt6w6_AU66G0yS4RQpcBVMX49LiSndFe_qBy6cpR37T-ILfwOYdv-WfYGc7WOSTYVG0XhK2ovyPecIkZk9IshriB_YQuwz4zFFrXIcagwP8w8A-5xXHoOgHpxZNzScefuHawPs7HDbxXzDKHRdCZymwsRS7suHYe0frULgH4xqfKE1x-zxeZ8g',
                'title' => 'Evaluasi Individu bersama Coach',
                'description' => 'Setiap siswa mendapatkan perhatian khusus untuk perkembangan optimal.',
                'category' => 'Latihan',
                'aspect' => '4/5',
                'sort_order' => 13,
            ],
        ];

        foreach ($gallery as $image) {
            LandingGalleryImage::create($image);
        }
    }
}
