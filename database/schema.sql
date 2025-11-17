CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL CHECK(role IN ('paciente', 'medico', 'administrador')),
    remember_token VARCHAR(100),
    email_verified_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Pacientes (vinculada a users)
-- CI: 7-8 dígitos, edad no negativa, datos obligatorios
CREATE TABLE IF NOT EXISTS pacientes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER UNIQUE,
    ci VARCHAR(8) UNIQUE NOT NULL CHECK(length(ci) >= 7 AND length(ci) <= 8),
    nombre VARCHAR(100) NOT NULL,
    edad INTEGER NOT NULL CHECK(edad >= 0 AND edad <= 150),
    fecha_nacimiento DATE,
    genero VARCHAR(10) CHECK(genero IN ('Masculino', 'Femenino', 'Otro')),
    direccion TEXT,
    telefono VARCHAR(20),
    antecedentes TEXT,
    alergias TEXT,
    contacto_emergencia VARCHAR(100),
    grupo_sanguineo VARCHAR(5),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabla de Médicos (vinculada a users)
-- CI: 7-8 dígitos, datos profesionales obligatorios
CREATE TABLE IF NOT EXISTS medicos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER UNIQUE,
    ci VARCHAR(8) UNIQUE NOT NULL CHECK(length(ci) >= 7 AND length(ci) <= 8),
    nombre VARCHAR(100) NOT NULL,
    especialidad VARCHAR(100) NOT NULL,
    registro_profesional VARCHAR(50),
    turno VARCHAR(50),
    telefono VARCHAR(20),
    formacion_continua TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabla de Personal Administrativo (vinculada a users)
CREATE TABLE IF NOT EXISTS personal_administrativo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER UNIQUE,
    ci VARCHAR(8) UNIQUE NOT NULL CHECK(length(ci) >= 7 AND length(ci) <= 8),
    nombre VARCHAR(100) NOT NULL,
    cargo VARCHAR(100) NOT NULL,
    edad INTEGER CHECK(edad >= 18 AND edad <= 100),
    celular VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabla de Citas
-- Validaciones: fecha no pasada, hora válida, estado controlado
CREATE TABLE IF NOT EXISTS citas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    paciente_id INTEGER NOT NULL,
    medico_id INTEGER NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    motivo TEXT,
    diagnostico TEXT,
    tratamiento TEXT,
    observaciones TEXT,
    estado VARCHAR(50) DEFAULT 'Programada' CHECK(estado IN ('Programada', 'Confirmada', 'En Consulta', 'Atendida', 'Cancelada')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
);

-- Tabla de Historial Médico
-- Registro completo de atenciones médicas del paciente
CREATE TABLE IF NOT EXISTS historial_medico (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    paciente_id INTEGER NOT NULL,
    cita_id INTEGER,
    fecha DATE NOT NULL,
    tipo_atencion VARCHAR(100),
    diagnostico TEXT NOT NULL,
    tratamiento TEXT,
    observaciones TEXT,
    medico_id INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (cita_id) REFERENCES citas(id) ON DELETE SET NULL,
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE SET NULL
);

-- Tabla de Medicamentos
-- Validaciones: disponibilidad no negativa, control de caducidad
CREATE TABLE IF NOT EXISTS medicamentos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre_generico VARCHAR(200) NOT NULL,
    nombre_comercial VARCHAR(200),
    tipo VARCHAR(100),
    presentacion VARCHAR(100),
    dosis VARCHAR(100),
    disponibilidad INTEGER DEFAULT 0 CHECK(disponibilidad >= 0),
    precio_unitario DECIMAL(10,2) CHECK(precio_unitario >= 0),
    caducidad DATE,
    lote VARCHAR(50),
    laboratorio VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Recetas
-- Vinculada a citas y pacientes, con estado de dispensación
CREATE TABLE IF NOT EXISTS recetas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cita_id INTEGER NOT NULL,
    paciente_id INTEGER NOT NULL,
    medico_id INTEGER NOT NULL,
    fecha_emision DATE NOT NULL,
    indicaciones TEXT,
    dosis_general TEXT,
    duracion_tratamiento VARCHAR(100),
    estado VARCHAR(50) DEFAULT 'Pendiente' CHECK(estado IN ('Pendiente', 'Dispensada', 'Cancelada')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cita_id) REFERENCES citas(id) ON DELETE CASCADE,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
);

