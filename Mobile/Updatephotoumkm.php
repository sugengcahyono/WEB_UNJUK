<?php

/**
 * Digunakan untuk mengupdate photo profile user.
 */

require "../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // post request
    $idakun = $_POST['id_akun'];
    $photo = $_POST['photo'];

    // cek email exist atau tidak
    $sql = "SELECT id_akun FROM umkm WHERE id_akun = '$id_akun' LIMIT 1";
    if ($conn->query($sql)->num_rows == 1) {
        // saving photo
        $photo = str_replace('data:image/png;base64,', '', $photo);
        $photo = str_replace(' ', '+', $photo);
        $data = base64_decode($photo);
        $filename = uniqid() . '.png';
        $file = '../public/img/user-photo/' . $filename;
        file_put_contents($file, $data);

        // get data user
        $sql = "UPDATE umkm SET umkm_foto = '$filename' WHERE id_akun = '$idakun'";
        $result = $conn->query($sql);

        // jika foto profile berhasil diupdate
        if ($result === true) {
            $sql = "SELECT umkm_foto FROM umkm WHERE id_akun = '$idakun' LIMIT 1";
            $result = $conn->query($sql);
            $photo = $result->fetch_assoc();

            if ($result->num_rows == 1) {
                $response = array("status" => "success", "message" => "photo umkm berhasil diupdate", "data" => $photo);
            } else {
                $response = array("status" => "success", "message" => "photo umkm berhasil diupdate");
            }
        } else {
            $response = array("status" => "error", "message" => "photo umkm gagal diupdate");
        }
    }else{
        $response = array("status"=> "error", "message"=> "umkm tidak ditemukan");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "method is not post");
}

// show response
echo json_encode($response);
