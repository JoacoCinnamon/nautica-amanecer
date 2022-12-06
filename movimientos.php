<?php include('./public/templates/header.php'); ?>
<?php include('./src/procesarMovimientos.php'); ?>

<div class="col-12 py-4">
  <script defer src="./public/js/validarMovimientos.js"></script>

  <?php if (!empty($alert)) : ?>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
      </symbol>
      <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
      </symbol>
      <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
      </symbol>
    </svg>
    <div class="alert alert-<?= $alert["res"]["status"]; ?> d-flex align-items-center alert-dismissible fade show" role="alert">
      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="<?= ucfirst($alert["res"]["status"]) ?>:">
        <use xlink:href="#<?= $alert["res"]["icon"] ?>" />
      </svg>
      <div class="ml-2">
        <strong><?= $alert["res"]["strong"]; ?></strong> <?= $alert["res"]["msg"]; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">
      <div class="container text-center">
        <div class="row justify-content-end">
          <div class="col-4">
            <h3 class="card-title text-center">Movimientos</h3>
          </div>
          <div class="col-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMovimientos"><i class="bi bi-plus-lg"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table">
        <table id="tablaMovimientos" class="table">
          <thead>
            <tr>
              <th>Embarcacion</th>
              <th>Rey</th>
              <th>Cliente</th>
              <th>DNI</th>
              <th>Amarra</th>
              <th>Pasillo</th>
              <th>Fecha Desde</th>
              <th>Fecha Hasta</th>
            </tr>
          </thead>
          <tbody>
            <?php $movimientos = Movimiento::selectAllMovimientos(); ?>
            <?php foreach ((array) $movimientos as $movimientoActual) : ?>
              <tr>
                <?php
                $embarcacionActual = Embarcacion::selectEmbarcacionById($movimientoActual->id_embarcacion);
                $amarraActual = Amarra::selectAmarraById($movimientoActual->id_amarra);
                $clienteActual = Cliente::selectClienteById($embarcacionActual->id_cliente);
                ?>
                <td><?= $embarcacionActual->nombre ?></td>
                <td><?= $embarcacionActual->rey ?></td>
                <td><?= $clienteActual->apellido_nombre ?></td>
                <td><?= $clienteActual->dni ?></td>
                <td><?= $amarraActual->id ?></td>
                <td><?= "Pasillo $amarraActual->pasillo" ?></td>
                <td><?= parsearFecha($movimientoActual->fecha_desde) ?></td>
                <td><?= parsearFecha($movimientoActual->fecha_hasta) ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal para agregar movimientos -->
<div class="modal fade" id="modalMovimientos" tabindex="-1" aria-labelledby="modalTitulo" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-center" id="modalTitulo">Movimientos</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="movimientos.php" method="POST" id="formMovimientos" enctype="multipart/form-data">

          <input type="hidden" name="id" id="id">

          <div class="row gx-5 gy-2 py-2 mb-2">
            <div class="col-sm">
              <div class="form-floating mb-3">
                <select class="form-select" aria-label="selectEmbarcaciones" name="idEmbarcacion" id="idEmbarcacion">
                  <option value="0">Seleccione la embarcación...</option>
                  <?php $embarcaciones = Embarcacion::selectEmbarcacionesByEstado(1); ?>
                  <?php foreach ((array) $embarcaciones as $embarcacionActual) : ?>
                    <option value="<?= $embarcacionActual->id; ?>"><?= "$embarcacionActual->nombre - $embarcacionActual->rey" ?>
                      <?php $estaEmbarcado = Movimiento::selectEmbarcado($embarcacionActual->id); ?>
                      <?php if ($estaEmbarcado) print " - En la amarra N°" . $estaEmbarcado->id_amarra; ?> </option>
                  <?php endforeach ?>
                </select>
                <label for="idEmbarcacion">Embarcaciones activas</label>
              </div>
            </div>

            <div class="col-sm">
              <div class="form-floating mb-3">
                <select class="form-select" aria-label="selectAmarras" name="idAmarra" id="idAmarra">
                  <option value="0">Seleccione la amarra...</option>
                  <?php $amarras = Amarra::selectAmarrasByEstado(0); ?>
                  <?php foreach ((array) $amarras as $amarraActual) : ?>
                    <option value="<?= $amarraActual->id; ?>"><?= "N°$amarraActual->id - Pasillo $amarraActual->pasillo" ?> </option>
                  <?php endforeach ?>
                </select>
                <label for="idEmbarcacion">Amarras desocupadas</label>
              </div>
            </div>
          </div>

          <!-- <div class="form-floating mb-3">
            <input type="date" class="form-control date" id="fecha_desde">
            <label for="fecha_desde">Fecha desde</label>
          </div> -->

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" name="guardarMovimiento">Guardar</button>
          </div>
        </form>
      </div>


    </div>
  </div>
</div>

<script>
  /**
   * Datatables
   */
  const dtMovimientos = new simpleDatatables.DataTable("#tablaMovimientos", {
    // Acá iria la traduccion al español si me dejara instalarlo
  });
</script>


<?php include("./public/templates/footer.php"); ?>