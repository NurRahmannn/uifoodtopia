<?php
session_start();
require "config/connect.php";
if (isset($_SESSION['login'])) {
  $iduser = $_SESSION['iduser'];
  $name_user = $_SESSION['username'];
} else {
  header('Location: index.php');
}

//membuat path file gambar
$queryedit = mysqli_query($koneksi, "SELECT * FROM user WHERE id = $iduser");
$profiluser = mysqli_fetch_assoc($queryedit);
$name_img_user = $profiluser['photo'];
$path_foto = "assets/img/profile/" . $name_img_user;

$target_dir = "assets/img/profile/";
$ppp = basename($_FILES["imguser"]["name"]);
$ubahnama = $iduser . "_" . $name_user . "_" . "$ppp";
$target_file = $target_dir . $ubahnama;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


if (isset($_POST['edit'])) {

  if (!empty($_POST['username'])) {
    $username = $_POST['username'];
    $editname = mysqli_query($koneksi, "UPDATE user SET name ='$username' WHERE id=$iduser ");
  }

  //jika file tidak kosong
  if (!empty($_FILES["imguser"]["name"])) {

    $check = getimagesize($_FILES["imguser"]["tmp_name"]);
    if ($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      echo "File is not an image.";
      $uploadOk = 0;
    }

    if ( $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif") {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }

    if ($_FILES["imguser"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }

    if (file_exists($path_foto)) {
      unlink($path_foto);
    }
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    } else {
      if (move_uploaded_file($_FILES["imguser"]["tmp_name"], $target_file)) {
        $editimg = mysqli_query($koneksi, "UPDATE user SET photo = '$ubahnama' WHERE id = $iduser ");
        echo "The file " . htmlspecialchars(basename($_FILES["imguser"]["name"])) . " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }

  }

  header('Location: profile.php');
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EDIT</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="shortcut icon" type="image/png" href="assets/img/title.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .center-image {
      display: block;
      margin: auto;
    }
  </style>
</head>

<body style="background-color: #e0e5de;">

  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <a href="index.php">
              <img src="assets/img/logo.svg" class="center-image" width="300rem" alt="">
            </a>
            <p class="card-title text-center mb-4">Temukan Resep yang Menarik</p>

            <form method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="username" class="form-label">Nama</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan Nama Baru">
              </div>
              <div class="mb-3">
                <label for="foto" class="form-label">Foto Profil</label>
                <input type="file" name="imguser" class="form-control" id="imguser">
              </div>
              <button class="btn btn-success btn-block" name="edit">Edit</button>
              <a href="profile.php" class="btn btn-danger btn-block" name="cancel">Cancel</a>
            </form>

            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>