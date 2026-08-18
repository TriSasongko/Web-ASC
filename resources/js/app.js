
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

window.confirmToggleActive = function (event, form, name, isActive, subject) {
    event.preventDefault();
    subject = subject || 'pengguna';

    Swal.fire({
        title: isActive ? 'Nonaktifkan ' + subject + '?' : 'Aktifkan ' + subject + '?',
        text: isActive
            ? name + ' tidak akan bisa login dan mengakses sistem.'
            : name + ' akan bisa login dan mengakses sistem kembali.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: isActive ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: isActive ? '#D32F2F' : '#2E7D32',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmResetPassword = function (event, form, name) {
    event.preventDefault();

    Swal.fire({
        title: 'Reset Password?',
        text: 'Password ' + name + ' akan direset ke default (password).',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmDeleteUser = function (event, form, name, subject) {
    event.preventDefault();
    subject = subject || 'pengguna';

    Swal.fire({
        title: 'Hapus ' + subject + '?',
        html: '<strong>' + name + '</strong> akan dihapus permanen dari sistem.',
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#D32F2F',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmDeleteClass = function (event, form, className, studentCount) {
    event.preventDefault();

    var html = '<strong>' + className + '</strong> akan dihapus permanen dari sistem.';

    if (studentCount > 0) {
        html += '<br>Terdapat <strong>' + studentCount + ' siswa</strong> terdaftar di kelas ini.';
    }

    Swal.fire({
        title: 'Hapus kelas?',
        html: html,
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#D32F2F',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmAcceptRegistration = function (event, form, studentName) {
    event.preventDefault();

    Swal.fire({
        title: 'Terima Pendaftaran?',
        html: 'Pendaftaran <strong>' + studentName + '</strong> akan diterima.',
        text: 'Siswa akan masuk ke daftar penempatan kelas.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Terima',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2E7D32',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmRejectRegistration = function (event, form, studentName) {
    event.preventDefault();

    var reason = form.querySelector('textarea[name="rejection_reason"]');
    var reasonText = reason ? reason.value.trim() : '';

    Swal.fire({
        title: 'Tolak Pendaftaran?',
        html: '<strong>' + studentName + '</strong> akan ditolak' +
            (reasonText ? ' dengan alasan: <em>&quot;' + reasonText + '&quot;</em>' : '') + '.',
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#D32F2F',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmDeleteAttendance = function (event, form, studentName, date, className) {
    event.preventDefault();

    var html = 'Absensi <strong>' + studentName + '</strong> tanggal <strong>' + date + '</strong>'
        + (className ? ' di kelas <strong>' + className + '</strong>' : '')
        + ' akan dihapus.';

    Swal.fire({
        title: 'Hapus data absensi?',
        html: html,
        text: 'Riwayat pertemuan (sessions_completed) tidak dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#D32F2F',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmRecommendationApprove = function (event, form, studentName) {
    event.preventDefault();

    Swal.fire({
        title: 'Setujui rekomendasi?',
        html: 'Rekomendasi <strong>' + studentName + '</strong> akan disetujui.',
        text: 'Siswa dipindahkan setelah orang tua mengonfirmasi.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2E7D32',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmRecommendationReject = function (event, form, studentName) {
    event.preventDefault();

    Swal.fire({
        title: 'Tolak rekomendasi?',
        html: 'Rekomendasi <strong>' + studentName + '</strong> akan ditolak.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#D32F2F',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmRecommendationConfirm = function (event, form, studentName, targetLabel) {
    event.preventDefault();

    Swal.fire({
        title: 'Ortu sudah konfirmasi?',
        html: '<strong>' + studentName + '</strong> akan dipindahkan ke <strong>' + targetLabel + '</strong>.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Pindahkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2E7D32',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmRecommendationRejectByParent = function (event, form, studentName) {
    event.preventDefault();

    Swal.fire({
        title: 'Ortu menolak?',
        html: 'Rekomendasi <strong>' + studentName + '</strong> akan ditandai ditolak.',
        text: 'Siswa tetap di kelas sekarang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#D32F2F',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

window.confirmRecommendationDelete = function (event, form, studentName) {
    event.preventDefault();

    Swal.fire({
        title: 'Hapus rekomendasi?',
        html: 'Rekomendasi <strong>' + studentName + '</strong> akan dihapus permanen.',
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#D32F2F',
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

window.confirmDeleteStudent = function (event, form, studentName) {
    event.preventDefault();

    Swal.fire({
        title: 'Hapus siswa?',
        html: '<strong>' + studentName + '</strong> akan dihapus permanen dari sistem.',
        text: 'Data absensi, perkembangan, dan riwayat lainnya juga akan terhapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#D32F2F',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
};

Alpine.start();
