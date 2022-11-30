<?php include('./public/templates/header.php'); ?>
<?php include('./src/procesarMovimientos.php'); ?>

<div class="col-12 py-4">
  <script defer src="./public/js/validarMovimientos.js"></script>

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
                <td><?= $movimientoActual->fecha_desde ?></td>
                <td><?= parsearFecha($movimientoActual->fecha_hasta) ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

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