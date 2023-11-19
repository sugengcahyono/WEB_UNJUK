<?php
/**
 * Digunakan untuk register manual.
 */

require "../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // post request
    $nama_user = $_POST['nama_user'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $notelp_user = $_POST['notelp_user'];
    $alamat_user = $_POST['alamat_user'];
    $Level = $_POST['Level'];

    // Fungsi untuk memeriksa validitas alamat email
    function is_valid_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Fungsi untuk memeriksa kriteria nama user
    function is_valid_nama_user($nama_user, $conn) {
        $nama_user = mysqli_real_escape_string($conn, $nama_user);
        $checkQuery = "SELECT * FROM akun WHERE nama_user = '$nama_user'";
        $checkResult = $conn->query($checkQuery);
        return (preg_match('/^[A-Za-z\'().]{3,50}$/', $nama_user) == 1 && $checkResult->num_rows === 0);
    }

    // Fungsi untuk memeriksa kriteria alamat user
    function is_valid_alamat_user($alamat_user) {
        return (strlen($alamat_user) >= 3 && strlen($alamat_user) <= 90);
    }

    // Periksa apakah email sudah terdaftar
    $checkQueryEmail = "SELECT * FROM akun WHERE email = '$email'";
    $checkResultEmail = $conn->query($checkQueryEmail);

    if ($checkResultEmail->num_rows > 0) {
        // Email sudah terdaftar, berikan respons error
        $response = array("status" => "error", "message" => "Email sudah terdaftar");
    } else {
        // Periksa apakah email valid
        if (is_valid_email($email)) {
            // Periksa kekuatan kata sandi menggunakan ekspresi reguler
            if (preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*\W).{8,}$/', $password)) {
                // Periksa nomor telepon minimal 11 karakter
                if (strlen($notelp_user) >= 11) {
                    // Periksa kriteria nama user
                    if (is_valid_nama_user($nama_user, $conn)) {
                        // Periksa kriteria alamat user
                        if (is_valid_alamat_user($alamat_user)) {
                            $epassword = password_hash($password, PASSWORD_BCRYPT);

                            // get data user
                            $sql = "INSERT INTO akun (email, password, nama_user, alamat_user, notelp_user, Level) VALUES ('$email', '$epassword',
                             '$nama_user', '$alamat_user', '$notelp_user', 'user')";
                            $result = $conn->query($sql);

                            if ($result === true) {
                                $response = array("status" => "success", "message" => "Register Success");
                            } else {
                                $response = array("status" => "error", "message" => "Register Gagal", "error_details" => $conn->error);
                            }
                        } else {
                            $response = array("status" => "error", "message" => "Alamat minimal 3 karakter dan maksimal 90 karakter");
                        }
                    } else {
                        $response = array("status" => "error", "message" => "Nama tidak memenuhi kriteria atau sudah terdaftar");
                    }
                } else {
                    $response = array("status" => "error", "message" => "Nomor telepon minimal 11 karakter");
                }
            } else {
                $response = array("status" => "error", "message" => "Kata sandi harus memiliki minimal 8 karakter, huruf kapita, angka, dan simbol");
            }
        } else {
            $response = array("status" => "error", "message" => "Alamat email tidak valid");
        }
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// show response
echo json_encode($response);
?>
