document.addEventListener("DOMContentLoaded", () => {
  // Toggle para el menú móvil
  const menuToggle = document.querySelector(".menu-toggle")
  const menu = document.querySelector(".menu")

  if (menuToggle && menu) {
    menuToggle.addEventListener("click", () => {
      menu.classList.toggle("active")
      menuToggle.classList.toggle("active")
    })
  }

  // Toggle para el tema oscuro
  const themeToggle = document.getElementById("themeToggle")

  if (themeToggle) {
    // Verificar si hay un tema guardado en localStorage
    const currentTheme = localStorage.getItem("theme") || "light"
    document.body.classList.toggle("dark-theme", currentTheme === "dark")
    themeToggle.textContent = currentTheme === "dark" ? "☀️" : "🌙"

    themeToggle.addEventListener("click", () => {
      document.body.classList.toggle("dark-theme")

      // Guardar preferencia en localStorage
      const isDark = document.body.classList.contains("dark-theme")
      localStorage.setItem("theme", isDark ? "dark" : "light")

      // Cambiar icono del botón
      themeToggle.textContent = isDark ? "🌞" : "🌛"
    })
  }

  // Manejo de submenús en dispositivos móviles
  const dropdowns = document.querySelectorAll(".dropdown")

  dropdowns.forEach((dropdown) => {
    dropdown.addEventListener("click", function (e) {
      // Solo en móvil
      if (window.innerWidth <= 768) {
        const submenu = this.querySelector(".submenu")

        // Si el clic fue en el enlace principal del dropdown
        if (e.target === this.querySelector("a")) {
          e.preventDefault()
          submenu.classList.toggle("active")
        }
      }
    })
  })
})
