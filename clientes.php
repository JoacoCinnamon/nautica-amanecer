<?php include('./public/templates/header.php'); ?>
<?php include('./src/procesarClientes.php'); ?>

<div class="row">
  <script defer src="./public/js/validarClientes.js"></script>

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

  <form name="clientes" id="formClientes" action="clientes.php" method="POST" class="col-md-4 py-3">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title text-center">Clientes</h3>
      </div>
      <div class="card-body">

        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="apellido_nombre" value="<?= $apellido_nombre ?>" id="apellido_nombre" maxlength="80">
          <label for="nombre">Apellidos y Nombres</label>
          <p></p>
        </div>

        <div class="form-floating mb-3">
          <input type="email" class="form-control" name="email" value="<?= $email ?>" id="email">
          <label for="email">Email</label>
        </div>

        <div class="row gy-2 gx-3 align-items-center">
          <div class="col">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="dni" value="<?= $dni ?>" id="dni" maxlength="9">
              <label for="dni">DNI</label>
            </div>
          </div>

          <div class="col">
            <div class="form-floating mb-3">
              <label class="visually-hidden" for="movil">Movil</label>
              <div class="input-group">
                <div class="input-group-text">+54</div>
                <input type="text" class="form-control" name="movil" value="<?= $movil ?>" id="movil" placeholder="Movil" maxlength="10">
              </div>
            </div>
          </div>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="domicilio" value="<?= $domicilio ?>" id="domicilio" maxlength="40">
          <label for="domicilio">Domicilio Completo</label>
        </div>


        <!-- ESTADO DEL CLIENTE -->
        <?php if ($id != 0) : ?>
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
        <?php endif ?>


        <div class="row gx-3 gy-2 align-items-center">
          <?php if ($id == 0) : ?>
            <!-- No hay ningún cliente seleccionado -->
            <button type="submit" class="w-100 btn btn-lg btn-primary" name="agregarClientes" id="agregarClientes" value="Agregar">Agregar</button>
          <?php else : ?>
            <!-- Hay un cliente seleccionado -->
            <div class="col-sm">
              <button type="submit" class="w-100 btn btn-lg btn-info" name="editarClientes" id="editarClientes" value="Actualizar">Actualizar</button>
            </div>
            <div class="col-sm">
              <button type="button" class="w-100 btn btn-lg btn-danger" name="cancelarEditar" id="cancelarEditar" value="Cancelar" onClick="location.href='clientes.php'">Cancelar</button>
            </div>
          <?php endif ?>
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
          <?php $clientes = Cliente::selectAllClientes(); ?>
          <?php foreach ((array) $clientes as $clienteActual) : ?>
            <tr>
              <td><?= $clienteActual->id; ?></td>
              <td><?= $clienteActual->apellido_nombre; ?> </td>
              <td><?= $clienteActual->email; ?></td>
              <td><?= $clienteActual->dni; ?></td>
              <td><?= $clienteActual->movil; ?></td>
              <td><?= $clienteActual->domicilio; ?></td>
              <td><?= getEstadoToString($clienteActual); ?></td>
              <td>
                <a href="clientes.php?id=<?= $clienteActual->id; ?>"><i class="bi bi-pencil-square text-success"></i></a>
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
  const dtClientes = new simpleDatatables.DataTable("#tablaClientes", {
    perPage: 4,
    perPageSelect: false
    // Acá iria la traduccion al español si me dejara instalarlo
  });
</script>


<?php include("./public/templates/footer.php"); ?>