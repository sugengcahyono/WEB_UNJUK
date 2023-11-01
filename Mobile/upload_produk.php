<?php
/**
 * Digunakan untuk register manual.
 */

require "../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // post request
    $idproduk = $_POST['id_produk'];
    $namaproduk = $_POST['nama_produk'];
    $hargaproduk = $_POST['harga_produk'];
    $kategoriproduk = $_POST['kategori_produk'];
    $deskripsiproduk = $_POST['deskripsi_produk'];
    $pirtproduk = $_POST['pirt_produk'];
    $bpomproduk = $_POST['bpom_produk'];
    $idhalalproduk = $_POST['idhalal_produk'];
    $gambarproduk1 = $_POST['gambar_produk1'];
    $gambarproduk2 = $_POST['gambar_produk2'];
    $gambarproduk3 = $_POST['gambar_produk3'];
    $idumkm = $_POST['id_umkm'];
    

    // get data user
    $sql = "INSERT INTO produk (nama_produk, harga_produk, kategori_produk, deskripsi_produk, pirt_produk, bpom_produk, idhalal_produk, gambar_produk1, gambar_produk2, gambar_produk3, id_umkm) VALUES ('$namaproduk', '$hargaproduk', '$kategoriproduk', '$deskripsiproduk', '$pirtproduk', '$bpomproduk', '$idhalalproduk', '$gambarproduk1', '$gambarproduk2', '$gambarproduk3', '$idumkm')";

    $result = $conn->query($sql);

    if ($result === true) {
        $response = array("status" => "success", "message" => "Upload berhasil");
    } else {
        $response = array("status" => "error", "message" => "Upload Gagal $sql", "error_details" => $conn->error);
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// show response
echo json_encode($response);
?>
