<div class="modal fade" id="modalMovimientos" tabindex="-1" aria-labelledby="modalTitulo" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-center" id="modalTitulo">Movimientos</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="movimientos.php" method="POST" enctype="multipart/form-data">

          <input type="hidden" name="id" id="id">

          <div class="row gx-5 gy-2 py-2 mb-2">
            <div class="col-sm">

              <div class="form-floating mb-3">
                <select class="form-select" aria-label="selectEmbarcaciones" name="idEmbarcacion" id="idEmbarcacion">
                  <option value="0">Seleccione la embarcación...</option>
                  <?php
                  $embarcaciones = Embarcacion::selectEmbarcacionesByEstado(1);
                  foreach ((array) $embarcaciones as $embarcacionActual) {
                    $estaEmbarcado = Movimiento::selectEmbarcado($embarcacionActual->id); ?>
                    <option value="<?= $embarcacionActual->id; ?>" <?php //if ($embarcacion->id == $embarcacionActual->id) print "selected";
                                                                    ?>>
                      <?= "$embarcacionActual->nombre - $embarcacionActual->rey" ?>
                      <?php if ($estaEmbarcado) print " - ubicado en la amarra N°" . $estaEmbarcado->amarra->id_amarra; ?> </option>
                  <?php }
                  ?>
                </select>
                <label for="idEmbarcacion">Embarcaciones activas</label>
              </div>

            </div>

            <div class="col-sm">

              <div class="form-floating mb-3">
                <select class="form-select" aria-label="selectAmarras" name="idAmarra" id="idAmarra">
                  <option value="0">Seleccione la amarra...</option>
                  <?php
                  $amarras = Amarra::selectAmarrasByEstado(0);
                  foreach ((array) $amarras as $amarraActual) { ?>
                    <option value="<?= $amarraActual->id; ?>" <?php //if ($amarra->id == $amarraActual->id) print "selected";
                                                              ?>>
                      <?= "N°$amarraActual->id - Pasillo $amarraActual->pasillo" ?> </option>
                  <?php } ?>
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