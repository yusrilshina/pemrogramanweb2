<style>
    table,
    th,
    td {
        border: 1px solid;
        padding: 5px;
    }
</style>
<h1 style="text-align: center;">Daftar Buku</h1>
<table style="border-collapse: collapse; width: 100%;">
    <tr>
        <td style="width: 5%;">No</td>
        <td style="width: 10%;">Kode</td>
        <td style="width: 25%;">Judul</td>
        <td style="width: 20%;">Pengarang</td>
        <td style="width: 20%;">Penerbit</td>
        <td style="width: 10%;">Tahun Terbit</td>
    </tr>

    <?php
    $no = 0;
    foreach ($dtbuku as $k) {
        $no++;
        $kode = $k->Kode_Buku;
        $judul = $k->Judul;
        $pengarang = $k->Pengarang;
        $penerbit = $k->Penerbit;
        $tahun = $k->Tahun_Terbit;
        echo " <tr>
        <td>$no</td>
        <td>$kode</td>
        <td>$judul</td>
        <td>$pengarang</td>
        <td>$penerbit</td>
        <td>$tahun</td>
    </tr>";
    }
    ?>
</table>