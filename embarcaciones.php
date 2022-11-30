<?php include('./public/templates/header.php'); ?>
<?php include('./src/procesarEmbarcaciones.php'); ?>

<div class="row">
  <script defer src="./public/js/validarEmbarcaciones.js"></script>

  <form name="embarcaciones" id="formEmbarcaciones" action="embarcaciones.php" method="POST" class="col-md-4 py-3">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title text-center">Embarcaciones</h3>
      </div>
      <div class="card-body">

        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="nombre" value="<?= $nombre ?>" id="nombre" maxlength="30">
          <label for="nombre">Nombre de la embarcación</label>
          <p></p>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="rey" value="<?= $rey ?>" id="rey" maxlength="20">
          <label for="rey">Rey</label>
        </div>


        <div class="form-floating mb-3">
          <select class="form-select" aria-label="selectClientes" name="idCliente" id="idCliente">
            <option value="0">Seleccione el dueño...</option>
            <?php $clientes = Cliente::selectClientesByEstado(1); ?>
            <?php foreach ((array) $clientes as $clienteActual) : ?>
              <option value="<?= $clienteActual->id; ?>" <?php if ($id_cliente == $clienteActual->id) print "selected";
                                                          ?>>
                <?= "$clienteActual->apellido_nombre - $clienteActual->dni" ?> </option>
            <?php endforeach ?>
          </select>
          <label for="idCliente">Dueño</label>
        </div>


        <!-- ESTADO DE LA EMBARCACION -->
        <?php if ($id != 0) : ?>
          <div class="row gx-5 gy-2 py-2 mb-2">
            <!-- Hay una embarcacion seleccionada, por lo tanto puede cambiar el estado -->

            <div class="col-sm">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="estado" id="embarcacionActiva" value="1" <?php if ($estado == 1) echo "checked"; ?>>
                <label class="form-check-label" for="embarcacionActiva">Activa</label>
              </div>
            </div>

            <div class="col-sm">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="estado" id="embarcacionBaja" value="0" <?php if ($estado == 0) echo "checked"; ?>>
                <label class="form-check-label" for="embarcacionBaja">Baja</label>
              </div>
            </div>

          </div>
        <?php endif ?>


        <div class="row gx-3 gy-2 align-items-center">
          <?php if ($id == 0) : ?>
            <!-- No hay ningúna embarcacion seleccionada -->
            <button type="submit" class="w-100 btn btn-lg btn-primary" name="agregarEmbarcaciones" id="agregarEmbarcaciones" value="Agregar">Agregar</button>
          <?php else :  ?>
            <!-- Hay una embarcacion seleccionada -->
            <div class="col-sm">
              <button type="submit" class="w-100 btn btn-lg btn-info" name="editarEmbarcaciones" id="editarEmbarcaciones" value="Actualizar">Actualizar</button>
            </div>
            <div class="col-sm">
              <button type="button" class="w-100 btn btn-lg btn-danger" name="cancelarEditar" id="cancelarEditar" value="Cancelar" onClick="location.href='embarcaciones.php'">Cancelar</button>
            </div>
          <?php endif ?>
        </div>

      </div>
    </div>
  </form>

  <!-- Tabla con los datos de los embarcaciones -->

  <div class="col-md-8 py-3">
    <div class="table">
      <table id="tablaEmbarcaciones" class="table">
        <thead>
          <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>Rey</th>
            <th>Dueño</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php $embarcaciones = Embarcacion::selectAllEmbarcaciones(); ?>
          <?php foreach ((array) $embarcaciones as $embarcacionActual) : ?>
            <tr>
              <td><?= $embarcacionActual->id; ?></td>
              <td><?= $embarcacionActual->nombre; ?></td>
              <td><?= $embarcacionActual->rey; ?></td>
              <td><?= Cliente::selectClienteById($embarcacionActual->id_cliente)->apellido_nombre . " - " .
                    Cliente::selectClienteById($embarcacionActual->id_cliente)->dni; ?></td>
              <td><?= getEstadoToString($embarcacionActual); ?></td>
              <td>
                <a href="embarcaciones.php?id=<?= $embarcacionActual->id; ?>"><i class="bi bi-pencil-square text-success"></i></a>
              </td>
            </tr>
          <?php endforeach ?>
      </table>
    </div>
  </div>

</div>

<script>
  /**
   * Datatables
   */
  const dtEmbarcaciones = new simpleDatatables.DataTable("#tablaEmbarcaciones", {
    perPage: 4,
    perPageSelect: false
    // Acá iria la traduccion al español si me dejara instalarlo
  });
</script>


<?php include("./public/templates/footer.php"); ?>