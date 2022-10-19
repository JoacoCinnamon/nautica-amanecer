<?php include('./templates/header.php'); ?>
<?php include('./src/private/procesarClientes.php'); ?>

<div class="row">

  <script defer src="./js/validarClientes.js"></script>

  <form name="clientes" id="formClientes" action="" method="POST" class="col-md-4 py-3">

    <div class="card">
      <div class="card-header">
        <h3 class="card-title text-center">Clientes</h3>
      </div>
      <div class="card-body">

        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="apellido_nombre" value="<?php echo $apellido_nombre ?>" id="apellido_nombre">
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
              <input type="text" class="form-control" name="dni" value="<?php echo $dni ?>" id="dni">
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
          <input type="text" class="form-control" name="domicilio" value="<?php echo $domicilio ?>" id="domicilio">
          <label for="domicilio">Domicilio Completo</label>
        </div>

        <input type="hidden" name="estado" value="<?php echo $estado ?>">

        <div class="col">
          <button type="submit" class="w-100 btn btn-lg btn-primary" name="agregarClientes" id="agregarClientes" value="Agregar">Agregar</button>
        </div>

      </div>
    </div>
  </form>

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
            <!-- <th>Modificar</th> -->
          </tr>
        </thead>
        <tbody>
          <?php
          $cliente = new Cliente(0, "", "", 0, 0, "", 0);
          $clientes = $cliente->selectAllClientes();

          foreach ($clientes as $clienteActual) {
            echo '<tr>';
            echo '<td>' . $clienteActual->id . '</td>';
            echo '<td>' . $clienteActual->apellido_nombre . '</td>';
            echo '<td>' . $clienteActual->email . '</td>';
            echo '<td>' . $clienteActual->dni . '</td>';
            echo '<td>' . $clienteActual->movil . '</td>';
            echo '<td>' . $clienteActual->domicilio . '</td>';
            echo '<td>' . getEstadoToString($clienteActual) . '</td>';
          ?>
            <td>
              <button type="button" class="btn text-success" data-bs-toggle="modal" data-bs-target="#modalClientes">
                <i class="bi bi-pencil-square"></i>
              </button>
            </td>
          <?php
            echo '</tr>';
            // echo '<td class="text-danger"> <i class="bi bi-trash"></i> </td>';
          }
          ?>
          <!-- Modal -->
      </table>
    </div>
  </div>
  <div class="modal fade" id="modalClientes" tabindex="-1" aria-labelledby="modalTitulo" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modalTitulo">Editar Clientes</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating mb-3">
            <?php echo $apellido_nombre; ?>
            <input type="text" class="form-control" name="modal_apellido_nombre" value="<?php echo $apellido_nombre ?>" id="modal_apellido_nombre">
            <label for="modal_apellido_nombre">Apellidos y Nombres</label>
            <p></p>
          </div>

          <div class="form-floating mb-3">
            <input type="email" class="form-control" name="modal_email" value="<?php echo $email ?>" id="modal_email">
            <label for="modal_email">Email</label>
          </div>

          <div class="row gy-2 gx-3 align-items-center">
            <div class="col">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" name="modal_dni" value="<?php echo $dni ?>" id="modal_dni">
                <label for="modal_dni">DNI</label>
              </div>
            </div>

            <div class="col">
              <div class="form-floating mb-3">
                <label class="visually-hidden" for="modal_movil">Movil</label>
                <div class="input-group">
                  <div class="input-group-text">+54</div>
                  <input type="text" class="form-control" name="modal_movil" value="<?php echo $movil ?>" id="modal_movil" placeholder="Movil" maxlength="10">
                </div>
              </div>
            </div>
          </div>

          <div class="form-floating mb-3">
            <input type="text" class="form-control" name="modal_domicilio" value="<?php echo $domicilio ?>" id="modal_domicilio">
            <label for="modal_domicilio">Domicilio Completo</label>
          </div>

          <div class="row gx-3 gy-2 align-items-center">
            <div class="col-auto">

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="modal_estado" id="modal_estado" value="0" <?php if ($estado == 0) echo "checked"; ?>>
                <label class="form-check-label" for="amarraLibre">Baja</label>
              </div>
            </div>

            <div class="col-auto">

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="modal_estado" id="modal_estado" value="1" <?php if ($estado == 1) echo "checked"; ?>>
                <label class="form-check-label" for="amarraOcupada">Alta</label>
              </div>
            </div>
          </div>
        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const dataTable = new simpleDatatables.DataTable("#tablaClientes", {
    // Acá iria la traduccion al español si me dejara instalarlo
  });

  const btnEditarCliente = document.getElementById("editarCliente");

  btnEditarCliente.addEventListener("click", () => {

  });
</script>


<?php include("./templates/footer.php"); ?>