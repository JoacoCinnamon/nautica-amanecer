<?php include('./templates/header.php'); ?>
<?php include('./src/private/procesarAmarras.php'); ?>

<div class="row justify-content-center">

  <!-- <script src="./js/validarAmarras.js"></script> -->

  <form name="amarras" action="amarras.php" method="POST" class="needs-validation col-md-4 py-3">

    <div class="card">
      <div class="card-header">
        <h3 class="card-title text-center">Amarras</h3>
      </div>
      <div class="card-body">

        <div class="form-floating mb-3">
          <input type="number" class="form-control" name="pasillo" value="<?php echo $pasillo; ?>" id="floatingPasillo">
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
            <select class="form-select" id="inputGroupSelect04" name="idAmarra">
              <option value="0">Lista de amarras</option>
              <?php
              $amarras = Amarra::selectAllAmarras();
              foreach ($amarras as $amarraActual) {
                ($amarraActual->estado == 0)
                  ? $estadoString = " (Libre)"
                  : $estadoString = " (Ocupada)";
              ?>
                <option value="<?php echo $amarraActual->id; ?>" <?php if ($id == $amarraActual->id) print "selected"; ?>><?php echo "N°$amarraActual->id - Pasillo $amarraActual->pasillo - Estado: $estadoString" ?> </option>
              <?php
              }
              ?>
            </select>
            <button class="btn btn-outline-secondary" name="selectAmarras" <?php if (!$amarras) echo "disabled"; ?> type="sumbit">Elegir</button>
          </div>

        </div>
      </div>
    </div>
</div>
</form>



</div>

<?php include("./templates/footer.php"); ?>