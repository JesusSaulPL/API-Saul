<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestion de Alumnos</title>
  <link rel="stylesheet" href="CSS/estilof.css">
    <link rel="icon" type="image/png" href="logo.png">

</head>
<body>

<!-- Formulario de registro de alumnos -->
<form class="Registro" id="RegistroForm">
  <h2>Registro de Alumno</h2>
  <div class="form-group">
    <input type="text" id="Matricula" name="Matricula" placeholder="Matrícula" required maxlength="10">
  </div>
  <div class="form-group">
    <input type="text" id="Nombre" name="Nombre" maxlength="20" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')" placeholder="Nombre" required>
  </div>
<div class="form-group">
  <input  type="text" id="Apaterno" name="Apaterno" maxlength="20" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')" placeholder="Apellido Paterno"  required>
</div>

<div class="form-group">
  <input type="text" id="Amaterno" name="Amaterno" maxlength="20" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"  placeholder="Apellido Materno"  required>
</div>

  <div class="form-group">
    <input type="email" id="Email" name="Email" placeholder="Correo Electrónico" required>
  </div>
  <div class="form-group">
    <input type="text" id="Celular" name="Celular" placeholder="Celular" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required maxlength="10">
  </div>
  <div class="form-group">
<input 
  type="text" id="CP" name="CP"  placeholder="Código Postal"  oninput="this.value = this.value.replace(/[^0-9]/g, '')" required maxlength="5">
  </div>
  <div class="form-group">
    <select id="Sexo" name="Sexo" required>
      <option value="">Selecciona sexo</option>
      <option value="Masculino">Masculino</option>
      <option value="Femenino">Femenino</option>
      <option value="Otro">Otro</option>
    </select>
  </div>
  <div class="form-group">
    <button type="submit">Registrar Alumno</button>
  </div>
</form>
<!-- Fin del formulario -->

<!-- Tabla para mostrar los alumnos -->
  <h1>Lista de Alumnos</h1>
  <table id="tablaAlumnos">
    <thead>
      <tr>
        <th>ID</th>
        <th>Matrícula</th>
        <th>Nombre</th>
        <th>Apellido P</th>
        <th>Apellido M</th>
        <th>Email</th>
        <th>Celular</th>
        <th>CP</th>
        <th>Sexo</th>
        <th>UPDATE</th>
        <th>DELETE</th>
      </tr>
    </thead>
    <tbody>
      <tr><td colspan="8" style="text-align:center;">Cargando datos...</td></tr>
    </tbody>
  </table>
  <div id="pagination" class="pagination"></div>
<!-- Fin de la tabla -->


<!-- Modal para editar el registro de alumnos -->
<div id="modalEditar" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Editar Alumno</h2>
    <form id="formEditar">
      <input type="hidden" id="edit-id">

      <div class="form-group">
        <label>Matricula:</label>
        <input type="text" id="edit-Matricula" name="Matricula"placeholder="Matricula" required>
      </div>

      <div class="form-group">
        <label>Nombre:</label>
        <input type="text" id="edit-Nombre" name="Nombre" maxlength="20" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')" placecholder="Nombre" required>
      </div>

      <div class="form-group">
        <label>Apellido Paterno:</label>
        <input type="text" id="edit-Apaterno" name="Apaterno" maxlength="20" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')" placeholder="Apellido Paterno" required>
      </div>

      <div class="form-group">
        <label>Apellido Materno:</label>
        <input type="text" id="edit-Amaterno" name="Amaterno" maxlength="20" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')" placecholder="Apellido Materno" required>
      </div>

      <div class="form-group">
        <label>Email:</label>
        <input type="email" id="edit-Email" name="Email"  placecholder="Gmail" required>
      </div>

      <div class="form-group">
        <label>Celular:</label>
        <input type="text" id="edit-Celular" name="Celular" placecholder="Celular" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required maxlength="10">
      </div>

      <div class="form-group">
        <label>Codigo Postal:</label>
        <input type="text" id="edit-CP" name="CP"  placecholder="Codigo Postal" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required maxlength="5">
      </div>

      <div class="form-group">
        <label>Sexo:</label>
        <select id="edit-Sexo" name="Sexo">
          <option value="Masculino">Masculino</option>
          <option value="Femenino">Femenino</option>
        </select>
      </div>

      <button type="submit" class="btn-guardar">Guardar Cambios</button>
    
    </form>
  </div>
</div>
<!-- Fin del modal -->


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/Sentencias.js"></script>
<script src="js/tabla.js"></script>

    

</body>
</html>
