// Obtener el rol del usuario desde localStorage
const userRole = localStorage.getItem('userRole') || 'default';

// Determinar la ruta del menú según el rol
function getHeaderPath(role) {
    switch (role) {
        case 'Admin':
            return '../Partials/headerAdmin.html';
        case 'Student':
            return '../Partials/headerUser.html';
        case 'default':
            return '../Partials/header.html'; // Menú por defecto
    }
}


// Cargar el archivo HTML correspondiente al menú
function loadMenu(role) {
    const headerPath = getHeaderPath(role);
    loadHTML('header', headerPath, () => {
        setupHamburgerMenu(); // Inicializar el menú hamburguesa después de cargar el HTML
    });
}
// Función para cargar un archivo HTML en un contenedor
function loadHTML(elementId, filePath, callback) {
    fetch(filePath)
        .then(response => {
            if (!response.ok) throw new Error(`Error al cargar ${filePath}`);
            return response.text();
        })
        .then(data => {
            document.getElementById(elementId).innerHTML = data;
            if (callback) callback(); // Ejecuta el callback si se pasa uno
        })
        .catch(error => console.error('Error loading HTML:', error));
}

// Configurar la funcionalidad del menú hamburguesa
function setupHamburgerMenu() {
    const hamburger = document.getElementById("hamburger");
    const navbar = document.getElementById("navbar");

    if (hamburger && navbar) {
        hamburger.addEventListener("click", () => {
            navbar.classList.toggle("active"); // Alternar clase para abrir/cerrar el menú
        });
    }
}

// Decidir y cargar el menú cuando la página cargue
window.addEventListener('load', () => {
    const role = localStorage.getItem('userRole') || 'default'; // Obtener el rol del usuario
    loadMenu(role); // Cargar el menú según el rol
    redirectToIndexIfLoggedIn(role); // Redirigir al index.html si corresponde
});

// Cargar el footer
loadHTML('footer', '../Partials/footer.html');

// Ajustar la posición del footer
window.addEventListener('load', () => {
    const footer = document.getElementById('footer');
    const body = document.body;

    function adjustFooterPosition() {
        if (body.scrollHeight <= window.innerHeight) {
            footer.style.position = 'absolute';
            footer.style.bottom = '0';
            footer.style.width = '100%';
        } else {
            footer.style.position = 'static';
        }
    }

    adjustFooterPosition();
    window.addEventListener('resize', adjustFooterPosition);
});