<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap con estilo "United" de bootswatch  -->
  <link rel="stylesheet" href="./css/bootstrapUnited.min.css">
  <!-- Iconos de Bootstrap  -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <!-- Script del Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

  <!-- Script simple de JS de internet para mostrar en que sección se está -->
  <script defer src="./js/paginaActual.js"></script>

  <!-- Datatables -->
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

  <!-- favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="./img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./img/favicon-16x16.png">
  <link rel="manifest" href="./site.webmanifest">

  <title>Náutica Amanecer</title>
</head>

<body>
  <!-- HEADER -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-2 border-bottom border-secondary">
      <!-- También fluid -->
      <div class="container">
        <a class="navbar-brand" aria-current="page" href="/Nautica_Amanecer/"> <img src="logo.png" alt="Logo" width="150px" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav ms-auto">
            <li>
              <a class="nav-link" href="clientes.php">Clientes</a>
            </li>
            <li>
              <a class="nav-link" href="embarcacion.php">Embarcaciones</a>
            </li>
            <li>
              <a class="nav-link" href="amarras.php">Amarras</a>
            </li>
            <li>
              <a class="nav-link" href="movimientos.php">Movimientos</a>
            </li>
          </div>
        </div>

      </div>
    </nav>
  </header>
  <!-- Parte de arriba de todos los bodys -->
  <div class="container">
    <div class="row">
      <div class="col-12 py-3">