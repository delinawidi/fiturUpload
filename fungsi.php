<?php 
// melakukan input dari php office | file depedensi untuk input file
require 'vendor/autoload.php';

// koneksi ke mysql dan database
$conn = mysqli_connect("localhost", "root", "", "perpustakaan");

// Fungsi untuk import Excel ke database
function importExcel($fileData) {
    global $conn;
    $err = "";
    $ekstensi = "";
    $success = "";

    // cek file yang di upload
    $file_name = $fileData['name']; // untuk mendapatkan nama file yang di upload
    $file_tmp = $fileData['tmp_name']; // untuk mendapatkan temporary data

    // pesan apabila file belum di unggah
    if (empty($file_name)) {
        $err .= "<li>Silahkan masukkan file anda!</li>";
    } else {
        $ekstensi = pathinfo($file_name)['extension'];

        // ekstensi yang di bolehkan
        $ekstensi_allowed = ["xls", "xlsx", "csv"];

        // cek apakah ekstensi file sesuai yang diizinkan
        if (!in_array($ekstensi, $ekstensi_allowed)) {
            $err .= "<li>Masukkan file tipe (xls, xlsx, atau csv) karena tipe file yang anda masukkan adalah tipe $ekstensi</b> </li>";
        }
    }

    // mengambil isi file data
    if (empty($err)) {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_tmp);
        $spreadsheet = $reader->load($file_tmp);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        // Memeriksa apakah setidaknya ada satu baris data | isi file kosong
        if (count($sheetData) <= 1) {
            $err .= "<li>File kosong atau tidak berisi data yang dapat diimpor.</li>";
        } else {
            $jumlahData = 0;
            for ($i = 1; $i < count($sheetData); $i++) {
                $transaksi = $sheetData[$i]['0'];
                $produk = $sheetData[$i]['1'];
                
                // Memeriksa apakah setidaknya satu kolom memiliki data yang relevan | isi file terdapat tabel dan baris namun kosong
                if (empty($transaksi) && empty($produk)) {
                    $err .= "<li>Setidaknya satu baris data harus berisi informasi yang relevan.</li>";
                    break;  // Berhenti jika satu baris data kosong
                }
                
                // melakukan query untuk memasukkan data ke sql (database)
                $query = "INSERT INTO 
                            belanja(transaksi, produk) 
                            VALUES('$transaksi', '$produk')
                        ";
                mysqli_query($conn, $query);
                $jumlahData++;
            }

            if ($jumlahData > 0) {
                // $success = "$jumlahData data berhasil di tambahkan";
                echo "
                    <script>
                        alert('$jumlahData Data berhasil di tambahkan');
                        window.location.href = 'index.php';
                    </script>
                ";
                exit;
            }
        }
        
    }

    // Return result
    return [
        'error' => $err,
        // 'success' => $success
    ];
}

?>