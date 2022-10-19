const index = "/Nautica_Amanecer/";
const paginaActual = window.location.pathname;
const navLinks = document.querySelectorAll('nav a').forEach(link => {
  if (link.href.includes(`${paginaActual}`) && paginaActual != index) {
    link.classList.add('active');
  }
})


