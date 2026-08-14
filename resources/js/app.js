
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

window.confirmToggleDevelopment = function (event, form, coachName, isOn) {
    event.preventDefault();

    Swal.fire({
        title: isOn ? 'Matikan izin penilaian?' : 'Aktifkan izin penilaian?',
        text: isOn
            ? coachName + ' tidak akan bisa mengisi penilaian perkembangan siswa.'
            : coachName + ' akan bisa mengisi penilaian perkembangan siswa.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: isOn ? 'Ya, Matikan' : 'Ya, Aktifkan',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

// Format input waktu best time: hanya menerima angka, titik dua ":" otomatis.
// Pola: Menit(2):Detik(2):MiliDetik(2), contoh ketik 012537 -> "01:25:37".
window.formatTimeInput = function (input) {
    var digits = input.value.replace(/\D/g, '').slice(0, 6);
    var out = digits;

    if (digits.length > 2) {
        out = digits.slice(0, 2) + ':' + digits.slice(2);
    }

    if (digits.length > 4) {
        out = out.slice(0, 5) + ':' + digits.slice(4);
    }

    input.value = out;
};

window.confirmMoveToClass = function (event, form, studentName) {
    event.preventDefault();

    var select = form.querySelector('select[name="target_class_id"]');
    var targetName = select.options[select.selectedIndex] ? select.options[select.selectedIndex].text : '';

    if (!select.value) {
        Swal.fire({
            title: 'Pilih kelas target',
            text: 'Silakan pilih kelas target terlebih dahulu.',
            icon: 'warning',
            confirmButtonText: 'OK',
        });

        return false;
    }

    Swal.fire({
        title: 'Ajukan Naik Kelas?',
        html: '<strong>' + studentName + '</strong> akan diajukan naik ke <strong>' + targetName + '</strong>.',
        text: 'Wajib konfirmasi ke orang tua sebelum siswa dipindahkan.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ajukan',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

Alpine.start();
