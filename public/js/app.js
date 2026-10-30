document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');

    if (toggle && sidebar) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('is-open');
        });

        document.addEventListener('click', function (event) {
            if (!sidebar.classList.contains('is-open')) return;
            if (sidebar.contains(event.target) || toggle.contains(event.target)) return;
            sidebar.classList.remove('is-open');
        });
    }

    document.querySelectorAll('.alert__close').forEach(function (button) {
        button.addEventListener('click', function () {
            var alert = button.closest('.alert');
            if (alert) alert.remove();
        });
    });

    document.querySelectorAll('.pilihan').forEach(function (label) {
        var input = label.querySelector('input[type="radio"]');
        if (!input) return;

        var refresh = function () {
            var group = document.querySelectorAll('input[name="' + input.name + '"]');
            group.forEach(function (item) {
                var itemLabel = item.closest('.pilihan');
                if (itemLabel) itemLabel.classList.toggle('is-selected', item.checked);
            });
        };

        input.addEventListener('change', refresh);
        refresh();
    });

    var formKuis = document.getElementById('form-kuis');
    if (formKuis) {
        formKuis.addEventListener('submit', function (event) {
            var soal = formKuis.querySelectorAll('[data-soal-id]');
            var belumTerjawab = 0;

            soal.forEach(function (item) {
                var id = item.getAttribute('data-soal-id');
                if (!formKuis.querySelector('input[name="jawaban[' + id + ']"]:checked')) {
                    belumTerjawab++;
                }
            });

            if (belumTerjawab > 0) {
                event.preventDefault();
                window.showKidAlert('Masih ada ' + belumTerjawab + ' soal yang belum dijawab. Ayo lengkapi dulu! ✏️');
            }
        });
    }

    var container = document.getElementById('daftar-soal');
    var tombolTambah = document.getElementById('tambah-soal');

    if (container && tombolTambah) {
        var counter = container.querySelectorAll('.soal-block').length;

        tombolTambah.addEventListener('click', function () {
            var index = counter;
            counter++;

            var block = document.createElement('div');
            block.className = 'soal-block';
            block.innerHTML =
                '<div class="soal-block__header"><span>Soal ' + counter + '</span></div>' +
                '<div class="form-group">' +
                '<label class="form-label">Pertanyaan</label>' +
                '<textarea class="form-textarea" name="soal[' + index + '][pertanyaan]" required></textarea>' +
                '</div>' +
                '<div class="form-row">' +
                '<div class="form-group"><label class="form-label">Pilihan A</label>' +
                '<input class="form-input" type="text" name="soal[' + index + '][pilihan_a]" required></div>' +
                '<div class="form-group"><label class="form-label">Pilihan B</label>' +
                '<input class="form-input" type="text" name="soal[' + index + '][pilihan_b]" required></div>' +
                '</div>' +
                '<div class="form-row">' +
                '<div class="form-group"><label class="form-label">Pilihan C</label>' +
                '<input class="form-input" type="text" name="soal[' + index + '][pilihan_c]" required></div>' +
                '<div class="form-group"><label class="form-label">Pilihan D</label>' +
                '<input class="form-input" type="text" name="soal[' + index + '][pilihan_d]" required></div>' +
                '</div>' +
                '<div class="form-group mb-0">' +
                '<label class="form-label">Jawaban Benar</label>' +
                '<select class="form-select" name="soal[' + index + '][kunci_jawaban]" required>' +
                '<option value="">Pilih jawaban benar</option>' +
                '<option value="a">A</option><option value="b">B</option>' +
                '<option value="c">C</option><option value="d">D</option>' +
                '</select></div>';

            container.appendChild(block);
            block.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    }

    /* ---------- Navbar landing (hamburger) ---------- */
    var lpToggle = document.getElementById('lpNavToggle');
    var lpMenu = document.getElementById('lpNavMenu');
    var lpNav = document.getElementById('lpNav');

    if (lpToggle && lpMenu) {
        lpToggle.addEventListener('click', function () {
            var open = lpMenu.classList.toggle('is-open');
            lpToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        lpMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                lpMenu.classList.remove('is-open');
                lpToggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    if (lpNav) {
        var onScroll = function () {
            lpNav.classList.toggle('is-scrolled', window.scrollY > 8);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* ---------- Efek riak (ripple) pada tombol ---------- */
    document.addEventListener('click', function (event) {
        var btn = event.target.closest('.btn');
        if (!btn || btn.disabled) return;

        var rect = btn.getBoundingClientRect();
        var size = Math.max(rect.width, rect.height);
        var ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = (event.clientX - rect.left - size / 2) + 'px';
        ripple.style.top = (event.clientY - rect.top - size / 2) + 'px';
        btn.appendChild(ripple);
        setTimeout(function () { ripple.remove(); }, 550);
    });

    /* ---------- Animasi progress bar dari 0 ---------- */
    document.querySelectorAll('.progress__fill').forEach(function (fill) {
        var target = fill.style.width || '0%';
        fill.style.width = '0%';
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                fill.style.width = target;
            });
        });
    });

    /* ---------- Konfeti ---------- */
    window.playConfetti = function (count) {
        count = count || 70;
        var layer = document.createElement('div');
        layer.className = 'confetti-layer';
        var colors = ['#FBBF24', '#F472B6', '#38BDF8', '#22C55E', '#A78BFA', '#F97316'];

        for (var i = 0; i < count; i++) {
            var piece = document.createElement('i');
            piece.style.left = Math.random() * 100 + 'vw';
            piece.style.background = colors[Math.floor(Math.random() * colors.length)];
            piece.style.animationDuration = (2.4 + Math.random() * 2.2) + 's';
            piece.style.animationDelay = (Math.random() * 0.8) + 's';
            piece.style.width = piece.style.height = (7 + Math.random() * 9) + 'px';
            if (Math.random() > 0.5) piece.style.borderRadius = '50%';
            layer.appendChild(piece);
        }

        document.body.appendChild(layer);
        setTimeout(function () { layer.remove(); }, 6500);
    };

    window.showKidAlert = function (pesan) {
        var box = document.createElement('div');
        box.className = 'alert alert-warning';
        box.style.position = 'fixed';
        box.style.top = '80px';
        box.style.left = '50%';
        box.style.transform = 'translateX(-50%)';
        box.style.zIndex = '1000';
        box.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
        box.style.maxWidth = '90%';
        box.innerHTML = '<span>' + pesan + '</span>';
        document.body.appendChild(box);
        setTimeout(function () { box.remove(); }, 3500);
    };

    if (document.querySelector('[data-celebrate]')) {
        window.playConfetti(90);
        setTimeout(function () { window.playConfetti(50); }, 900);
    }

    document.querySelectorAll('.js-copy').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var teks = btn.getAttribute('data-copy') || '';

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(teks).catch(function () {});
            }

            var asli = btn.textContent;
            btn.textContent = 'Tersalin ✓';
            setTimeout(function () { btn.textContent = asli; }, 1600);
        });
    });

    /* ---------- Modal konfirmasi hapus ---------- */
    var confirmModal = document.getElementById('confirmModal');

    if (confirmModal) {
        var judulKonfirmasi = confirmModal.querySelector('[data-modal-title]');
        var teksKonfirmasi = confirmModal.querySelector('[data-modal-text]');
        var tombolBatal = confirmModal.querySelector('[data-modal-cancel]');
        var tombolHapus = confirmModal.querySelector('[data-modal-confirm]');
        var formAktif = null;

        var tutupModal = function () {
            confirmModal.hidden = true;
            formAktif = null;
        };

        document.querySelectorAll('form[data-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (form.getAttribute('data-terkonfirmasi') === '1') return;
                event.preventDefault();
                formAktif = form;
                judulKonfirmasi.textContent = form.getAttribute('data-confirm-title') || 'Konfirmasi';
                teksKonfirmasi.textContent = form.getAttribute('data-confirm-text') || '';
                confirmModal.hidden = false;
            });
        });

        tombolBatal.addEventListener('click', tutupModal);

        tombolHapus.addEventListener('click', function () {
            if (formAktif) {
                formAktif.setAttribute('data-terkonfirmasi', '1');
                formAktif.submit();
            }
            confirmModal.hidden = true;
        });

        confirmModal.addEventListener('click', function (event) {
            if (event.target === confirmModal) tutupModal();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !confirmModal.hidden) tutupModal();
        });
    }
});
