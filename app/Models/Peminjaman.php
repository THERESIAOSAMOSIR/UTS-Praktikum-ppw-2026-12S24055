<script>
    // 1. Data Awal Buku (Master Data)
    const initialBooks = [
      { id: 1, title: "IOT", >enum('status', ['diajukan', 'disetujui', 'ditolak', 'selesai',default:'diajukan'])->default('dipinjam')};
      { id: 2, title: "SRV", >enum('status', ['diajukan', 'disetujui', 'ditolak', 'selesai',default:'diajukan'])->default('dipinjam')};
      { id: 3, title: "DRN", >enum('status', ['diajukan', 'disetujui', 'ditolak', 'selesai',default:'diajukan'])->default('dipinjam')};
      
    // 2. Inisialisasi State dari localStorage
    function getLoans() {
      const data = localStorage.getItem('ppw01_loans');
      return data ? JSON.parse(data) : [];
    }

    function saveLoans(loans) {
      localStorage.setItem('ppw01_loans', JSON.stringify(loans));
    }

    // 3. Membaca Query String Pencarian (HTTP GET Parameter 'q')
    function getSearchQuery() {
      const params = new URLSearchParams(window.location.search);
      return (params.get('q') || '').trim().toLowerCase();
    }

    // 4. Render Katalog Buku & Dropdown Form
    function renderApp() {
      const loans = getLoans();
      const query = getSearchQuery();
      document.getElementById('searchInput').value = new URLSearchParams(window.location.search).get('q') || '';

      // Tentukan status buku berdasarkan daftar pinjaman aktif
      const booksWithStatus = initialBooks.map(book => {
        const isBorrowed = loans.some(l => l.bookId === book.id);
        return {
          ...book,
          status: isBorrowed ? 'Dipinjam' : 'Tersedia'
        };
      });

      // Filter berdasarkan query GET jika ada
      const filteredBooks = booksWithStatus.filter(b => {
        if (!query) return true;
        return b.title.toLowerCase().includes(query) || b.author.toLowerCase().includes(query);
      });

      // Render Tabel Buku
      const bookTbody = document.getElementById('bookTableBody');
      if (filteredBooks.length === 0) {
        bookTbody.innerHTML = `<tr><td colspan="5" class="empty-state">Buku tidak ditemukan untuk pencarian "${query}".</td></tr>`;
      } else {
        bookTbody.innerHTML = filteredBooks.map((b, idx) => `
          <tr>
            <td>${idx + 1}</td>
            <td><strong>${b.title}</strong></td>
            <td>${b.author}</td>
            <td>${b.year}</td>
            <td>
              <span class="badge ${b.status === 'Tersedia' ? 'badge-avail' : 'badge-borrowed'}">
                ${b.status}
              </span>
            </td>
          </tr>
        `).join('');
      }

      // Render Dropdown Buku Tersedia di Form
      const bookSelect = document.getElementById('bookSelect');
      const availableBooks = booksWithStatus.filter(b => b.status === 'Tersedia');
      bookSelect.innerHTML = '<option value="">-- Pilih Buku --</option>' +
        availableBooks.map(b => `<option value="${b.id}">${b.title} (${b.year})</option>`).join('');

      // Render Tabel Peminjaman (localStorage)
      const loanTbody = document.getElementById('loanTableBody');
      if (loans.length === 0) {
        loanTbody.innerHTML = '<tr><td colspan="5" class="empty-state">Belum ada peminjaman aktif saat ini.</td></tr>';
      } else {
        loanTbody.innerHTML = loans.map((loan, idx) => `
          <tr>
            <td>${loan.userName}</td>
            <td>${loan.userNIM}</td>
            <td>${loan.bookTitle}</td>
            <td>${loan.date}</td>
            <td>
              <button class="btn btn-danger" onclick="returnBook(${loan.bookId})">Return Book</button>
            </td>
          </tr>
        `).join('');
      }

      // Update Statistik
      document.getElementById('statTotal').innerText = initialBooks.length;
      document.getElementById('statAvail').innerText = availableBooks.length;
      document.getElementById('statBorrowed').innerText = loans.length;
    }

    // 5. Handle Peminjaman Buku
    document.getElementById('borrowForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const name = document.getElementById('borrowerName').value.trim();
      const nim = document.getElementById('borrowerNIM').value.trim();
      const bookId = parseInt(document.getElementById('bookSelect').value);

      if (!name || !nim || !bookId) {
        alert('Harap lengkapi semua kolom form peminjaman!');
        return;
      }

      const book = initialBooks.find(b => b.id === bookId);
      if (!book) return;

      const loans = getLoans();
      loans.push({
        bookId: book.id,
        bookTitle: book.title,
        userName: name,
        userNIM: nim,
        date: new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
      });

      saveLoans(loans);
      this.reset();
      renderApp();
      alert(`Buku "${book.title}" berhasil dipinjam oleh ${name} (${nim})!`);
    });

    // 6. Bonus: Return Book (Hapus dari peminjaman)
    window.returnBook = function(bookId) {
      let loans = getLoans();
      const targetLoan = loans.find(l => l.bookId === bookId);
      if (!targetLoan) return;