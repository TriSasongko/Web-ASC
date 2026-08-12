
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
