<?php include('./templates/header.php'); ?>
<?php include('./src/private/procesarClientes.php'); ?>

<div class="row">

  <script defer src="./js/validarClientes.js"></script>

  <form name="clientes" id="formClientes" action="clientes.php" method="POST" class="col-md-4 py-3">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title text-center">Clientes</h3>
      </div>
      <div class="card-body">

        <input type="hidden" name="id" value="<?php echo $id ?>">

        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="apellido_nombre" value="<?php echo $apellido_nombre ?>" id="apellido_nombre" maxlength="80">
          <label for="nombre">Apellidos y Nombres</label>
          <p></p>
        </div>

        <div class="form-floating mb-3">
          <input type="email" class="form-control" name="email" value="<?php echo $email ?>" id="email">
          <label for="email">Email</label>
        </div>

        <div class="row gy-2 gx-3 align-items-center">
          <div class="col">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="dni" value="<?php echo $dni ?>" id="dni" maxlength="9">
              <label for="dni">DNI</label>
            </div>
          </div>

          <div class="col">
            <div class="form-floating mb-3">
              <label class="visually-hidden" for="movil">Movil</label>
              <div class="input-group">
                <div class="input-group-text">+54</div>
                <input type="text" class="form-control" name="movil" value="<?php echo $movil ?>" id="movil" placeholder="Movil" maxlength="10">
              </div>
            </div>
          </div>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="domicilio" value="<?php echo $domicilio ?>" id="domicilio" maxlength="40">
          <label for="domicilio">Domicilio Completo</label>
        </div>


        <!-- ESTADO DEL CLIENTE -->
        <?php if ($id != 0) { ?>
          <div class="row gx-5 gy-2 py-2 mb-2">
            <!-- Hay un cliente seleccionado, por lo tanto puede cambiar el estado -->

            <div class="col-sm">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="estado" id="clienteActivo" value="1" <?php if ($estado == 1) echo "checked"; ?>>
                <label class="form-check-label" for="clienteActivo">Activo</label>
              </div>
            </div>

            <div class="col-sm">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="estado" id="clienteBaja" value="0" <?php if ($estado == 0) echo "checked"; ?>>
                <label class="form-check-label" for="clienteBaja">Baja</label>
              </div>
            </div>

          </div>
        <?php } ?>


        <div class="row gx-3 gy-2 align-items-center">
          <?php if ($id == 0) { ?>
            <!-- No hay ningún cliente seleccionado -->
            <button type="submit" class="w-100 btn btn-lg btn-primary" name="agregarClientes" id="agregarClientes" value="Agregar">Agregar</button>
          <?php } else { ?>
            <!-- Hay un cliente seleccionado -->
            <div class="col-sm">
              <button type="submit" class="w-100 btn btn-lg btn-info" name="editarClientes" id="editarClientes" value="Actualizar">Actualizar</button>
            </div>
            <div class="col-sm">
              <button type="button" class="w-100 btn btn-lg btn-danger" name="cancelarEditar" id="cancelarEditar" value="Cancelar" onClick="location.href='clientes.php'">Cancelar</button>
            </div>
          <?php } ?>
        </div>

      </div>
    </div>
  </form>

  <!-- Tabla con los datos de los clientes -->

  <div class="col-md-8 py-3">
    <div class="table">
      <table id="tablaClientes" class="table">
        <thead>
          <tr>
            <th>N°</th>
            <th>Apellido y Nombre</th>
            <th>Email</th>
            <th>DNI</th>
            <th>Celular</th>
            <th>Domicilio</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $clientes = Cliente::selectAllClientes();
          foreach ($clientes as $clienteActual) { ?>
            <tr>
              <td><?php print $clienteActual->id; ?></td>
              <td><?php print $clienteActual->apellido_nombre; ?> </td>
              <td><?php print $clienteActual->email; ?></td>
              <td><?php print $clienteActual->dni; ?></td>
              <td><?php print $clienteActual->movil; ?></td>
              <td><?php print $clienteActual->domicilio; ?></td>
              <td><?php print getEstadoToString($clienteActual); ?></td>
              <td>
                <a href="clientes.php?id=<?php echo $clienteActual->id; ?>"><i class="bi bi-pencil-square text-success"></i></a>
              </td>

            </tr>
          <?php
          }
          ?>
      </table>
    </div>
  </div>

</div>

<script>
  /**
   * Datatables
   */
  const dataTable = new simpleDatatables.DataTable("#tablaClientes", {
    // Acá iria la traduccion al español si me dejara instalarlo
  });
</script>


<?php include("./templates/footer.php"); ?>