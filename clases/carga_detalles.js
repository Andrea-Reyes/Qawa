// JavaScript para manejar la carga de detalles y otras funciones

function agregarPlatillo(id) {
    // Obtener la cantidad ingresada por el usuario
    const cantidad = document.getElementById('cantidad').value;

    // Para enviar los parámetros mediante el método POST
    let url = 'clases/carrito.php';
    let formData = new FormData();      //Para enviar los parametros mediante método POST
    formData.append('id', id);
    formData.append('cantidad', cantidad);

    // Petición AJAX usando método POST
    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    })
        .then(response => response.json())
        .then(data => {
            // Para manejar la respuesta del servidor
            console.log(data);
            if (data.ok) {
                let elemento = document.getElementById("pedido");
                elemento.innerHTML = data.numero;
                alert('Producto agregado al carrito');
            } else {
                alert('Error al agregar producto al carrito');
            }
        })
        .catch(error => {
            console.error('Error saber:', error);
        });
}

function cargarDetallesAlmuerzo(id) {
    // Construye la URL para obtener los detalles
    let url = `detallesAlmuerzos.php?id=${id}`;

    // Petición AJAX para obtener los detalles del almuerzo
    fetch(url)
        .then(response => response.text())
        .then(html => {
            // Carga el contenido de los detalles en el contenedor
            document.getElementById('contenido-detalles').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Funcionalidad para incrementar y decrementar la cantidad
document.addEventListener('click', function (e) {
    if (e.target && e.target.id == 'sumar') {
        let cantidad = document.getElementById('cantidad');
        cantidad.value = parseInt(cantidad.value) + 1;
    }
    if (e.target && e.target.id == 'restar') {
        let cantidad = document.getElementById('cantidad');
        if (parseInt(cantidad.value) > 0) {
            cantidad.value = parseInt(cantidad.value) - 1;
        }
    }
});