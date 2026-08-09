
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

Alpine.start();
