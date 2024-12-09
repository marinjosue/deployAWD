// Función para manejar el cierre de sesión
function handleLogout() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cerrar la sesión?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar los datos de sesión
            localStorage.setItem('userRole', 'default');
            // Limpiar cualquier otra información de sesión
            localStorage.removeItem('username');
            // Mostrar mensaje de éxito
            Swal.fire({
                title: '¡Sesión cerrada!',
                text: 'Has cerrado sesión exitosamente',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // Redirigir al inicio
                window.location.href = '../index/index.html';
            });
        }
    });
}

// AJAX para obtener los datos del perfil
function loadProfile() {
    fetch('../Controller/ProfileController.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                Swal.fire({
                    title: 'Error',
                    text: data.error,
                    icon: 'error',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '../index/index.html';
                });
            } else {
                const user = data.user;
                document.getElementById('fullName').textContent = user.first_name + ' ' + user.last_name;
                document.getElementById('role').textContent = user.roles;
                document.getElementById('id').value = user.cedula;
                document.getElementById('email').value = user.email;
                document.getElementById('phone').value = user.phone;
                // Establecer la ruta de la imagen del perfil con un parámetro único
                const photoUrl = `../imgProfile/${user.cedula}.png?timestamp=${new Date().getTime()}`;
                document.getElementById('photo').src = photoUrl;
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al cargar el perfil.',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            });
        });
}

// Cargar los datos del perfil al cargar la página
window.onload = loadProfile;