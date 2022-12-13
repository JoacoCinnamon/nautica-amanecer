<?php include('./public/templates/header.php'); ?>
<?php include('./src/procesarEmbarcaciones.php'); ?>

<div class="row">
  <script defer src="./public/js/validarEmbarcaciones.js"></script>

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
          <?php $clientes = Cliente::selectClientesByEstado(1); ?>
          <select class="form-select" aria-label="selectClientes" name="idCliente" id="idCliente" <?php if (!$clientes) print "disabled";  ?>>
            <option value="0"><?= (!$clientes) ? "No hay clientes aún..." : "Seleccione el dueño..." ?></option>
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