// === Variables de paginación ===
let alumnosData = [];
let currentPage = 1;
const pageSize = 10; // filas por página

//Cargar alumnos desde API//
async function cargarAlumnos() {
  const tabla = document.querySelector("#tablaAlumnos tbody");
  tabla.innerHTML =
    "<tr><td colspan='11' style='text-align:center;'>Cargando...</td></tr>";

  try {
    const respuesta = await fetch("Api_Saul.php");
    if (!respuesta.ok) throw new Error("Error HTTP " + respuesta.status);
    const datos = await respuesta.json();

    alumnosData = Array.isArray(datos) ? datos : [];
    renderTablePage(1);
  } catch (error) {
    console.error("Error al obtener los datos:", error);
    tabla.innerHTML = `<tr><td colspan='11' style='text-align:center;color:red;'>Error al conectar con la API</td></tr>`;
  }
}

//Renderizar tabla con paginación//
function renderTablePage(page = 1) {
  const tabla = document.querySelector("#tablaAlumnos tbody");
  const total = alumnosData.length;
  const totalPages = Math.max(1, Math.ceil(total / pageSize));
  currentPage = Math.min(Math.max(1, page), totalPages);

  const start = (currentPage - 1) * pageSize;
  const end = start + pageSize;
  const pageItems = alumnosData.slice(start, end);

  tabla.innerHTML = "";

  // Mostrar mensaje si no hay registros//
  if (pageItems.length === 0) {
    tabla.innerHTML =
      "<tr><td colspan='11' style='text-align:center;'>No hay registros</td></tr>";
  } else {
    pageItems.forEach((a) => {
      const fila = `
        <tr data-id="${a.id}">
          <td>${a.id}</td>
          <td>${a.Matricula}</td>
          <td>${a.Nombre}</td>
          <td>${a.Apaterno}</td>
          <td>${a.Amaterno}</td>
          <td>${a.Email}</td>
          <td>${a.Celular}</td>
          <td>${a.CP}</td>
          <td>${a.Sexo}</td>
          <style> .btn-editar, .btn-eliminar { 
          padding: 6px 12px; border: none; border-radius: 6px; color: #fff; font-size: 14px; cursor: pointer; transition: 0.3s ease; 
          } .btn-editar { background-color: #007bff; 
          } .btn-editar:hover { background-color: #0056b3; 
          } .btn-eliminar { 
          background-color: #dc3545; 
           } .btn-eliminar:hover { background-color: #b02a37; 
            } </style>
          <td><button type="button" class="btn-editar">Actualizar</button></td>
          <td><button type="button" class="btn-eliminar">Eliminar</button></td>
        </tr>`;
      tabla.insertAdjacentHTML("beforeend", fila);
    });
  }

  // Renderizar controles de paginación//
  renderPaginationControls(totalPages);
}

/// Controladores de paginación///
function renderPaginationControls(totalPages) {
  const container = document.getElementById("pagination");
  if (!container) return;
  container.innerHTML = "";

  const prev = document.createElement("button");
  prev.textContent = "« Anterior";
  prev.disabled = currentPage === 1;
  prev.addEventListener("click", () => renderTablePage(currentPage - 1));
  container.appendChild(prev);

  const maxButtons = 7;
  let start = Math.max(1, currentPage - Math.floor(maxButtons / 2));
  let end = start + maxButtons - 1;
  if (end > totalPages) {
    end = totalPages;
    start = Math.max(1, end - maxButtons + 1);
  }

  for (let p = start; p <= end; p++) {
    const btn = document.createElement("button");
    btn.textContent = p;
    if (p === currentPage) btn.className = "active-page";
    btn.addEventListener("click", () => renderTablePage(p));
    container.appendChild(btn);
  }

  const next = document.createElement("button");
  next.textContent = "Siguiente »";
  next.disabled = currentPage === totalPages;
  next.addEventListener("click", () => renderTablePage(currentPage + 1));
  container.appendChild(next);
}


