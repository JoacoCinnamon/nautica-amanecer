<?php http_response_code(404); ?>
<?php include('./public/templates/header.php'); ?>

<div class="d-flex align-items-center justify-content-center vh-100">
  <div class="text-center">
    <h1 class="display-1 fw-bold text-primary">404</h1>
    <p class="fs-3"> <span class="text-danger">Opps!</span> Página no encontrada.</p>
    <p class="lead">
      La página que estás buscando no existe.
    </p>
    <a href="index.php" class="btn btn-primary">Volver a inicio</a>
  </div>
</div>

<?php include('./public/templates/footer.php'); ?>