/**
 * Script para manejar la eliminación de preguntas y respuestas
 * Guía Verde - Sistema de preguntas y respuestas
 */

document.addEventListener("DOMContentLoaded", () => {
  // Inicializar funcionalidad para eliminar preguntas en la página de listado
  inicializarEliminarPreguntasListado()

  // Inicializar funcionalidad para eliminar preguntas en la página de detalle
  inicializarEliminarPreguntaDetalle()

  // Inicializar funcionalidad para eliminar respuestas
  inicializarEliminarRespuestas()
})

/**
 * Inicializa los botones para eliminar preguntas en la página de listado
 */
function inicializarEliminarPreguntasListado() {
  const botonesEliminarPregunta = document.querySelectorAll(".preguntas-lista .btn-eliminar-pregunta")

  if (botonesEliminarPregunta.length === 0) return

  botonesEliminarPregunta.forEach((boton) => {
    boton.addEventListener("click", function (e) {
      e.preventDefault()

      const preguntaId = this.getAttribute("data-id")
      const preguntaCard = this.closest(".pregunta-card")

      confirmarEliminacion(
        "¿Estás seguro de que deseas eliminar esta pregunta?",
        "Esta acción no se puede deshacer y también eliminará todas las respuestas asociadas.",
        () => eliminarPregunta(preguntaId, preguntaCard),
      )
    })
  })
}

/**
 * Inicializa el botón para eliminar la pregunta en la página de detalle
 */
function inicializarEliminarPreguntaDetalle() {
  const btnEliminarPregunta = document.querySelector(".pregunta-detalle .btn-eliminar-pregunta")

  if (!btnEliminarPregunta) return

  btnEliminarPregunta.addEventListener("click", function () {
    const preguntaId = this.getAttribute("data-id")

    confirmarEliminacion(
      "¿Estás seguro de que deseas eliminar esta pregunta?",
      "Esta acción no se puede deshacer y también eliminará todas las respuestas asociadas.",
      () => eliminarPreguntaYRedirigir(preguntaId),
    )
  })
}

/**
 * Inicializa los botones para eliminar respuestas
 */
function inicializarEliminarRespuestas() {
  const botonesEliminarRespuesta = document.querySelectorAll(".btn-eliminar-respuesta")

  if (botonesEliminarRespuesta.length === 0) return

  botonesEliminarRespuesta.forEach((boton) => {
    boton.addEventListener("click", function () {
      const respuestaId = this.getAttribute("data-id")
      const respuestaCard = this.closest(".respuesta-card")

      confirmarEliminacion(
        "¿Estás seguro de que deseas eliminar esta respuesta?",
        "Esta acción no se puede deshacer.",
        () => eliminarRespuesta(respuestaId, respuestaCard),
      )
    })
  })
}

/**
 * Muestra un diálogo de confirmación
 *
 * @param {string} titulo - Título de la confirmación
 * @param {string} mensaje - Mensaje de la confirmación
 * @param {Function} callback - Función a ejecutar si se confirma
 */
function confirmarEliminacion(titulo, mensaje, callback) {
  // Por ahora usamos confirm nativo, pero esto podría mejorarse con un modal personalizado
  if (confirm(titulo + "\n\n" + mensaje)) {
    callback()
  }
}

/**
 * Elimina una pregunta y su tarjeta del DOM
 *
 * @param {string|number} preguntaId - ID de la pregunta a eliminar
 * @param {HTMLElement} preguntaCard - Elemento DOM de la tarjeta de pregunta
 */
function eliminarPregunta(preguntaId, preguntaCard) {
  // Realizar petición AJAX
  fetch("actions/eliminar_pregunta.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "pregunta_id=" + preguntaId,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        mostrarMensaje(data.message, "success")

        // Eliminar la tarjeta de pregunta del DOM con animación
        preguntaCard.style.opacity = "0"
        setTimeout(() => {
          preguntaCard.remove()

          // Si no quedan preguntas, mostrar mensaje
          const preguntasContainer = document.querySelector(".preguntas-lista")
          if (preguntasContainer && preguntasContainer.querySelectorAll(".pregunta-card").length === 0) {
            const noPreguntas = document.createElement("div")
            noPreguntas.className = "no-preguntas"
            noPreguntas.innerHTML = "<p>Aún no hay preguntas. ¡Sé el primero en preguntar!</p>"
            preguntasContainer.appendChild(noPreguntas)
          }
        }, 300)
      } else {
        mostrarMensaje("Error: " + data.message, "error")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      mostrarMensaje("Ocurrió un error al procesar tu solicitud.", "error")
    })
}

/**
 * Elimina una pregunta y redirige a la página de listado
 *
 * @param {string|number} preguntaId - ID de la pregunta a eliminar
 */
function eliminarPreguntaYRedirigir(preguntaId) {
  // Realizar petición AJAX
  fetch("actions/eliminar_pregunta.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "pregunta_id=" + preguntaId,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        mostrarMensaje(data.message, "success")

        // Redirigir a la página de preguntas después de un breve retraso
        setTimeout(() => {
          window.location.href = "preguntas.php"
        }, 1000)
      } else {
        mostrarMensaje("Error: " + data.message, "error")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      mostrarMensaje("Ocurrió un error al procesar tu solicitud.", "error")
    })
}

/**
 * Elimina una respuesta y su tarjeta del DOM
 *
 * @param {string|number} respuestaId - ID de la respuesta a eliminar
 * @param {HTMLElement} respuestaCard - Elemento DOM de la tarjeta de respuesta
 */
function eliminarRespuesta(respuestaId, respuestaCard) {
  // Realizar petición AJAX
  fetch("actions/eliminar_respuesta.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "respuesta_id=" + respuestaId,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        mostrarMensaje(data.message, "success")

        // Eliminar la tarjeta de respuesta del DOM con animación
        respuestaCard.style.opacity = "0"
        setTimeout(() => {
          respuestaCard.remove()

          // Actualizar el contador de respuestas
          actualizarContadorRespuestas()

          // Si no quedan respuestas, mostrar mensaje
          const respuestasContainer = document.querySelector(".respuestas-container")
          if (respuestasContainer && respuestasContainer.querySelectorAll(".respuesta-card").length === 0) {
            const noRespuestas = document.createElement("div")
            noRespuestas.className = "no-respuestas"
            noRespuestas.innerHTML = "<p>Aún no hay respuestas para esta pregunta.</p>"
            respuestasContainer.appendChild(noRespuestas)
          }
        }, 300)
      } else {
        mostrarMensaje("Error: " + data.message, "error")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      mostrarMensaje("Ocurrió un error al procesar tu solicitud.", "error")
    })
}

/**
 * Actualiza el contador de respuestas en la página
 */
function actualizarContadorRespuestas() {
  const tituloRespuestas = document.querySelector(".respuestas-container h2")
  if (tituloRespuestas) {
    const numRespuestas = document.querySelectorAll(".respuesta-card").length
    tituloRespuestas.textContent = numRespuestas + " Respuestas"
  }
}

/**
 * Muestra un mensaje al usuario
 *
 * @param {string} mensaje - Mensaje a mostrar
 * @param {string} tipo - Tipo de mensaje ('success' o 'error')
 */
function mostrarMensaje(mensaje, tipo) {
  // Por ahora usamos alert nativo, pero esto podría mejorarse con un sistema de notificaciones
  alert(mensaje)
}
