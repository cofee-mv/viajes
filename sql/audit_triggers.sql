-- Audit triggers for viajes_db
USE viajes_db;

-- Table to store audit logs
CREATE TABLE IF NOT EXISTS audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    db_user VARCHAR(255),
    table_name VARCHAR(100),
    operation VARCHAR(10),
    row_id VARCHAR(255),
    old_values JSON NULL,
    new_values JSON NULL,
    additional_info TEXT NULL
);

DELIMITER $$

-- Generic INSERT trigger for a table
CREATE TRIGGER trg_usuarios_insert AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (db_user, table_name, operation, row_id, new_values)
    VALUES (CURRENT_USER(), 'usuarios', 'INSERT', NEW.id_usuario, JSON_OBJECT(
        'id_usuario', NEW.id_usuario,
        'nombre', NEW.nombre,
        'email', NEW.email,
        'rol_id', NEW.rol_id
    ));
END$$

CREATE TRIGGER trg_usuarios_update AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (db_user, table_name, operation, row_id, old_values, new_values)
    VALUES (CURRENT_USER(), 'usuarios', 'UPDATE', NEW.id_usuario,
        JSON_OBJECT('id_usuario', OLD.id_usuario, 'nombre', OLD.nombre, 'email', OLD.email, 'rol_id', OLD.rol_id),
        JSON_OBJECT('id_usuario', NEW.id_usuario, 'nombre', NEW.nombre, 'email', NEW.email, 'rol_id', NEW.rol_id)
    );
END$$

CREATE TRIGGER trg_usuarios_delete AFTER DELETE ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (db_user, table_name, operation, row_id, old_values)
    VALUES (CURRENT_USER(), 'usuarios', 'DELETE', OLD.id_usuario,
        JSON_OBJECT('id_usuario', OLD.id_usuario, 'nombre', OLD.nombre, 'email', OLD.email, 'rol_id', OLD.rol_id)
    );
END$$

-- viajes triggers
CREATE TRIGGER trg_viajes_insert AFTER INSERT ON viajes
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (db_user, table_name, operation, row_id, new_values)
    VALUES (CURRENT_USER(), 'viajes', 'INSERT', NEW.id_viaje, JSON_OBJECT(
        'id_viaje', NEW.id_viaje, 'origen', NEW.origen, 'destino', NEW.destino,
        'fecha_salida', NEW.fecha_salida, 'fecha_regreso', NEW.fecha_regreso, 'precio', NEW.precio
    ));
END$$

CREATE TRIGGER trg_viajes_update AFTER UPDATE ON viajes
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (db_user, table_name, operation, row_id, old_values, new_values)
    VALUES (CURRENT_USER(), 'viajes', 'UPDATE', NEW.id_viaje,
        JSON_OBJECT('id_viaje', OLD.id_viaje, 'origen', OLD.origen, 'destino', OLD.destino, 'fecha_salida', OLD.fecha_salida, 'fecha_regreso', OLD.fecha_regreso, 'precio', OLD.precio),
        JSON_OBJECT('id_viaje', NEW.id_viaje, 'origen', NEW.origen, 'destino', NEW.destino, 'fecha_salida', NEW.fecha_salida, 'fecha_regreso', NEW.fecha_regreso, 'precio', NEW.precio)
    );
END$$

CREATE TRIGGER trg_viajes_delete AFTER DELETE ON viajes
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (db_user, table_name, operation, row_id, old_values)
    VALUES (CURRENT_USER(), 'viajes', 'DELETE', OLD.id_viaje,
        JSON_OBJECT('id_viaje', OLD.id_viaje, 'origen', OLD.origen, 'destino', OLD.destino, 'fecha_salida', OLD.fecha_salida, 'fecha_regreso', OLD.fecha_regreso, 'precio', OLD.precio)
    );
END$$

-- reservas triggers
CREATE TRIGGER trg_reservas_insert AFTER INSERT ON reservas
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (db_user, table_name, operation, row_id, new_values)
    VALUES (CURRENT_USER(), 'reservas', 'INSERT', NEW.id_reserva, JSON_OBJECT(
        'id_reserva', NEW.id_reserva, 'usuario_id', NEW.usuario_id, 'viaje_id', NEW.viaje_id,
        'num_pasajeros', NEW.num_pasajeros, 'total', NEW.total, 'fecha_reserva', NEW.fecha_reserva
    ));
END$$

CREATE TRIGGER trg_reservas_update AFTER UPDATE ON reservas
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (db_user, table_name, operation, row_id, old_values, new_values)
    VALUES (CURRENT_USER(), 'reservas', 'UPDATE', NEW.id_reserva,
        JSON_OBJECT('id_reserva', OLD.id_reserva, 'usuario_id', OLD.usuario_id, 'viaje_id', OLD.viaje_id, 'num_pasajeros', OLD.num_pasajeros, 'total', OLD.total, 'fecha_reserva', OLD.fecha_reserva),
        JSON_OBJECT('id_reserva', NEW.id_reserva, 'usuario_id', NEW.usuario_id, 'viaje_id', NEW.viaje_id, 'num_pasajeros', NEW.num_pasajeros, 'total', NEW.total, 'fecha_reserva', NEW.fecha_reserva)
    );
END$$

CREATE TRIGGER trg_reservas_delete AFTER DELETE ON reservas
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (db_user, table_name, operation, row_id, old_values)
    VALUES (CURRENT_USER(), 'reservas', 'DELETE', OLD.id_reserva,
        JSON_OBJECT('id_reserva', OLD.id_reserva, 'usuario_id', OLD.usuario_id, 'viaje_id', OLD.viaje_id, 'num_pasajeros', OLD.num_pasajeros, 'total', OLD.total, 'fecha_reserva', OLD.fecha_reserva)
    );
END$$

DELIMITER ;

-- Optional: show created triggers
SELECT TRIGGER_NAME, EVENT_MANIPULATION, EVENT_OBJECT_TABLE FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA = DATABASE();
