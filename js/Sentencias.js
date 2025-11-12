document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("RegistroForm");

    if (!form) return; // Evita errores si el formulario no existe

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Evita recargar la página

        const formData = new FormData(this);

        fetch("Api_Saul.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json()) // Esperamos respuesta JSON del PHP
        .then(data => {
            if (data.mensaje && data.mensaje.includes("registrado")) {
                Swal.fire({
                    icon: "success",
                    title: "Registro exitoso",
                    text: data.mensaje,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Aceptar"
                }).then(() => {
                    window.location.href = "/ProyectoAPI_Saul/Index.php";
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error al registrar",
                    text: data.mensaje || "No se pudo registrar al alumno.",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                });
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: "error",
                title: "Error de conexión",
                text: " No se pudo conectar con el servidor.",
                confirmButtonColor: "#d33"
            });
        });
    });
});
