window.addEventListener("load", () => {
  const form = document.getElementById("formMovimientos");
  const idEmbarcacion = document.getElementById("idEmbarcacion");
  const idAmarra = document.getElementById("idAmarra");

  // Una vez que desencadenamos el sumbit hacemos que no vaya al action
  form.addEventListener("submit", (e) => {
    console.log({ Embarcacion: idEmbarcacion.value, Amarra: idAmarra.value });
    if (!contieneAlgo()) {
      e.preventDefault();
    }
  });

  const contieneAlgo = () => {
    const movimiento = {
      idEmbarcacion: Number(idEmbarcacion.value.trim()),
      idAmarra: Number(idAmarra.value.trim()),
    };
    return movimiento.idEmbarcacion !== 0 && movimiento.idAmarra !== 0;
  };
});