-- Tabla intermedia Recetas-Medicamentos
-- Cantidad debe ser positiva
CREATE TABLE IF NOT EXISTS receta_medicamento (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    receta_id INTEGER NOT NULL,
    medicamento_id INTEGER NOT NULL,
    cantidad INTEGER NOT NULL CHECK(cantidad > 0),
    dosis VARCHAR(100),
    frecuencia VARCHAR(100),
    duracion VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (receta_id) REFERENCES recetas(id) ON DELETE CASCADE,
    FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id) ON DELETE CASCADE
);

-- Tabla de Horarios de Atención de Médicos
-- Para gestionar disponibilidad de médicos
CREATE TABLE IF NOT EXISTS horarios_atencion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    medico_id INTEGER NOT NULL,
    dia_semana VARCHAR(20) NOT NULL CHECK(dia_semana IN ('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')),
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    activo BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
);

-- Tabla de Información de la Clínica
-- Para la página de inicio (Home)
CREATE TABLE IF NOT EXISTS informacion_clinica (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre VARCHAR(255) NOT NULL DEFAULT 'Clínica ISI La Fuente',
    descripcion TEXT,
    mision TEXT,
    vision TEXT,
    direccion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(255),
    horario_atencion TEXT,
    servicios TEXT,
    imagen_principal VARCHAR(255),
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    whatsapp VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- ÍNDICES PARA MEJORAR RENDIMIENTO
-- ============================================

-- Índices para usuarios
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_users_role ON users(role);

-- Índices para pacientes
CREATE INDEX IF NOT EXISTS idx_pacientes_ci ON pacientes(ci);
CREATE INDEX IF NOT EXISTS idx_pacientes_nombre ON pacientes(nombre);
CREATE INDEX IF NOT EXISTS idx_pacientes_user_id ON pacientes(user_id);

-- Índices para médicos
CREATE INDEX IF NOT EXISTS idx_medicos_ci ON medicos(ci);
CREATE INDEX IF NOT EXISTS idx_medicos_especialidad ON medicos(especialidad);
CREATE INDEX IF NOT EXISTS idx_medicos_user_id ON medicos(user_id);

-- Índices para citas
CREATE INDEX IF NOT EXISTS idx_citas_fecha ON citas(fecha);
CREATE INDEX IF NOT EXISTS idx_citas_estado ON citas(estado);
CREATE INDEX IF NOT EXISTS idx_citas_paciente ON citas(paciente_id);
CREATE INDEX IF NOT EXISTS idx_citas_medico ON citas(medico_id);
CREATE INDEX IF NOT EXISTS idx_citas_fecha_hora ON citas(fecha, hora);

-- Índices para historial médico
CREATE INDEX IF NOT EXISTS idx_historial_paciente ON historial_medico(paciente_id);
CREATE INDEX IF NOT EXISTS idx_historial_fecha ON historial_medico(fecha);

-- Índices para medicamentos
CREATE INDEX IF NOT EXISTS idx_medicamentos_nombre ON medicamentos(nombre_generico);
CREATE INDEX IF NOT EXISTS idx_medicamentos_disponibilidad ON medicamentos(disponibilidad);

-- Índices para recetas
CREATE INDEX IF NOT EXISTS idx_recetas_paciente ON recetas(paciente_id);
CREATE INDEX IF NOT EXISTS idx_recetas_medico ON recetas(medico_id);
CREATE INDEX IF NOT EXISTS idx_recetas_fecha ON recetas(fecha_emision);

-- ============================================
-- TRIGGERS PARA ACTUALIZAR updated_at
-- ============================================

CREATE TRIGGER IF NOT EXISTS update_users_timestamp 
AFTER UPDATE ON users
BEGIN
    UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

CREATE TRIGGER IF NOT EXISTS update_pacientes_timestamp 
AFTER UPDATE ON pacientes
BEGIN
    UPDATE pacientes SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

CREATE TRIGGER IF NOT EXISTS update_medicos_timestamp 
AFTER UPDATE ON medicos
BEGIN
    UPDATE medicos SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

CREATE TRIGGER IF NOT EXISTS update_citas_timestamp 
AFTER UPDATE ON citas
BEGIN
    UPDATE citas SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

CREATE TRIGGER IF NOT EXISTS update_medicamentos_timestamp 
AFTER UPDATE ON medicamentos
BEGIN
    UPDATE medicamentos SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

CREATE TRIGGER IF NOT EXISTS update_recetas_timestamp 
AFTER UPDATE ON recetas
BEGIN
    UPDATE recetas SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

-- ============================================
-- DATOS INICIALES
-- ============================================

-- Insertar información de la clínica
INSERT INTO informacion_clinica (
    nombre, descripcion, mision, vision, direccion, telefono, email, horario_atencion, servicios
) VALUES (
    'Clínica ISI La Fuente',
    'Clínica especializada en atención médica integral con enfoque en medicina preventiva y atención de calidad.',
    'Brindar atención médica de excelencia, con calidez humana, a través de un equipo de salud comprometido con la capacitación continua y la innovación tecnológica.',
    'Ser referentes a nivel nacional e internacional, brindando atención de calidad, con seguridad y plena satisfacción del paciente.',
    'Calle Beni entre 6 de octubre y Potosí, Nro. 60, Oruro, Bolivia',
    '+591 2 5252525',
    'info@clinicaislafuente.com',
    'Lunes a Viernes: 8:00 AM - 6:00 PM | Sábados: 8:00 AM - 12:00 PM',
    'Consultas médicas generales, Medicina preventiva, Chequeos ocupacionales, Atención pediátrica, Cardiología, Ginecología'
);

-- Insertar usuario administrador por defecto
-- Password: admin123 (debe cambiarse en producción)
INSERT INTO users (name, email, password, role) VALUES
('Administrador Sistema', 'admin@clinica.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5idSZ7I.g9Kha', 'administrador');

-- Vincular administrador al personal administrativo
INSERT INTO personal_administrativo (user_id, ci, nombre, cargo) VALUES
(1, '1234567', 'Administrador Sistema', 'Director General');

-- ============================================
-- VISTAS ÚTILES PARA REPORTES
-- ============================================

-- Vista de Citas Completas
CREATE VIEW IF NOT EXISTS vista_citas_completas AS
SELECT 
    c.id,
    c.fecha,
    c.hora,
    c.estado,
    c.motivo,
    c.diagnostico,
    c.tratamiento,
    p.nombre AS paciente_nombre,
    p.ci AS paciente_ci,
    p.edad AS paciente_edad,
    m.nombre AS medico_nombre,
    m.especialidad AS medico_especialidad
FROM citas c
INNER JOIN pacientes p ON c.paciente_id = p.id
INNER JOIN medicos m ON c.medico_id = m.id;

-- Vista de Historial Médico Completo
CREATE VIEW IF NOT EXISTS vista_historial_completo AS
SELECT 
    h.id,
    h.fecha,
    h.tipo_atencion,
    h.diagnostico,
    h.tratamiento,
    p.nombre AS paciente_nombre,
    p.ci AS paciente_ci,
    m.nombre AS medico_nombre,
    m.especialidad AS medico_especialidad
FROM historial_medico h
INNER JOIN pacientes p ON h.paciente_id = p.id
LEFT JOIN medicos m ON h.medico_id = m.id;

-- Vista de Stock de Medicamentos
CREATE VIEW IF NOT EXISTS vista_stock_medicamentos AS
SELECT 
    id,
    nombre_generico,
    nombre_comercial,
    tipo,
    dosis,
    disponibilidad,
    caducidad,
    CASE 
        WHEN disponibilidad = 0 THEN 'Sin Stock'
        WHEN disponibilidad < 20 THEN 'Stock Bajo'
        WHEN disponibilidad < 50 THEN 'Stock Medio'
        ELSE 'Stock Suficiente'
    END AS nivel_stock,
    CASE 
        WHEN caducidad < DATE('now') THEN 'Vencido'
        WHEN caducidad < DATE('now', '+30 days') THEN 'Por Vencer'
        ELSE 'Vigente'
    END AS estado_caducidad
FROM medicamentos;