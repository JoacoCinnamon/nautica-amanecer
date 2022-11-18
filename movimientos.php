<?php include('./public/templates/header.php'); ?>
<?php include('./src/procesarMovimientos.php'); ?>

<div class="col-12 py-4">
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
              <th>Amarra</th>
              <th>Fecha Desde</th>
              <th>Fecha Hasta</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $movimientos = Movimiento::selectAllMovimientos($id, $id_embarcacion, $id_amarra, $fecha_desde, $fecha_hasta);
            foreach ((array) $movimientos as $movimientoActual) {
              $embarcacionActual = Embarcacion::selectEmbarcacionById($movimientoActual->getId_embarcacion());
              $amarraActual = Amarra::selectAmarraById($movimientoActual->getId_amarra());
              $clienteActual = Cliente::selectClienteById($embarcacionActual->id_cliente);
            ?>
              <tr>
                <td><?= "$embarcacionActual->nombre - $clienteActual->apellido_nombre" ?></td>
                <td><?= $amarraActual->id ?></td>
                <td><?= $movimientoActual->getFecha_desde() ?></td>
                <td><?= $movimientoActual->getFecha_hasta() ?></td>
                <td>
                  <a href=""><i class="bi bi-pencil-square text-success"></i></a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include("./modales/modalMovimiento.php"); ?>

<script>
  /**
   * Datatables
   */
  const dtMovimientos = new simpleDatatables.DataTable("#tablaMovimientos", {
    // Acá iria la traduccion al español si me dejara instalarlo
  });
</script>


<?php include("./public/templates/footer.php"); ?>