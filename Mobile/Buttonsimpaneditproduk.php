<?php
/**
 * Digunakan untuk mengupdate data produk.
 */

require "../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Post request
    $idproduk = $_POST['id_produk']; // Anda memerlukan ID produk yang akan diperbarui
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
    

    // Perbarui data produk dengan menggunakan UPDATE query
    $sql = "UPDATE produk SET 
            nama_produk = '$namaproduk',
            harga_produk = '$hargaproduk',
            kategori_produk = '$kategoriproduk',
            deskripsi_produk = '$deskripsiproduk',
            pirt_produk = '$pirtproduk',
            bpom_produk = '$bpomproduk',
            idhalal_produk = '$idhalalproduk',
            gambar_produk1 = '$gambarproduk1',
            gambar_produk2 = '$gambarproduk2',
            gambar_produk3 = '$gambarproduk3'
        WHERE id_produk = $idproduk";

    $result = $conn->query($sql);

    if ($result === true) {
        $response = array("status" => "success", "message" => "Data produk berhasil diperbarui");
    } else {
        $response = array("status" => "error", "message" => "Gagal memperbarui data produk: $sql", "error_details" => $conn->error);
    }

    // Tutup koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "Bukan metode POST");
}

// Tampilkan respons
echo json_encode($response);
?>
