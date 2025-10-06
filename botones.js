function filtrarManuales(categoria, botonSeleccionado) {
  // Quitar la clase 'active' de todos los botones
  const botones = document.querySelectorAll('.boton');
  botones.forEach(btn => btn.classList.remove('active'));

  // Agregar 'active' al botón clicado
  botonSeleccionado.classList.add('active');

  // Aquí iría tu lógica para mostrar/ocultar los manuales por categoría
  const manuales = document.querySelectorAll('.manual');
  manuales.forEach(manual => {
    if (categoria === 'todos' || manual.dataset.categoria === categoria) {
      manual.style.display = 'block';
    } else {
      manual.style.display = 'none';
    }
  });
}
