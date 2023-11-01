<?php
/**
 * Digunakan untuk register manual.
 */

require "../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // post request
    $idakun = $_POST['id_akun'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $namauser  = $_POST['nama_user'];
    $alamatuser = $_POST['alamat_user'];
    $notelpuser = $_POST['notelp_user'];
    $userfoto = $_POST['user_foto'];
    $kodeotp = $_POST['Kode_OTP'];
    $level = $_POST['Level'];
    

    // get data user
    $sql = "UPDATE akun SET nama_user = '$namauser', notelp_user = '$notelpuser ', alamat_user = '$alamatuser', user_foto = '$userfoto' WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result === true) {
        $response = array("status" => "success", "message" => "Data Profil Berhasil diubah");
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
