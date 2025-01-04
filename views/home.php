<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuanQuach | Cửa hàng đồ chơi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<?php session_start(); ?>
<body>
    <header>
        <?php include('partials/header.php'); ?>
    </header>
    <div class="container-fluid py-5  ">
        <div class="row d-flex">
            <div class="col-2 mb-4">
                <?php include('partials/sidebar.php'); ?>
            </div>
            <div class="col-10">

                <?php include('partials/main.php'); ?>
            </div>

        </div>
    </div>
    </div>
    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>