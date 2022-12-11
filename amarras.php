<?php include('./public/templates/header.php'); ?>
<?php include('./src/procesarAmarras.php'); ?>

<div class="row justify-content-center">
  <script defer src="./public/js/validarAmarras.js"></script>


  <?php if (!empty($alert)) : ?>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol id="success" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
      </symbol>
      <symbol id="info" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
      </symbol>
      <symbol id="danger" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
      </symbol>
    </svg>
    <div class="alert alert-<?= $alert["res"]["status"]; ?> d-flex align-items-center alert-dismissible fade show" role="alert">
      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="<?= ucfirst($alert["res"]["status"]) ?>:">
        <use xlink:href="#<?= $alert["res"]["status"] ?>" />
      </svg>
      <div class="ml-2">
        <strong><?= $alert["res"]["strong"]; ?></strong> <?= $alert["res"]["msg"]; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  <?php endif; ?>

  <form name="amarras" id="formAmarras" action="amarras.php" method="POST" class="col-md-4 py-3">

    <div class="card">
      <div class="card-header">
        <h3 class="card-title text-center">Amarras</h3>
      </div>
      <div class="card-body">

        <div class="form-floating mb-3">
          <input type="number" class="form-control" name="pasillo" value="<?php print $pasillo; ?>" id="pasillo">
          <label for="floatingNombre">Pasillo</label>
        </div>

        <div class="row gx-3 gy-2 align-items-center">
          <div class="col-auto">

            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="estadoAmarra" id="amarraLibre" value="0" disabled <?php if ($estado == 0) echo "checked"; ?>>
              <label class="form-check-label" for="amarraLibre">Libre</label>
            </div>
          </div>

          <div class="col-auto">

            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="estadoAmarra" id="amarraOcupada" value="1" disabled <?php if ($estado == 1) echo "checked"; ?>>
              <label class="form-check-label" for="amarraOcupada">Ocupada</label>
            </div>
          </div>

          <div class="row gx-3 gy-2 py-2">
            <div class="col-sm">
              <button type="submit" class="w-100 btn btn-lg btn-primary" name="botonAmarras" value="Agregar" <?php if ($seSeleccionoAmarra) echo "disabled"; ?>>Agregar</button>
            </div>
            <div class="col-sm">
              <button type="submit" class="w-100 btn btn-lg btn-dark" name="botonAmarras" value="Editar" <?php if (!$seSeleccionoAmarra) echo "disabled"; ?>>Editar</button>
            </div>
            <div class="col-sm">
              <button type="submit" class="w-100 btn btn-lg btn-danger" name="botonAmarras" value="Eliminar" <?php if (!$seSeleccionoAmarra) echo "disabled"; ?>>Eliminar</button>
            </div>
          </div>

          <div class="input-group">
            <select class="form-select" id="idAmarra" name="idAmarra">
              <option value="0">Lista de amarras</option>
              <?php
              $amarras = Amarra::selectAllAmarras();
              foreach ((array) $amarras as $amarraActual) :
                $estadoString = getEstadoToString($amarraActual);
              ?>
                <option value="<?= $amarraActual->id; ?>" <?php if ($id == $amarraActual->id) print "selected"; ?>>
                  <?= "N°$amarraActual->id - Pasillo $amarraActual->pasillo - Estado: ($estadoString)" ?> </option>
              <?php endforeach ?>
            </select>
            <button class="btn btn-outline-secondary" name="selectAmarras" <?php if (!$amarras) print "disabled"; ?> type="sumbit">Elegir</button>
          </div>

        </div>
      </div>
    </div>
</div>
</form>


</div>

<?php include("./public/templates/footer.php"); ?>