<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap con estilo "United" de bootswatch  -->
  <link rel="stylesheet" href="./public/css/bootstrapUnited.min.css">
  <!-- Iconos de Bootstrap  -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <!-- Script del Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

  <!-- Script simple de JS de internet para mostrar en que sección se está -->
  <script defer src="./public/js/paginaActual.js"></script>

  <!-- Datatables -->
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

  <!-- favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="./public/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="./public/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./public/img/favicon-16x16.png">
  <link rel="manifest" href="./public/site.webmanifest">

  <title>Náutica Amanecer</title>
</head>

<body>

  <!-- Contenido Stático del index (responsivo) -->
  <link rel="stylesheet" href="./public/css/index.css" />
  <?php $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?>
  <?php if ($uri == "/Nautica_Amanecer/" || $uri == "/Nautica_Amanecer/index.php") : ?>
    <script defer src="./public/js/welcome.js"></script>
  <?php endif ?>

  <!-- Navbar -->
  <?php include('./public/templates/navbar.php'); ?>

  <!-- Parte de arriba de todos los bodys -->
  <div class="container py-3">