CREATE DATABASE IF NOT EXISTS bella_imagen_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bella_imagen_db;

CREATE TABLE IF NOT EXISTS usuarios (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL,
email VARCHAR(180) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL,
rol ENUM('admin','especialista','recepcion') NOT NULL DEFAULT 'recepcion',
activo TINYINT(1) NOT NULL DEFAULT 1, creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS password_resets (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, usuario_id INT UNSIGNED NOT NULL,
token_hash CHAR(64) NOT NULL UNIQUE, expira DATETIME NOT NULL,
FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS clientes (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL,
telefono VARCHAR(25), email VARCHAR(150), direccion VARCHAR(255), documento VARCHAR(30) UNIQUE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS especialistas (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL,
especialidad VARCHAR(120), habilidades TEXT, telefono VARCHAR(25), email VARCHAR(150), activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS servicios (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL,
categoria VARCHAR(80), descripcion TEXT, precio DECIMAL(10,2) NOT NULL DEFAULT 0, duracion_min INT NOT NULL DEFAULT 30
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS productos (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL,
categoria VARCHAR(80), unidad VARCHAR(30) DEFAULT 'unidad', stock DECIMAL(10,2) NOT NULL DEFAULT 0,
stock_minimo DECIMAL(10,2) NOT NULL DEFAULT 0, precio DECIMAL(10,2) NOT NULL DEFAULT 0
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS citas (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, cliente_id INT UNSIGNED NOT NULL,
especialista_id INT UNSIGNED NOT NULL, servicio_id INT UNSIGNED NOT NULL,
fecha_hora DATETIME NOT NULL, estado ENUM('pendiente','confirmada','atendida','cancelada') NOT NULL DEFAULT 'pendiente', notas TEXT,
FOREIGN KEY (cliente_id) REFERENCES clientes(id), FOREIGN KEY (especialista_id) REFERENCES especialistas(id),
FOREIGN KEY (servicio_id) REFERENCES servicios(id), INDEX (fecha_hora)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS atenciones (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, cita_id INT UNSIGNED NOT NULL UNIQUE,
descripcion TEXT, recomendaciones TEXT, fecha DATE NOT NULL,
FOREIGN KEY (cita_id) REFERENCES citas(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS atencion_productos (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, atencion_id INT UNSIGNED NOT NULL,
producto_id INT UNSIGNED NOT NULL, cantidad DECIMAL(10,2) NOT NULL,
FOREIGN KEY (atencion_id) REFERENCES atenciones(id) ON DELETE CASCADE,
FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS tratamientos (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, cliente_id INT UNSIGNED NOT NULL,
nombre VARCHAR(150) NOT NULL, indicaciones TEXT, fecha_inicio DATE, fecha_fin DATE, costo DECIMAL(10,2) DEFAULT 0,
FOREIGN KEY (cliente_id) REFERENCES clientes(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS pagos (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, cita_id INT UNSIGNED NOT NULL,
monto DECIMAL(10,2) NOT NULL, metodo ENUM('efectivo','tarjeta_credito','tarjeta_debito','yape') NOT NULL,
estado ENUM('pendiente','pagado','anulado') NOT NULL DEFAULT 'pendiente', referencia VARCHAR(120), fecha_pago DATETIME,
FOREIGN KEY (cita_id) REFERENCES citas(id)
) ENGINE=InnoDB;
INSERT INTO servicios(nombre,categoria,descripcion,precio,duracion_min)
SELECT 'Manicure clásica','Manicure','Cuidado y esmaltado de uñas',35,45
WHERE NOT EXISTS (SELECT 1 FROM servicios WHERE nombre='Manicure clásica');
INSERT INTO servicios(nombre,categoria,descripcion,precio,duracion_min)
SELECT 'Facial hidratante','Tratamiento facial','Limpieza e hidratación facial',80,60
WHERE NOT EXISTS (SELECT 1 FROM servicios WHERE nombre='Facial hidratante');
