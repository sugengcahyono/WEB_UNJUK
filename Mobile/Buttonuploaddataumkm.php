<?php
/**
 * Digunakan untuk register manual.
 */

require "../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // post request
    $idakun = $_POST['id_akun'];
    $idumkm = $_POST['id_umkm'];
    $namaumkm = $_POST['nama_umkm'];
    $jenisusahaumkm = $_POST['Jenis_usahaumkm'];
    $nibumkm  = $_POST['Nib_umkm'];
    $notelpumkm = $_POST['notelp_umkm'];
    $kecamatanumkm = $_POST['kecamatan_umkm'];
    $alamatumkm = $_POST['alamat_umkm'];
    $umkmfoto = $_POST['umkm_foto'];

    // Check if id_akun already has an id_umkm
    $checkSql = "SELECT id_umkm FROM umkm WHERE id_akun = '$idakun'";
    $checkResult = $conn->query($checkSql);
    $idUmkm = $checkResult->fetch_assoc();

    if ($checkResult && $checkResult->num_rows > 0) {
        // id_akun already has an id_umkm, so we can't insert a new one
        $response = array("status" => "error", "message" => "Data UMKM sudah terisi");
    } else {
        // Validasi nama UMKM (hanya huruf, 5-30 karakter, tidak mengandung emotikon dan karakter khusus)
        if (!preg_match("/^[a-zA-Z0-9 ]{5,30}$/", $namaumkm) || ctype_digit($namaumkm) || preg_match("/[^\w\s]/", $namaumkm)) {
            $response = array("status" => "error", "message" => "Nama UMKM harus terdiri dari 5-30 karakter, dapat mengandung huruf dan angka, tapi tidak boleh hanya angka, dan tidak boleh mengandung emotikon atau karakter khusus");
            echo json_encode($response);
            exit;
        }

        // Validasi NIB (13 digit angka)
        if (!preg_match("/^\d{13}$/", $nibumkm)) {
            $response = array("status" => "error", "message" => "NIB harus terdiri dari 13 digit angka");
            echo json_encode($response);
            exit;
        }

        // Validasi nomor telepon (minimal 11 digit, maksimal 13 digit angka)
        if (!preg_match("/^\d{11,13}$/", $notelpumkm)) {
            $response = array("status" => "error", "message" => "Nomor telepon harus terdiri dari 11 hingga 13 digit angka");
            echo json_encode($response);
            exit;
        }

        // Validasi alamat (maksimal 90 karakter)
        if (strlen($alamatumkm) > 90) {
            $response = array("status" => "error", "message" => "Alamat maksimal 90 karakter");
            echo json_encode($response);
            exit;
        }

        // Validasi jenis usaha tidak kosong
        if (empty($jenisusahaumkm) || $jenisusahaumkm === "Pilih Jenis Usaha") {
            $response = array("status" => "error", "message" => "Silakan Pilih Jenis Usaha");
            echo json_encode($response);
            exit;
        }

        // Validasi kecamatan tidak kosong
        if (empty($kecamatanumkm) || $kecamatanumkm === "Pilih Kecamatan") {
            $response = array("status" => "error", "message" => "Silakan Pilih Kecamatan");
            echo json_encode($response);
            exit;
        }

        // Check if namaumkm already exists in the database
        $checkNamaUmkmSql = "SELECT id_umkm FROM umkm WHERE nama_umkm = '$namaumkm'";
        $checkNamaUmkmResult = $conn->query($checkNamaUmkmSql);

        if ($checkNamaUmkmResult->num_rows > 0) {
            // Nama UMKM sudah terdaftar, berikan respons error
            $response = array("status" => "error", "message" => "Nama UMKM sudah terdaftar");
            echo json_encode($response);
            exit;
        }

        // Check if NIB already exists in the database
        $checkNIBSql = "SELECT id_umkm FROM umkm WHERE Nib_umkm = '$nibumkm'";
        $checkNIBResult = $conn->query($checkNIBSql);

        if ($checkNIBResult->num_rows > 0) {
            // NIB sudah terdaftar, berikan respons error
            $response = array("status" => "error", "message" => "NIB sudah terdaftar");
            echo json_encode($response);
            exit;
        }

        // Perform the INSERT operation
        $sql = "INSERT INTO umkm (id_umkm, id_akun, nama_umkm, Jenis_usahaumkm, Nib_umkm, notelp_umkm, kecamatan_umkm, alamat_umkm, umkm_foto) VALUES ('$idumkm', '$idakun', '$namaumkm', '$jenisusahaumkm', '$nibumkm', '$notelpumkm', '$kecamatanumkm', '$alamatumkm', '$umkmfoto')";
        $result = $conn->query($sql);

        if ($result == true) {
            $checkSql = "SELECT id_umkm FROM umkm WHERE id_akun = '$idakun'";
            $checkResult = $conn->query($checkSql);
            $idUmkm = $checkResult->fetch_assoc();

            $response = array("status" => "success", "message" => "Data inserted successfully", "id_umkm" => $idUmkm['id_umkm']);
        } else {
            $response = array("status" => "error", "message" => "Data insertion failed -> $sql", "error_details" => $conn->error);
        }
    }

    // Close the connection
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// Show response
echo json_encode($response);
?>
