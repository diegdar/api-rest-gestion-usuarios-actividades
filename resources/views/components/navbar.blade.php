<nav id="navbar-container"
    role="navigation"
    aria-label="Main navigation"
    class="navbar container-fluid d-flex navbar-expand-lg navbar-dark bg-dark bg-opacity-75 fixed-top">
    {{-- logo container --}}
    <div id="logo-container" class="d-flex flex-fill align-items-center">
        <a href="https://diegochacondev.es#about">
            <img class="logo-Navbar navbar-brand" src="{{ asset('img/logos/myLogo.png') }}" alt="Logo">
        </a>
        {{-- navbar toggler --}}
        <button id="navbar-toggler" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="https://diegochacondev.es#navbar-links" aria-controls="navbar-links" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-icon">
                <i class="fa-solid fa-bars"></i> <!-- Icono inicial -->
            </span>
        </button>
    </div>
    {{-- navbar links --}}
    <div id="navbar-links" class="collapse navbar-collapse justify-content-center-sm">
        <ul class="navbar-nav">
            <li id="home" class="nav-item">
                <a class="nav-link" href="https://diegochacondev.es#introduction-header">Inicio</a>
            </li>
            <li id="about" class="nav-item">
                <a class="nav-link" href="https://diegochacondev.es#about-header">Sobre mi</a>
            </li>
            <li id="cv" class="nav-item">
                <a class="nav-link" href="https://diegochacondev.es#cv-header">Curriculum</a>
            </li>
            <li id="projects" class="nav-item">
                <a class="nav-link" href="https://diegochacondev.es#projects-header">Proyectos</a>
            </li>
            <li id="contact" class="nav-item">
                <a class="nav-link" href="https://diegochacondev.es#contact-header">Contacto</a>
            </li>
            <li id="social" class="nav-item d-flex align-items-center">
                <div>
                    <a href="https://github.com/diegdar?tab=repositories" target="_blank" aria-label="enlace al perfil de Github Diego Chacon" title="perfil Github Diego Chacon">
                        <i class="fa-brands fa-github fa-xl" aria-hidden="true"></i>
                    </a>
                </div>
                <div>
                    <a href="https://www.linkedin.com/in/diegochacondelgado" target="_blank" title="pefil linkedin Diego Chacon" aria-label="pefil linkedin Diego Chacon">
                        <i class="fa-brands fa-linkedin fa-xl"></i>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
@vite('resources/js/navbar.js')
