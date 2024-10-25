-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS restaurante;
USE restaurante;

-- Crear la tabla usuario_tipo
CREATE TABLE usuario_tipo (
    usuario_tipo_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    usuario_tipo_nombre VARCHAR(50) NOT NULL
);

-- Crear la tabla usuario
CREATE TABLE usuario (
    usuario_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    usuario_nombre VARCHAR(50) NOT NULL,
    usuario_correo VARCHAR(255) NOT NULL,
    password VARCHAR(100) NOT NULL,
    usuario_tipo_id BIGINT,
    CONSTRAINT fk_usuario_tipo FOREIGN KEY (usuario_tipo_id) REFERENCES usuario_tipo(usuario_tipo_id)
);

-- Crear la tabla menu
CREATE TABLE menu (
    menu_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    menu_plato_nombre VARCHAR(100) NOT NULL,
    menu_plato_descripcion VARCHAR(100),
    menu_precio BIGINT NOT NULL,
    menu_disponible BOOLEAN NOT NULL
);

-- Crear la tabla reserva_estado
CREATE TABLE reserva_estado (
    reserva_estado_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    reserva_estado_tipo VARCHAR(50) NOT NULL
);

-- Crear la tabla reserva
CREATE TABLE reserva (
    reserva_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT,
    reserva_fecha DATE NOT NULL,
    reserva_hora TIME NOT NULL,
    reserva_num_personas INT NOT NULL,
    reserva_estado BIGINT,
    CONSTRAINT fk_usuario_reserva FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id),
    CONSTRAINT fk_reserva_estado FOREIGN KEY (reserva_estado) REFERENCES reserva_estado(reserva_estado_id)
);

-- Crear la tabla comentario
CREATE TABLE comentario (
    comentario_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    reserva_id BIGINT,
    usuario_id BIGINT,
    comentario_text VARCHAR(250),
    comentario_fecha DATE NOT NULL,
    CONSTRAINT fk_reserva_comentario FOREIGN KEY (reserva_id) REFERENCES reserva(reserva_id),
    CONSTRAINT fk_usuario_comentario FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id)
);
