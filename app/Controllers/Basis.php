<?php

namespace App\Controllers;

use App\Models\Mdata;
use App\Libraries\SimpleXLSXGen;

use function Symfony\Component\String\b;

class Basis extends BaseController
{
    // yang di komen adalah index awal
    // public function index()
    // {
    //     $dtx = new Mdata();
    //     $x['hal'] = 'beranda';
    //     $x['jmlbuku'] = $dtx->getTotalBuku();
    //     $x['maxtahun'] = $dtx->getMaxTahun();
    //     $x['maxpenerbit'] = $dtx->getMaxPenerbit();
    //     $x['maxrak'] = $dtx->getMaxRak();
    //     return view('home', $x);
    // }

    public function index()
    {
        $dtx = new Mdata();
        $x['hal'] = 'beranda';
        $x['jmlbuku'] = $dtx->getTotalBuku();
        $x['maxtahun'] = $dtx->getMaxTahun();
        $x['maxpenerbit'] = $dtx->getMaxPenerbit();
        $x['maxrak'] = $dtx->getMaxRak();
        $x['dtgrafik'] = $dtx->getBuku_perTahun();
        $x['dtpenerbit'] = $dtx->getPenerbit();
        return view('home', $x);
    }
    // yang di komen dalah method cari buku
    // public function data()
    // {
    //     $sesi = session();
    //     $sesi->set("keyword", "");
    //     $dtx = new Mdata();
    //     $x['hal'] = 'buku';
    //     $x['dtpengarang'] = $dtx->getPengarang();
    //     $x['dtpenerbit'] = $dtx->getPenerbit();
    //     return view('home', $x);
    // }

    public function data()
    {
        $dtx = new Mdata();
        $x['hal'] = 'buku';
        $x['dtpengarang'] = $dtx->getPengarang();
        $x['dtpenerbit'] = $dtx->getPenerbit();
        return view('home', $x);
    }

