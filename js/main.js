document.addEventListener("DOMContentLoaded", function () {
  // 1. Validasi Form Kontak (index.php)
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      let isValid = true;

      const nama = document.getElementById("nama");
      const email = document.getElementById("email");
      const telepon = document.getElementById("telepon");
      const pesan = document.getElementById("pesan");

      // Validasi Nama
      if (!nama.value.trim()) {
        showInvalid(nama);
        isValid = false;
      } else {
        showValid(nama);
      }

      // Validasi Email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
        showInvalid(email);
        isValid = false;
      } else {
        showValid(email);
      }

      // Validasi Telepon
      if (!telepon.value.trim()) {
        showInvalid(telepon);
        isValid = false;
      } else {
        showValid(telepon);
      }

      // Validasi Pesan
      if (!pesan.value.trim()) {
        showInvalid(pesan);
        isValid = false;
      } else {
        showValid(pesan);
      }

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  // 2. Validasi Form Login (login.php)
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      let isValid = true;

      const username = document.getElementById("username");
      const password = document.getElementById("password");

      if (!username.value.trim()) {
        showInvalid(username);
        isValid = false;
      } else {
        showValid(username);
      }

      if (!password.value.trim()) {
        showInvalid(password);
        isValid = false;
      } else {
        showValid(password);
      }

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  // 3. Validasi Form Tambah Unit (admin/add.php)
  const addUnitForm = document.getElementById("addUnitForm");
  if (addUnitForm) {
    addUnitForm.addEventListener("submit", function (e) {
      let isValid = true;

      const namaUnit = document.getElementById("nama_unit");
      const tipe = document.getElementById("tipe");
      const harga = document.getElementById("harga");
      const gambar = document.getElementById("gambar");
      const deskripsi = document.getElementById("deskripsi");

      if (!namaUnit.value.trim()) {
        showInvalid(namaUnit);
        isValid = false;
      } else {
        showValid(namaUnit);
      }

      if (!tipe.value.trim()) {
        showInvalid(tipe);
        isValid = false;
      } else {
        showValid(tipe);
      }

      if (
        !harga.value.trim() ||
        isNaN(harga.value) ||
        parseFloat(harga.value) <= 0
      ) {
        showInvalid(harga);
        isValid = false;
      } else {
        showValid(harga);
      }

      if (!gambar.value) {
        showInvalid(gambar);
        isValid = false;
      } else {
        showValid(gambar);
      }

      if (!deskripsi.value.trim()) {
        showInvalid(deskripsi);
        isValid = false;
      } else {
        showValid(deskripsi);
      }

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  // 4. Validasi Form Edit Unit (admin/edit.php)
  const editUnitForm = document.getElementById("editUnitForm");
  if (editUnitForm) {
    editUnitForm.addEventListener("submit", function (e) {
      let isValid = true;

      const namaUnit = document.getElementById("nama_unit");
      const tipe = document.getElementById("tipe");
      const harga = document.getElementById("harga");
      const deskripsi = document.getElementById("deskripsi");

      if (!namaUnit.value.trim()) {
        showInvalid(namaUnit);
        isValid = false;
      } else {
        showValid(namaUnit);
      }

      if (!tipe.value.trim()) {
        showInvalid(tipe);
        isValid = false;
      } else {
        showValid(tipe);
      }

      if (
        !harga.value.trim() ||
        isNaN(harga.value) ||
        parseFloat(harga.value) <= 0
      ) {
        showInvalid(harga);
        isValid = false;
      } else {
        showValid(harga);
      }

      if (!deskripsi.value.trim()) {
        showInvalid(deskripsi);
        isValid = false;
      } else {
        showValid(deskripsi);
      }

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  // Helper functions for bootstrap validation styles
  function showInvalid(element) {
    element.classList.add("is-invalid");
    element.classList.remove("is-valid");
  }

  function showValid(element) {
    element.classList.remove("is-invalid");
    element.classList.add("is-valid");
  }
});

// 5. Global function untuk memicu modal konfirmasi hapus unit (dashboard.php)
function confirmDelete(id) {
  const confirmBtn = document.getElementById("confirmDeleteBtn");
  if (confirmBtn) {
    confirmBtn.setAttribute("href", "delete.php?id=" + id);
    const deleteModal = new bootstrap.Modal(
      document.getElementById("deleteModal"),
    );
    deleteModal.show();
  }
}
