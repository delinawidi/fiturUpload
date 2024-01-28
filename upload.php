<?php 
require 'fungsi.php';

// Menampilkan data inputan
$belanja = read("SELECT * FROM belanja");

if (isset($_POST["submit"])) {
    // $result = importExcelToDatabase($_FILES['filexls'], $conn);
    $result = importExcel($_FILES['filexls']);

    // kondisi jika benar atau salah maka terdapat pesan yang akan dikirimkan
    if ($result['error']) {
        $error = true;
    } else {
        if ($result['success']) {
            $success = true;
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Input data file</title>
    <!-- link css botstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

    <div style="margin: auto; width: 600px; padding: 20px;">

        <!-- pesan yang akan muncul jika terdapat error -->
        <?php if (isset($error) && $error) : ?>
            <div class="alert alert-danger">
                <ul><?= $result['error']; ?></ul>
            </div>
        <?php endif; ?>

        <!-- form input file -->
        <form action="" method="POST" enctype="multipart/form-data" class="row g-2">
            <div class="col-auto">
                <input class="form-control" type="file" name="filexls" id="formFile">
            </div>
            <div class="col-auto">
                <input type="submit" name="submit" class="btn btn-primary" value="Upload File XLS/XLSX">
            </div>
        </form>

    </div>

    
</body>
</html>