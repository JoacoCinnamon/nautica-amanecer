window.addEventListener("load", () => {
  const form = document.getElementById("formEmbarcaciones");
  const nombre = document.getElementById("nombre");
  const rey = document.getElementById("rey");
  const clientes = document.getElementById("idCliente");

  // Una vez que desencadenamos el sumbit hacemos que no vaya al action
  form.addEventListener("submit", (e) => {
    //console.log("submit", e);
    if (!contieneAlgo()) {
      e.preventDefault();
    }
    //validarCampos();
  });

  const contieneAlgo = () => {
    const embarcacion = {
      nombre: nombre.value.trim(),
      rey: rey.value.trim(),
      clientes: clientes.value.trim(),
    };
    return (
      embarcacion.nombre !== "" &&
      embarcacion.rey !== "" &&
      embarcacion.clientes !== 0
    );
  };
});
