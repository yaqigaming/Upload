<?php
$directory = 'uploads';

// Membuat direktori uploads jika belum ada
if (!is_dir($directory)) {
    mkdir($directory, 0777, true);
    // echo "Direktori $directory berhasil dibuat.";
} else {
    // echo "Direktori $directory sudah ada.";
}

$uploadMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            // echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            // echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        // echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        // echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowedExtensions = array("jpg", "jpeg", "png", "gif", "mp4", "avi", "mov");
    if (!in_array($imageFileType, $allowedExtensions)) {
        // echo "Sorry, only JPG, JPEG, PNG, GIF, MP4, AVI, MOV files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $uploadMessage = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $uploadMessage = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            $uploadMessage = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Halaman Web Menarik</title>
   <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <h1>Selamat Datang!</h1>
    <p>Silahkan Upload gambar dan video di halaman kami.</p>
  </header>

  <main>
    <div class="upload-form">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        Select image/video to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
      </form>
    </div>
    
    <?php
    $directory = 'uploads/';
    $files = scandir($directory);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
            if ($fileExtension === 'jpg' || $fileExtension === 'jpeg' || $fileExtension === 'png' || $fileExtension === 'gif') {
                echo "<div class='gallery-item'><div class='image'><img src='$directory$file' alt='Uploaded Image' width='300'></div></div>";
            } elseif ($fileExtension === 'mp4' || $fileExtension === 'avi' || $fileExtension === 'mov') {
                echo "<div class='gallery-item'><video width='320' height='240' controls>
                        <source src='$directory$file' type='video/$fileExtension'>
                        Your browser does not support the video tag.
                    </video></div>";
            }
        }
    }
    ?>

    <!-- Menampilkan pesan setelah upload -->
    <p class="hide-text"><?php echo $uploadMessage; ?></p>

  </main>
</body>
</html>
