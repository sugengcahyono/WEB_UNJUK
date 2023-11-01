<?php
/**
 * Digunakan untuk register manual.
 */

require "../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // post request
    $idumkm = $_POST['id_umkm'];
    $namaumkm = $_POST['nama_umkm'];
    $jenisusahaumkm = $_POST['Jenis_usahaumkm'];
    $nibumkm  = $_POST['Nib_umkm'];
    $notelpumkm = $_POST['notelp_umkm'];
    $kecamatanumkm = $_POST['kecamatan_umkm'];
    $alamatumkm = $_POST['alamat_umkm'];
    $umkmfoto = $_POST['umkm_foto'];
    $idakun = $_POST['id_akun'];
    

    // get data user
    $sql = "UPDATE umkm SET nama_umkm = '$namaumkm', Jenis_usahaumkm = '$jenisusahaumkm ', Nib_umkm = '$nibumkm', notelp_umkm = '$notelpumkm', kecamatan_umkm = '$kecamatanumkm', alamat_umkm = '$alamatumkm', umkm_foto = '$umkmfoto'  WHERE id_akun = '$idakun'";
    $result = $conn->query($sql);

    if ($result === true) {
        $response = array("status" => "success", "message" => "Data UMKM Berhasil diubah");
    } else {
        $response = array("status" => "error", "message" => "Data Profil Gagal diubah -> $sql", "error_details" => $conn->error);
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// show response
echo json_encode($response);
?>
