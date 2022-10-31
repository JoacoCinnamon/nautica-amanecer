window.addEventListener("load", () => {
  const form = document.getElementById("formAmarras");
  const pasillo = document.getElementById("pasillo");

  form.addEventListener("submit", (e) => {
    console.log("submit", e);
    if (!contieneAlgo()) {
      e.preventDefault();
    }
  });

  const contieneAlgo = () => {
    const amarra = {
      pasillo: pasillo.value.trim(),
    };
    return amarra.pasillo != "" && amarra.pasillo > 0;
  };
});
