// app.js — TemuKembali
document.addEventListener('DOMContentLoaded', function () {
  // 1. Salin Kode Laporan ke Clipboard (Section 6.5)
  const copyButtons = document.querySelectorAll('[data-copy-code]');
  copyButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      const code = this.getAttribute('data-copy-code');
      if (!code) return;

      const originalText = this.innerText;
      navigator.clipboard.writeText(code).then(() => {
        this.innerText = 'Kode tersalin';
        this.classList.add('btn-tk-sekunder');
        setTimeout(() => {
          this.innerText = originalText;
          this.classList.remove('btn-tk-sekunder');
        }, 2000);
      }).catch(() => {
        // Fallback jika navigator.clipboard gagal
        const tempInput = document.createElement('input');
        tempInput.value = code;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        this.innerText = 'Kode tersalin';
        setTimeout(() => {
          this.innerText = originalText;
        }, 2000);
      });
    });
  });

  // 2. Mencegah Double Submit pada Form (Section 5.2)
  const forms = document.querySelectorAll('form[data-prevent-double-submit]');
  forms.forEach(function (form) {
    form.addEventListener('submit', function (e) {
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.dataset.originalText = submitBtn.innerText;
        submitBtn.innerText = 'Mengirim…';
      }
    });
  });

  // 3. Interaktivitas Pemilih Jenis Laporan (Form Lapor Section 5.5)
  const formBox = document.querySelector('.form-box');
  const typeRadios = document.querySelectorAll('input[name="type"]');
  if (formBox && typeRadios.length > 0) {
    function updateFormBorder() {
      const selected = document.querySelector('input[name="type"]:checked');
      if (selected) {
        if (selected.value === 'lost') {
          formBox.classList.add('form-box--lost');
          formBox.classList.remove('form-box--found');
        } else if (selected.value === 'found') {
          formBox.classList.add('form-box--found');
          formBox.classList.remove('form-box--lost');
        }
      }
    }
    typeRadios.forEach(r => r.addEventListener('change', updateFormBorder));
    updateFormBorder();
  }

  // 4. Pengendali Dialog Konfirmasi Bawaan (Section 5.7)
  const dialogTriggers = document.querySelectorAll('[data-dialog-target]');
  dialogTriggers.forEach(function (trigger) {
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      const targetId = this.getAttribute('data-dialog-target');
      const dialog = document.getElementById(targetId);
      if (dialog && typeof dialog.showModal === 'function') {
        dialog.showModal();
      }
    });
  });

  const dialogCloses = document.querySelectorAll('[data-dialog-close]');
  dialogCloses.forEach(function (closeBtn) {
    closeBtn.addEventListener('click', function () {
      const dialog = this.closest('dialog');
      if (dialog && typeof dialog.close === 'function') {
        dialog.close();
      }
    });
  });

  // 5. Scroll otomatis ke chip kategori aktif (terutama di layar mobile)
  function scrollToActiveChip() {
    const chipContainers = document.querySelectorAll('.chip-scroll');
    chipContainers.forEach(function (container) {
      const activeChip = container.querySelector('.chip-kategori.active');
      if (activeChip) {
        const containerRect = container.getBoundingClientRect();
        const chipRect = activeChip.getBoundingClientRect();
        const offset = (chipRect.left - containerRect.left) + container.scrollLeft;
        container.scrollLeft = offset - (containerRect.width / 2) + (chipRect.width / 2);
      }
    });
  }

  scrollToActiveChip();
  requestAnimationFrame(scrollToActiveChip);
});

