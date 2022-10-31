window.addEventListener("load", () => {
  const form = document.getElementById("formClientes");
  const apellido_nombre = document.getElementById("apellido_nombre");
  const email = document.getElementById("email");
  const dni = document.getElementById("dni");
  const movil = document.getElementById("movil");
  const domicilio = document.getElementById("domicilio");

  // Una vez que desencadenamos el sumbit hacemos que no vaya al action
  form.addEventListener("submit", (e) => {
    console.log("submit", e);
    if (!contieneAlgo()) {
      e.preventDefault();
    }
    //validarCampos();
  });

  const contieneAlgo = () => {
    const cliente = {
      apellido_nombre: apellido_nombre.value.trim(),
      email: email.value.trim(),
      dni: dni.value.trim(),
      movil: movil.value.trim(),
      domicilio: domicilio.value.trim(),
    };
    return (
      cliente.apellido_nombre != "" &&
      cliente.email != "" &&
      cliente.dni != "" &&
      cliente.movil != "" &&
      cliente.domicilio != ""
    );
  };

  const validarCampos = () => {
    const cliente = {
      apellido_nombre: apellido_nombre.value.trim(),
      email: email.value.trim(),
      dni: dni.value.trim(),
      movil: movil.value.trim(),
      domicilio: domicilio.value.trim(),
    };

    if (!cliente.apellido_nombre) {
      removerEstado(apellido_nombre, "border-success");
      agregarEstado(apellido_nombre, "border-danger");
    } else {
      removerEstado(apellido_nombre, "border-danger");
      agregarEstado(apellido_nombre, "border-success");
    }

    if (!cliente.email || validaEmail(cliente.email)) {
      removerEstado(email, "border-success");
      agregarEstado(email, "border-danger");
    } else {
      removerEstado(email, "border-danger");
      agregarEstado(email, "border-success");
    }
  };

  const agregarEstado = (campo, msg) => {
    campo.classList.add(msg);
  };

  const removerEstado = (campo, msg) => {
    campo.classList.remove(msg);
  };

  const validaEmail = (email) => {
    return re.correo.test(email);
  };

  const re = {
    usuario: /^[a-zA-Z0-9\_\-]{4,16}$/, // Letras, numeros, guion y guion_bajo
    nombre: /^[a-zA-ZÀ-ÿ\s]{1,40}$/, // Letras y espacios, pueden llevar acentos.
    correo:
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
    telefono: /^\d{7,14}$/, // 7 a 14 numeros.
  };
});