    private function os()
    {
        $ux = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/linux/i", $ux)) {
            $platform = "Linux";
        } else if (preg_match("/macintosh | mac os x/i", $ux)) {
            $platform = "macOs";
        } else if (preg_match("/windows | win32/i", $ux)) {
            $platform = "Windows";
        } else {
            $platform = "Tidak Diketahui";
        }
        return $platform;
    }

    private function mac()
    {
        ob_start();
        system('ipconfig/all');
        $mycom = ob_get_contents();
        ob_clean();
        $findme = "Physical";
        $pmac = strpos($mycom, $findme);
        $mac = substr($mycom, ($pmac + 36), 17);
        return $mac;
    }

    private function serial()
    {
        $seri = shell_exec('wmic diskdrive get serialnumber');
        return $seri;
    }

    public function tentang()
    {
        $x["hal"] = "tentang";
        $x["os"] = $this->os();
        $x["mac"] = $this->mac();
        $getserial = $x["os"] == "Windows" ? $this->serial() : "Tidak terdeteksi";
        $x["serial"] = str_replace("SerialNumber", "", $getserial);
        return view("home", $x);
    }

    // public function getData()
    // {
    //     $sesi = session();
    //     if ($sesi->get("keyword") == "" || $sesi->get("keyword") == null) {
    //         $data = '{"data" : []}';
    //     } else {
    //         $y = $sesi->get("keyword");
    //         $dtx = new Mdata();
    //         $dtJSON = '{"data" : [xxx]}';
    //         $dtisi = "";
    //         $dt = $dtx->getBuku($y);
    //         foreach ($dt as $k) {
    //             $kode = $k->Kode_Buku;
    //             $judul = $k->Judul;
    //             $pengarang = $k->Pengarang;
    //             $penerbit = $k->Penerbit;
    //             $tahun = $k->Tahun_Terbit;
    //             $isbn = $k->ISBN;
    //             $tombolkelola = sprintf("<button type='button' class='btn btn-primary' data-kode='%s' onclick='filter(this)'>Kelola</button>", $kode);
    //             $dtisi .= sprintf('["%s","%s","%s","%s","%s","%s","%s"],', $kode, $judul, $pengarang, $penerbit, $tahun, $isbn, $tombolkelola);
    //         }
    //         $dtisifix = rtrim($dtisi, ",");
    //         $data = str_replace("xxx", $dtisifix, $dtJSON);
    //     }
    //     echo $data;
    // }

    public function getData()
    {
        $dtx = new Mdata();
        $dtJSON = '{"data" : [xxx]}';
        $dtisi = "";
        $dt = $dtx->getBuku();
        foreach ($dt as $k) {
            $kode = $k->Kode_Buku;
            $judul = $k->Judul;
            $pengarang = $k->Pengarang;
            $penerbit = $k->Penerbit;
            $tahun = $k->Tahun_Terbit;
            $isbn = $k->ISBN;
            $tombolkelola = sprintf("<button type='button' class='btn btn-primary' data-kode='%s' onclick='filter(this)'>Kelola</button>", $kode);
            $dtisi .= sprintf('["%s","%s","%s","%s","%s","%s","%s"],', $kode, $judul, $pengarang, $penerbit, $tahun, $isbn, $tombolkelola);
        }
        $dtisifix = rtrim($dtisi, ",");
        $data = str_replace("xxx", $dtisifix, $dtJSON);
        echo $data;
    }

    public function TambahData()
    {
        $dtx = new Mdata();
        $kode = $this->request->getVar("kodex");
        $judul = $this->request->getVar("judulx");
        $isbn = $this->request->getVar("isbnx");
        $pengarang = $this->request->getVar("pengarangx");
        $penerbit = $this->request->getVar("penerbitx");
        $tahun = $this->request->getVar("tahunx");
        $rak = $this->request->getVar("rakx");
        $proses = $dtx->TambahData($kode, $judul, $isbn, $pengarang, $penerbit, $tahun, $rak);
        if ($proses == "1") {
            echo '{"kode" : "1", "pesan": "Data Berhasil di Tambahkan"}';
        } else {
            echo '{"kode" : "0", "pesan" : "Data Gagal Ditambahkan , Periksa Kembali Isian Anda"}';
        }
    }

    public function AmbilData()
    {
        $dtx = new Mdata();
        $kode = $this->request->getVar("kodex");
        $hasil = $dtx->AmbilData($kode);
        if (is_array($hasil)) {
            if (count($hasil) > 0) {
                foreach ($hasil as $h) {
                    $judul = $h->Judul;
                    $idpengarang = $h->ID_Pengarang;
                    $idpenerbit = $h->ID_Penerbit;
                    $isbn = $h->ISBN;
                    $tahun = $h->Tahun_Terbit;
                    $rak = $h->Rak;
                }
                echo sprintf('
                {"kode" : "1","judul" : "%s","pengarang" : "%s","penerbit" : "%s","isbn" : "%s","tahun" : "%s","rak" : "%s"}', $judul,  $idpengarang, $idpenerbit, $isbn, $tahun, $rak);
            } else {
                echo '{"kode" : "0","pesan" : "Data Tidak di Temukan"}';
            }
        } else {
            echo '{"kode" : "0","pesan" : "Data Tidak di Temukan"}';
        }
    }

    public function UpdateData()
    {
        $dtx = new Mdata();
        $kode = $this->request->getVar("kodex");
        $judul = $this->request->getVar("judulx");
        $isbn = $this->request->getVar("isbnx");
        $pengarang = $this->request->getVar("pengarangx");
        $penerbit = $this->request->getVar("penerbitx");
        $tahun = $this->request->getVar("tahunx");
        $rak = $this->request->getVar("rakx");
        $proses = $dtx->UpdateData($kode, $judul, $isbn, $pengarang, $penerbit, $tahun, $rak);
        if ($proses == "1") {
            echo '{"kode" : "1", "pesan": "Data Berhasil di Update"}';
        } else {
            echo '{"kode" : "0", "pesan" : "Data Gagal di Update , Periksa Kembali Isian Anda"}';
        }
    }

    public function HapusData()
    {
        $dtx = new Mdata();
        $kode = $this->request->getVar("kodex");
        $proses = $dtx->HapusData($kode);
        if ($proses == "1") {
            echo '{"kode" : "1", "pesan": "Data Berhasil di Hapus"}';
        } else {
            echo '{"kode" : "0", "pesan" : "Data Gagal di Hapus , Periksa Kembali Isian Anda"}';
        }
    }

    public function RekapDashboard()
    {
        $dtx = new Mdata();
        $jenis = $this->request->uri->getSegment(2);
        $nilai = urldecode($this->request->uri->getSegment(3));
        if ($jenis == "bytahun") {
            $sql =  sprintf("SELECT Judul FROM buku WHERE Tahun_Terbit = '%s'", $nilai);
        } else if ($jenis == "bypenerbit") {
            $sql =  sprintf("SELECT Judul FROM buku_view WHERE Penerbit = '%s'", $nilai);
        } else {
            $sql =  "SELECT Judul FROM buku WHERE Rak LIKE '" . $nilai . "%'";
        }
        $hasil = $dtx->RekapDashboard($sql);
        echo json_encode($hasil);
    }

    public function Pelanggan()
    {
        $x['hal'] = 'pelanggan';
        return view('home', $x);
    }

    // public function caribykeyword()
    // {
    //     $keyword = $this->request->getVar("keyword");
    //     $sesi = session();
    //     $sesi->set("keyword", $keyword);
    //     echo '{"kode" : "1"}';
    // }

    public function cetakpdf1()
    {
        require_once WRITEPATH . 'assets/vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
        $mpdf->setFooter('hal{PAGENO}');
        $mpdf->setAuthor('Pemrograman Web');
        $mpdf->WriteHTML("<h1>Ini File PDF Statis dari mPDF</h1>");
        $mpdf->Output("statis.pdf", "D");
    }

    public function cetakpdf2()
    {
        require_once WRITEPATH . 'assets/vendor/autoload.php';
        $dtx = new Mdata();
        $x["dtbuku"] = $dtx->getBuku();
        $hasil = view("print", $x, [true]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A3-L']);
        $mpdf->setFooter('hal{PAGENO}');
        $mpdf->setAuthor('Pemrograman Web');
        $mpdf->WriteHTML($hasil);
        $mpdf->Output("dinamis.pdf", "D");
    }

    public function cetakexcel1()
    {
        $dtx = new SimpleXLSXGen();
        $dataku = [
            ['Kolom1', 'Kolom2', 'Kolom3'],
            ['Cell1-1', 'Cell1-2', 'Cell1-3'],
            ['Cell2-1', 'Cell2-2', 'Cell2-3']
        ];
        $xlsx = $dtx->fromArray($dataku);
        $xlsx->downloadAs('statis.xlsx');
    }

    public function cetakexcel2()
    {
        $gen = new SimpleXLSXGen();
        $dtx = new Mdata();
        $dtk = [];
        array_push($dtk, ['<b><center>Daftar Buku</center></b>']);
        array_push($dtk, ['<b><center>Kode Buku</center></b>', '<b>Judul</b>', '<b>Pengarang</b>', '<b>Penerbit</b>']);
        $dtbuku = $dtx->getBuku();
        foreach ($dtbuku as $k) {
            $kode =  $k->Kode_Buku;
            $judul = $k->Judul;
            $pengarang = $k->Pengarang;
            $penerbit = $k->Penerbit;
            array_push($dtk, ['<center>' . $kode . '</center>', $judul, $pengarang, $penerbit]);
        }
        $xlsx = $gen->fromArray($dtk);
        $xlsx->mergeCells('A1:D1');
        $xlsx->setColWidth(2, 75);
        $xlsx->setColWidth(3, 40);
        $xlsx->setColWidth(4, 40);
        $xlsx->downloadAs('dinamis.xlsx');
    }

    public function getGrafik()
    {
        $dtx = new Mdata();
        $idpenerbit = $this->request->uri->getSegment(2);
        $sql =  "SELECT Tahun_Terbit, COUNT(*) AS Jumlah FROM buku WHERE ID_Penerbit= '$idpenerbit' GROUP BY Tahun_Terbit ORDER BY Tahun_Terbit";
        $hasil = $dtx->RekapDashboard($sql);
        echo json_encode($hasil);
    }

    public function backup()
    {
        $dtx = new Mdata();
        $dta = $dtx->backupfile();
        $hasil = json_encode($dta);
        $enc = base64_encode(openssl_encrypt($hasil, "AES-256-CBC", "key12345678*", 0, "0123456789abcdef"));
        $namafile = fopen("backup/buku.pwx", "w");
        fwrite($namafile, $enc);
        fclose($namafile);
        echo '{"kode" : "1","pesan" : "Data Berhasil di Backup"}';
    }

    // public function restore()
    // {
    //     $namafilex = $this->request->getVar("namafilex");
    //     $lokasi = "restore/" . $namafilex;
    //     if (move_uploaded_file($_FILES["filex"]["tmp_name"], $lokasi)) {
    //         $baca = fopen($lokasi, "r");
    //         $isifile = fread($baca, filesize($lokasi));
    //         fclose($baca);
    //         $hasil = openssl_decrypt(base64_decode($isifile), "AES-256-CBC", "key12345678*", 0, "0123456789abcdef");
    //         echo $hasil;
    //     } else {
    //     }
    // }

    public function restore()
    {
        $namafilex = $this->request->getVar("namafilex");
        $lokasi = "restore/" . $namafilex;
        if (move_uploaded_file($_FILES["filex"]["tmp_name"], $lokasi)) {
            $baca = fopen($lokasi, "r");
            $isifile = fread($baca, filesize($lokasi));
            fclose($baca);
            $hasil = openssl_decrypt(base64_decode($isifile), "AES-256-CBC", "key12345678*", 0, "0123456789abcdef");
            $fix = json_decode($hasil);
            $dtx = new Mdata();
            $total = 0;
            $berhasil = 0;
            $gagal = 0;
            $dtx->HapusBukuAll();
            foreach ($fix as $t) {
                $total++;
                $kode = $t->Kode_Buku;
                $judul = $t->Judul;
                $isbn = $t->ISBN;
                $pengarang = $t->ID_Pengarang;
                $penerbit = $t->ID_Penerbit;
                $tahun = $t->Tahun_Terbit;
                $rak = $t->Rak;
                $cek = $dtx->TambahData($kode, $judul, $isbn, $pengarang, $penerbit, $tahun, $rak);
                if ($cek == "1") {
                    $berhasil++;
                } else {
                    $gagal++;
                }
            }
            unlink($lokasi);
            echo "Total: $total, Berhasil: $berhasil, Gagal: $gagal";
        } else {
            echo "File Gagal di Upload";
        }
    }
}
