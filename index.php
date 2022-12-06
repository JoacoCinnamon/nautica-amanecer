<?php include('./public/templates/header.php'); ?>

<?php
define("DIR", "./public/img/DALLE/");
$imagenesInicio = array_slice(scandir(DIR), 2);
?>


<div class="container px-4 px-lg-5 h-100">
  <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
    <div class="col-lg-8 align-self-end">

      <div id="carouselIndex" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="<?= DIR . $imagenesInicio[0] ?>" class="d-block w-100" alt="Imagen Náutica 1">
          </div>
          <?php
          for ($i = 1; $i < sizeof($imagenesInicio) - 1; $i++) {
            if ($imagenesInicio[$i] != "." && $imagenesInicio[$i] != "..") { ?>
              <div class="carousel-item">
                <img src="<?= DIR . $imagenesInicio[$i] ?>" class="d-block w-100" alt="<?= "Imagen Náutica " . $i - 1; ?>">
              </div>
          <?php
            }
          } ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndex" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselIndex" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>


    </div>
  </div>
</div>




<?php include("./public/templates/footer.php"); ?>