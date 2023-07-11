<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpCsFixer\Fixer\LanguageConstruct\FunctionToConstantFixer;

class Mdata extends Model
{
    public function getPengarang()
    {
        $sql = "SELECT * FROM pengarang ORDER BY Nama_Pengarang";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getResult();
        } else {
            return 0;
        }
    }
    public function getPenerbit()
    {
        $sql = "SELECT * FROM penerbit ORDER BY Nama_Penerbit";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getResult();
        } else {
            return 0;
        }
    }
    public function getTotalBuku()
    {
        $sql = "SELECT COUNT(*) AS Jumlah FROM buku";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getRow();
        } else {
            return 0;
        }
    }
    public function getMaxTahun()
    {
        // $sql = "SELECT COUNT(*) AS Jumlah FROM buku";
        $sql = "SELECT Tahun_Terbit, COUNT(*) AS Jumlah FROM buku GROUP BY Tahun_Terbit ORDER BY COUNT(*) DESC LIMIT 1";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getRow();
        } else {
            return 0;
        }
    }
    public function getMaxPenerbit()
    {
        // $sql = "SELECT COUNT(*) AS Jumlah FROM buku";
        $sql = "SELECT Penerbit, COUNT(*) AS Jumlah FROM buku_view GROUP BY Penerbit ORDER BY COUNT(*) DESC LIMIT 1";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getRow();
        } else {
            return 0;
        }
    }
    public function getMaxRak()
    {
        // $sql = "SELECT COUNT(*) AS Jumlah FROM buku";
        $sql = "SELECT SUBSTRING(Rak,1,1) AS Rak, COUNT(*) AS Jumlah FROM buku GROUP BY SUBSTRING(Rak,1,1) ORDER BY COUNT(*) DESC LIMIT 1";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getRow();
        } else {
            return 0;
        }
    }
    // public function getBuku($y)
    // {
    //     $sql = "SELECT * FROM buku_view WHERE Judul LIKE '%$y%' ORDER BY Judul ";
    //     $dt = db_connect()->query($sql);
    //     if ($dt) {
    //         return $dt->getResult();
    //     } else {
    //         return 0;
    //     }
    // }

    public function getBuku()
    {
        $sql = "SELECT * FROM buku_view ORDER BY Judul ";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getResult();
        } else {
            return 0;
        }
    }
    public function TambahData($kode, $judul, $isbn, $pengarang, $penerbit, $tahun, $rak)
    {
        $sql = "INSERT INTO buku VALUES('$kode','$judul','$pengarang','$penerbit','$isbn','$tahun','$rak')";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return "1";
        } else {
            return "0";
        }
    }
    public function AmbilData($kode)
    {
        $sql = "SELECT * FROM buku WHERE Kode_Buku = '$kode'";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getResult();
        } else {
            return 0;
        }
    }
    public function UpdateData($kode, $judul, $isbn, $pengarang, $penerbit, $tahun, $rak)
    {
        $sql = "UPDATE buku SET Judul='$judul', ID_Pengarang='$pengarang', ID_Penerbit='$penerbit', ISBN ='$isbn', Tahun_Terbit='$tahun', Rak='$rak' WHERE Kode_Buku='$kode'";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return "1";
        } else {
            return "0";
        }
    }
    public function HapusData($kode)
    {
        $sql = "DELETE FROM buku WHERE Kode_Buku='$kode'";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return "1";
        } else {
            return "0";
        }
    }
    public function RekapDashboard($sql)
    {
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getResult();
        } else {
            return "0";
        }
    }
    public function getBuku_perTahun()
    {
        $sql = "SELECT Tahun_Terbit, COUNT(*) AS Jumlah FROM buku GROUP BY Tahun_Terbit ORDER BY Tahun_Terbit";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getResult();
        } else {
            return "0";
        }
    }
    public function backupfile()
    {
        $sql = "SELECT * FROM buku ";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return $dt->getResult();
        } else {
            return 0;
        }
    }
    public function HapusBukuAll()
    {
        $sql = "DELETE FROM buku";
        $dt = db_connect()->query($sql);
        if ($dt) {
            return "1";
        } else {
            return "0";
        }
    }
}