// Manejo de eventos para actualizar y eliminar//
document.addEventListener("click", (e) => {
  const tabla = document.querySelector("#tablaAlumnos tbody");
  if (!tabla.contains(e.target)) return;

  if (e.target.classList.contains("btn-editar")) {
    const fila = e.target.closest("tr");
    abrirModalEditar(fila);
  }

  if (e.target.classList.contains("btn-eliminar")) {
    const fila = e.target.closest("tr");
    const id = fila.querySelector("td").innerText.trim();

    // Confirmación con diseño usando SweetAlert2//
    Swal.fire({
      title: '¿Eliminar registro?',
      text: `¿Estás seguro que deseas eliminar el registro #${id}? Esta acción no se puede deshacer.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      reverseButtons: true
    }).then((result) => {
      if (!result.isConfirmed) return;

      // Mostrar pantalla elimina
      Swal.fire({
        title: 'Eliminando...',
        text: 'Por favor espera',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      fetch("Api_Saul.php", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
      })
      .then(async (res) => {
        const text = await res.text();
        try {
          return JSON.parse(text);
        } catch (e) {
          console.warn("Respuesta no JSON:", text);
          return { mensaje: text.trim() };
        }
      })
      .then((data) => {
        if (data.mensaje?.includes("Eliminado")) {
          fila.remove();
          Swal.fire({
            icon: "success",
            title: "Registro eliminado",
            text: data.mensaje,
            confirmButtonColor: "#3085d6",
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error al eliminar",
            text: data.mensaje || "No se pudo eliminar el registro.",
            confirmButtonColor: "#d33",
          });
        }
      })
      .catch((err) => {
        console.error("Error de conexión:", err);
        Swal.fire({
          icon: "error",
          title: "Error de conexión",
          text: "No se pudo conectar con el servidor.",
          confirmButtonColor: "#d33",
        });
      });
    });
  }

});


// === Modal edición ===
const modalEditar = document.getElementById("modalEditar");
const closeModal = document.querySelector(".close");
const formEditar = document.getElementById("formEditar");

closeModal.onclick = () => (modalEditar.style.display = "none");

function abrirModalEditar(fila) {
  document.getElementById("edit-id").value = fila.dataset.id;
  document.getElementById("edit-Matricula").value = fila.children[1].textContent.trim();
  document.getElementById("edit-Nombre").value = fila.children[2].textContent.trim();
  document.getElementById("edit-Apaterno").value = fila.children[3].textContent.trim();
  document.getElementById("edit-Amaterno").value = fila.children[4].textContent.trim();
  document.getElementById("edit-Email").value = fila.children[5].textContent.trim();
  document.getElementById("edit-Celular").value = fila.children[6].textContent.trim();
  document.getElementById("edit-CP").value = fila.children[7].textContent.trim();
  document.getElementById("edit-Sexo").value = fila.children[8].textContent.trim();
  modalEditar.style.display = "block";
}

// Guardar cambios PUT
formEditar.addEventListener("submit", async (e) => {
  e.preventDefault();

  const datos = {
    id: document.getElementById("edit-id").value,
    Matricula: document.getElementById("edit-Matricula").value.trim(),
    Nombre: document.getElementById("edit-Nombre").value.trim(),
    Apaterno: document.getElementById("edit-Apaterno").value.trim(),
    Amaterno: document.getElementById("edit-Amaterno").value.trim(),
    Email: document.getElementById("edit-Email").value.trim(),
    Celular: document.getElementById("edit-Celular").value.trim(),
    CP: document.getElementById("edit-CP").value.trim(),
    Sexo: document.getElementById("edit-Sexo").value.trim(),
  };

  try {
    const res = await fetch("Api_Saul.php", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(datos),
    });

    const data = await res.json();

    if (data.mensaje && data.mensaje.toLowerCase().includes("actualiz")) {
      Swal.fire("Actualizado", data.mensaje, "success");
      modalEditar.style.display = "none";
      await cargarAlumnos();
    }
  } catch (error) {
    Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
  }
});
//Inicialización al cargar//
document.addEventListener("DOMContentLoaded", () => {
  cargarAlumnos();
});

//Exportar funciones globales opcional//
window.cargarAlumnos = cargarAlumnos;
window.renderTablePage = renderTablePage;
