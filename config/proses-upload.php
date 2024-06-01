<?php
session_start();

if (isset($_SESSION['login'])) {
    $iduser = $_SESSION['iduser'];
} else {
    header('Location: ../index.php');
}

require "connect.php";
$waktu_upload = date("Y:m:d H:i:s");

if (isset($_POST['upload'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $bahan = $_POST['bahan'];
    $langkah = $_POST['langkah'];
    $kategori = $_POST['select_kategori'];

    //upload img ke databse dan folder
    $resep_img_name = $_FILES["foodimg"]["name"];
    $temp_img_resep = $_FILES["foodimg"]["tmp_name"];
    $ukuran_img_rsp = $_FILES['foodimg']['size'];
    $new_resep_img_name = $judul . "_" . $resep_img_name;

    $folder_img_resep = "../assets/img/resep_img/" . $new_resep_img_name;
    $tipe_img = strtolower(pathinfo($folder_img_resep, PATHINFO_EXTENSION));

    if ($tipe_img == "jpeg" or $tipe_img == "png" or $tipe_img == "jpg") {
        if ($ukuran_img_rsp <= 1000000) {
            if (move_uploaded_file($temp_img_resep, $folder_img_resep)) {
                echo "seucces";
            } else {
                echo "failed";
            }

            $queryupload = "INSERT INTO resep (judul,deskripsi,bahan,langkah,foto,kategori_id,user_id,tanggal) VALUES ('$judul','$deskripsi','$bahan','$langkah','$new_resep_img_name','$kategori','$iduser','$waktu_upload')";
            $upload = mysqli_query($koneksi, $queryupload);
            if ($upload) {
                header('Location: ../profile.php');
            } else {
                echo "gagal";
            }
        }else{
            echo "file terlalu besar";
        }
    } else {
        echo "maaf harus jpeg, png atau jpg";
        
    }


}