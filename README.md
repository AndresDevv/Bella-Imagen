# Sistema de gestión Bella Imagen

Aplicación web PHP MVC para gestionar un centro de estética y belleza.

## Módulos

- Clientes y especialistas (incluye habilidades y especialidades).
- Servicios con categoría, precio y duración.
- Citas con fecha, hora, estado, cliente, especialista y servicio.
- Atenciones y recomendaciones asociadas a una cita.
- Productos e inventario, tratamientos estéticos y pagos.
- Registro de reportes de gestión.
- Inicio de sesión, registro de usuarios y cierre de sesión.

## Instalación con XAMPP

1. Inicia Apache y MySQL.
2. Importa `database/bella_imagen.sql` desde phpMyAdmin o MySQL Workbench.
3. Si tus credenciales MySQL son distintas, edita `config/database.php`.
4. Abre `http://localhost/sistema-veterinaria-26-2/` (usa el nombre actual de la carpeta en `htdocs`).
5. Registra un usuario desde la pantalla de inicio de sesión e ingresa.

Los pagos se registran en el sistema; no se ejecutan cobros reales. El esquema crea la base de datos `bella_imagen_db`.
# Bella-Imagen
