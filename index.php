<?php include('./public/templates/header.php'); ?>

<?php
$dir = "./public/img/DALLE/";
$imagenesInicio = array_slice(scandir($dir), 2);
$imagenRandom = $imagenesInicio[rand(2, count($imagenesInicio) - 2)];
?>

<style>
  .index {
    background-image: linear-gradient(0deg,
        rgba(0, 0, 0, 0.4),
        rgba(0, 0, 0, 0.4)),
      url("/Nautica_Amanecer/public/img/DALLE/<?= $imagenRandom ?>");
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    width: 100%;
    opacity: 0.8;
  }
</style>

<div class="index d-flex align-items-center justify-content-center">
  <h1 class="text-center text-white">¡Bienvenido!</h1>
</div>




<?php include("./public/templates/footer.php"); ?>