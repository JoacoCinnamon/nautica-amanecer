const navbar = document.querySelector(".navbar");
const welcome = document.querySelector(".index");
const navbarToggle = document.querySelector("#navbarNav");

const resizeBakgroundImg = () => {
  const height = window.innerHeight - navbar.clientHeight;
  welcome.style.height = `${height}px`;
  welcome.parentElement.classList.remove("container");
  welcome.parentElement.classList.remove("py-3");
};

navbarToggle.ontransitionend = resizeBakgroundImg;
navbarToggle.ontransitionstart = resizeBakgroundImg;
window.onresize = resizeBakgroundImg;
window.onload = resizeBakgroundImg;
