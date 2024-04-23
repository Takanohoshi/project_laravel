@include('layouts.petugas')

<style>
    .search-container {
        float: left;
    }

    .btn-container {
        float: right;
    }
</style>

<h1>
    Laporan Peminjaman
</h1>
<div class="search-container">
    <div class="search">
        <input type="date" id="tanggalPinjam" placeholder="Tanggal Pinjam">
        <input type="date" id="tanggalKembali" placeholder="Tanggal Kembali">
        <button type="button" onclick="filterByDate()">Search</button>
    </div>
</div>
    
 
<div class="btn-container">
    <button onclick="printTable()" class="btn btn-primary">Cetak tabel</button>
</div>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Tanggal Peminjaman</th>
            <th>Tanggal Pengembalian</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataPeminjaman as $index => $peminjaman)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $peminjaman->user->username }}</td>
                <td>{{ $peminjaman->buku->judul }}</td>
                <td>{{ $peminjaman->tanggal_pinjam }}</td>
                <td>{{ $peminjaman->tanggal_kembali }}</td>
                <td>{{ $peminjaman->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    function printTable() {
        const printWindow = window.open('', '', 'width=600,height=600');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('table, th, td { border: 1px solid black; border-collapse: collapse; }'); 
        printWindow.document.write('th, td { padding: 10px; }'); 
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<table>');
        printWindow.document.write(document.querySelector(".table").outerHTML);
        printWindow.document.write('</table>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
<script>
    function filterByDate() {
        var tanggalPinjam = document.getElementById('tanggalPinjam').value;
        var tanggalKembaliInput = document.getElementById('tanggalKembali');
        var rows = document.querySelectorAll('tbody tr');

        // Menghitung tanggal kembali 2 hari setelah tanggal pinjam
        var tanggalPinjamObj = new Date(tanggalPinjam);
        var tanggalKembaliObj = new Date(tanggalPinjamObj.getTime() + (2 * 24 * 60 * 60 * 1000));
        var tahun = tanggalKembaliObj.getFullYear();
        var bulan = String(tanggalKembaliObj.getMonth() + 1).padStart(2, '0');
        var tanggal = String(tanggalKembaliObj.getDate()).padStart(2, '0');
        var tanggalKembali = tahun + '-' + bulan + '-' + tanggal;

        // Mengatur nilai input tanggal kembali
        tanggalKembaliInput.value = tanggalKembali;

        rows.forEach(function(row) {
            var tanggalPinjamCell = row.querySelector('td:nth-child(4)').textContent; // Indeks 4 untuk tanggal pinjam
            var tanggalKembaliCell = row.querySelector('td:nth-child(5)').textContent; // Indeks 5 untuk tanggal kembali

            // Menggunakan flag untuk menentukan apakah baris harus ditampilkan
            var showRow = true;

            // Jika salah satu tanggal tidak diisi, maka tidak perlu memeriksa lagi dan langsung tampilkan baris
            if (tanggalPinjam === '' || tanggalKembali === '') {
                showRow = true;
            }
            // Jika keduanya diisi dan berada dalam rentang tanggal pinjam dan kembali, tampilkan baris
            else if ((tanggalPinjam <= tanggalPinjamCell || tanggalPinjam === '') &&
                (tanggalKembali >= tanggalKembaliCell || tanggalKembali === '')) {
                showRow = true;
            }
            // Jika tidak memenuhi kondisi di atas, sembunyikan baris
            else {
                showRow = false;
            }

            // Menampilkan atau menyembunyikan baris berdasarkan nilai flag
            if (showRow) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>



