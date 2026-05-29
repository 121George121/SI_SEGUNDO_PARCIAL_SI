<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. RAW SQL Schema creation
        DB::unprepared("
            -- ============================================
            -- 1. TABLAS BASE (SIN FK O CON PK SIMPLE)
            -- ============================================

            CREATE TABLE PERSONA (
                Id_persona SERIAL,
                ci VARCHAR(20) NOT NULL UNIQUE,
                nombre VARCHAR(100) NOT NULL,
                apellido VARCHAR(100) NOT NULL,
                fecha_nacimiento DATE NOT NULL,
                telefono VARCHAR(20),
                direccion TEXT,
                correo VARCHAR(150),
                PRIMARY KEY (Id_persona)
            );

            CREATE TABLE CARRERA (
                Id_carrera SERIAL,
                nombre_carrera VARCHAR(150) NOT NULL,
                descripcion TEXT,
                duracion_anios INTEGER,
                PRIMARY KEY (Id_carrera)
            );

            CREATE TABLE GESTION (
                Id_gestion SERIAL,
                anio VARCHAR(20) NOT NULL,
                periodo VARCHAR(50) NOT NULL,
                fecha_inicio DATE,
                fecha_fin DATE,
                PRIMARY KEY (Id_gestion)
            );

            CREATE TABLE ESPECIALIDAD (
                Id_especialidad SERIAL,
                nombre_especialidad VARCHAR(150) NOT NULL,
                descripcion TEXT,
                PRIMARY KEY (Id_especialidad)
            );

            CREATE TABLE AULA (
                Id_aula SERIAL,
                codigo_aula VARCHAR(50) NOT NULL UNIQUE,
                capacidad INTEGER,
                ubicacion VARCHAR(100),
                PRIMARY KEY (Id_aula)
            );

            CREATE TABLE MODALIDAD (
                Id_modalidad SERIAL,
                nombre_modalidad VARCHAR(100) NOT NULL,
                descripcion TEXT,
                PRIMARY KEY (Id_modalidad)
            );

            CREATE TABLE TURNO (
                Id_turno SERIAL,
                nombre_turno VARCHAR(50) NOT NULL,
                hora_inicio TIME,
                hora_fin TIME,
                PRIMARY KEY (Id_turno)
            );

            CREATE TABLE HORARIO (
                Id_horario SERIAL,
                dia_semana VARCHAR(20),
                hora_inicio TIME,
                hora_fin TIME,
                PRIMARY KEY (Id_horario)
            );

            CREATE TABLE MATERIA (
                Id_materia SERIAL,
                nombre_materia VARCHAR(150) NOT NULL,
                codigo_materia VARCHAR(50) UNIQUE,
                creditos INTEGER,
                PRIMARY KEY (Id_materia)
            );

            CREATE TABLE ROL (
                Id_rol SERIAL,
                nombre_rol VARCHAR(50) NOT NULL UNIQUE,
                descripcion TEXT,
                PRIMARY KEY (Id_rol)
            );

            CREATE TABLE COMPROBANTE (
                Id_comprobante SERIAL,
                tipo_comprobante VARCHAR(50),
                numero_comprobante VARCHAR(100),
                fecha_emision DATE,
                PRIMARY KEY (Id_comprobante)
            );

            -- ============================================
            -- 2. TABLAS CON FK
            -- ============================================

            -- TABLA SUPERADMINISTRADOR
            CREATE TABLE SUPERADMINISTRADOR (
                Id_superadministrador INTEGER NOT NULL,
                cargo VARCHAR(100),
                estado VARCHAR(20) NOT NULL,
                PRIMARY KEY (Id_superadministrador),
                FOREIGN KEY (Id_superadministrador) REFERENCES PERSONA(Id_persona)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_superadmin_estado CHECK (estado IN ('Activo', 'Inactivo'))
            );

            -- TABLA ADMINISTRADOR
            CREATE TABLE ADMINISTRADOR (
                Id_administrador INTEGER NOT NULL,
                cargo VARCHAR(100),
                area VARCHAR(100),
                estado VARCHAR(20) NOT NULL,
                PRIMARY KEY (Id_administrador),
                FOREIGN KEY (Id_administrador) REFERENCES PERSONA(Id_persona)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_admin_estado CHECK (estado IN ('Activo', 'Inactivo'))
            );

            -- TABLA DOCENTE
            CREATE TABLE DOCENTE (
                Id_docente INTEGER NOT NULL,
                anio_servicio INTEGER,
                estado VARCHAR(20) NOT NULL,
                PRIMARY KEY (Id_docente),
                FOREIGN KEY (Id_docente) REFERENCES PERSONA(Id_persona)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_docente_estado CHECK (estado IN ('Activo', 'Inactivo'))
            );

            -- TABLA ASIGNACIONCUPO
            CREATE TABLE ASIGNACIONCUPO (
                Id_asignacioncupo SERIAL,
                fecha_asignacion DATE NOT NULL DEFAULT CURRENT_DATE,
                promedio_final DECIMAL(5,2),
                puesto_merito INTEGER,
                estado_asignacion VARCHAR(20) NOT NULL,
                Id_carrera INTEGER NOT NULL,
                Id_gestion INTEGER NOT NULL,
                PRIMARY KEY (Id_asignacioncupo),
                FOREIGN KEY (Id_carrera) REFERENCES CARRERA(Id_carrera)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_gestion) REFERENCES GESTION(Id_gestion)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA POSTULANTE
            CREATE TABLE POSTULANTE (
                Id_postulante INTEGER NOT NULL,
                estado_inscripcion VARCHAR(20) NOT NULL,
                fecha_registro DATE NOT NULL DEFAULT CURRENT_DATE,
                Id_asignacion INTEGER,
                PRIMARY KEY (Id_postulante),
                FOREIGN KEY (Id_postulante) REFERENCES PERSONA(Id_persona)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_asignacion) REFERENCES ASIGNACIONCUPO(Id_asignacioncupo)
                    ON UPDATE CASCADE
                    ON DELETE SET NULL
            );

            -- TABLA USUARIO
            CREATE TABLE USUARIO (
                Id_usuario SERIAL,
                nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
                correo VARCHAR(150) NOT NULL UNIQUE,
                contraseña VARCHAR(255) NOT NULL,
                estado VARCHAR(20) NOT NULL,
                fecha_creacion DATE NOT NULL DEFAULT CURRENT_DATE,
                Id_rol INTEGER NOT NULL,
                Id_persona INTEGER NOT NULL,
                PRIMARY KEY (Id_usuario),
                FOREIGN KEY (Id_rol) REFERENCES ROL(Id_rol)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_persona) REFERENCES PERSONA(Id_persona)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_usuario_estado CHECK (estado IN ('Activo', 'Inactivo'))
            );

            -- TABLA REPORTE
            CREATE TABLE REPORTE (
                Id_reporte SERIAL,
                tipo_reporte VARCHAR(50) NOT NULL,
                fecha_generacion DATE NOT NULL DEFAULT CURRENT_DATE,
                descripcion TEXT,
                Id_usuario INTEGER NOT NULL,
                PRIMARY KEY (Id_reporte),
                FOREIGN KEY (Id_usuario) REFERENCES USUARIO(Id_usuario)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA BITACORA
            CREATE TABLE BITACORA (
                Id_bitacora SERIAL,
                tipo VARCHAR(50) NOT NULL,
                descripcion TEXT,
                fecha DATE NOT NULL DEFAULT CURRENT_DATE,
                hora TIME NOT NULL DEFAULT CURRENT_TIME,
                estado VARCHAR(20) NOT NULL,
                Id_usuario INTEGER NOT NULL,
                PRIMARY KEY (Id_bitacora),
                FOREIGN KEY (Id_usuario) REFERENCES USUARIO(Id_usuario)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA DETALLE_BITACORA
            CREATE TABLE DETALLE_BITACORA (
                Id_detallebitacora SERIAL,
                Id_bitacora INTEGER NOT NULL,
                direccion_ip VARCHAR(45),
                hora_inicio TIME,
                hora_fin TIME,
                accion TEXT,
                PRIMARY KEY (Id_detallebitacora, Id_bitacora),
                FOREIGN KEY (Id_bitacora) REFERENCES BITACORA(Id_bitacora)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA INSCRIPCION
            CREATE TABLE INSCRIPCION (
                Id_inscripcion SERIAL,
                codigo_inscripcion VARCHAR(50) NOT NULL UNIQUE,
                estado VARCHAR(20) NOT NULL,
                fecha_inscripcion DATE NOT NULL DEFAULT CURRENT_DATE,
                Id_postulante INTEGER NOT NULL,
                PRIMARY KEY (Id_inscripcion),
                FOREIGN KEY (Id_postulante) REFERENCES POSTULANTE(Id_postulante)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA INSCRIPCION_CARRERA
            CREATE TABLE INSCRIPCION_CARRERA (
                Id_inscripcion INTEGER NOT NULL,
                Id_carrera INTEGER NOT NULL,
                prioridad VARCHAR(50) NOT NULL,
                estado VARCHAR(20) NOT NULL,
                PRIMARY KEY (Id_inscripcion, Id_carrera),
                FOREIGN KEY (Id_inscripcion) REFERENCES INSCRIPCION(Id_inscripcion)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_carrera) REFERENCES CARRERA(Id_carrera)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_inscripcion_carrera_estado CHECK (estado IN ('Activo', 'Inactivo'))
            );

            -- TABLA DOCUMENTO
            CREATE TABLE DOCUMENTO (
                Id_documento SERIAL,
                tipo_documento VARCHAR(50) NOT NULL,
                nombre VARCHAR(255) NOT NULL,
                estado VARCHAR(20) NOT NULL,
                observacion TEXT,
                fecha_registro DATE NOT NULL DEFAULT CURRENT_DATE,
                fecha_validacion DATE,
                Id_administrador INTEGER,
                Id_postulante INTEGER,
                PRIMARY KEY (Id_documento),
                FOREIGN KEY (Id_administrador) REFERENCES ADMINISTRADOR(Id_administrador)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_postulante) REFERENCES POSTULANTE(Id_postulante)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_doc_estado CHECK (estado IN ('Validado', 'Rechazado', 'Pendiente'))
            );

            -- TABLA CUPOCARRERA
            CREATE TABLE CUPOCARRERA (
                Id_cupo SERIAL,
                gestion VARCHAR(50),
                cantidad_cupos INTEGER NOT NULL,
                cupos_ocupados INTEGER NOT NULL DEFAULT 0,
                cupos_disponibles INTEGER NOT NULL,
                Id_gestion INTEGER NOT NULL,
                Id_carrera INTEGER NOT NULL,
                PRIMARY KEY (Id_cupo),
                FOREIGN KEY (Id_gestion) REFERENCES GESTION(Id_gestion)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_carrera) REFERENCES CARRERA(Id_carrera)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_cupos CHECK (cupos_disponibles >= 0 AND cupos_ocupados <= cantidad_cupos)
            );

            -- TABLA PAGO
            CREATE TABLE PAGO (
                Id_pago SERIAL,
                monto DECIMAL(10,2) NOT NULL,
                fecha_pago DATE NOT NULL DEFAULT CURRENT_DATE,
                metodo_pago VARCHAR(50),
                estado_pago VARCHAR(20) NOT NULL,
                observaciones TEXT,
                Id_comprobante INTEGER,
                Id_inscripcion INTEGER NOT NULL,
                PRIMARY KEY (Id_pago),
                FOREIGN KEY (Id_comprobante) REFERENCES COMPROBANTE(Id_comprobante)
                    ON UPDATE CASCADE
                    ON DELETE SET NULL,
                FOREIGN KEY (Id_inscripcion) REFERENCES INSCRIPCION(Id_inscripcion)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_pago_monto CHECK (monto > 0),
                CONSTRAINT check_pago_estado CHECK (estado_pago IN ('Pagado', 'Rechazado', 'Pendiente'))
            );

            -- TABLA DOCENTE_ESPECIALIDAD
            CREATE TABLE DOCENTE_ESPECIALIDAD (
                Id_docente INTEGER NOT NULL,
                Id_especialidad INTEGER NOT NULL,
                PRIMARY KEY (Id_docente, Id_especialidad),
                FOREIGN KEY (Id_docente) REFERENCES DOCENTE(Id_docente)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_especialidad) REFERENCES ESPECIALIDAD(Id_especialidad)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA GRUPO
            CREATE TABLE GRUPO (
                Id_grupo SERIAL,
                sigla_grupo VARCHAR(20) NOT NULL UNIQUE,
                capacidad_max INTEGER NOT NULL,
                estado VARCHAR(20) NOT NULL,
                cant_estudiantes INTEGER NOT NULL DEFAULT 0,
                Id_aula INTEGER NOT NULL,
                Id_modalidad INTEGER NOT NULL,
                Id_turno INTEGER NOT NULL,
                Id_docente INTEGER NOT NULL,
                Id_gestion INTEGER NOT NULL,
                PRIMARY KEY (Id_grupo),
                FOREIGN KEY (Id_aula) REFERENCES AULA(Id_aula)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_modalidad) REFERENCES MODALIDAD(Id_modalidad)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_turno) REFERENCES TURNO(Id_turno)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_docente) REFERENCES DOCENTE(Id_docente)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_gestion) REFERENCES GESTION(Id_gestion)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_grupo_estado CHECK (estado IN ('Activo', 'Inactivo')),
                CONSTRAINT check_capacidad CHECK (cant_estudiantes <= capacidad_max)
            );

            -- TABLA GRUPO_HORARIO
            CREATE TABLE GRUPO_HORARIO (
                Id_grupo INTEGER NOT NULL,
                Id_horario INTEGER NOT NULL,
                PRIMARY KEY (Id_grupo, Id_horario),
                FOREIGN KEY (Id_grupo) REFERENCES GRUPO(Id_grupo)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_horario) REFERENCES HORARIO(Id_horario)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA GRUPO_MATERIA
            CREATE TABLE GRUPO_MATERIA (
                Id_grupo INTEGER NOT NULL,
                Id_materia INTEGER NOT NULL,
                Id_docente INTEGER NOT NULL,
                PRIMARY KEY (Id_grupo, Id_materia),
                FOREIGN KEY (Id_grupo) REFERENCES GRUPO(Id_grupo)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_materia) REFERENCES MATERIA(Id_materia)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_docente) REFERENCES DOCENTE(Id_docente)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA EVALUACION
            CREATE TABLE EVALUACION (
                Id_evaluacion SERIAL,
                numero_evaluacion INTEGER NOT NULL,
                porcentaje DECIMAL(5,2) NOT NULL,
                fecha DATE NOT NULL,
                estado VARCHAR(20) NOT NULL,
                Id_grupo INTEGER NOT NULL,
                Id_materia INTEGER,
                PRIMARY KEY (Id_evaluacion),
                FOREIGN KEY (Id_grupo) REFERENCES GRUPO(Id_grupo)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_materia) REFERENCES MATERIA(Id_materia)
                    ON UPDATE CASCADE
                    ON DELETE SET NULL,
                CONSTRAINT check_evaluacion_porcentaje CHECK (porcentaje > 0 AND porcentaje <= 100),
                CONSTRAINT check_evaluacion_estado CHECK (estado IN ('Activo', 'Inactivo'))
            );

            -- TABLA GRUPO_POSTULANTE
            CREATE TABLE GRUPO_POSTULANTE (
                Id_grupo INTEGER NOT NULL,
                Id_postulante INTEGER NOT NULL,
                estado VARCHAR(20) NOT NULL,
                fecha_asignacion DATE NOT NULL DEFAULT CURRENT_DATE,
                PRIMARY KEY (Id_grupo, Id_postulante),
                FOREIGN KEY (Id_grupo) REFERENCES GRUPO(Id_grupo)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_postulante) REFERENCES POSTULANTE(Id_postulante)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA NOTA
            CREATE TABLE NOTA (
                Id_nota SERIAL,
                nota DECIMAL(5,2),
                estado_academico VARCHAR(50),
                fecha DATE NOT NULL DEFAULT CURRENT_DATE,
                Id_evaluacion INTEGER NOT NULL,
                Id_grupo INTEGER NOT NULL,
                Id_postulante INTEGER,
                PRIMARY KEY (Id_nota),
                FOREIGN KEY (Id_evaluacion) REFERENCES EVALUACION(Id_evaluacion)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_grupo) REFERENCES GRUPO(Id_grupo)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_postulante) REFERENCES POSTULANTE(Id_postulante)
                    ON UPDATE CASCADE
                    ON DELETE SET NULL
            );

            -- TABLA RESULTADOACADEMICO
            CREATE TABLE RESULTADOACADEMICO (
                Id_resultado SERIAL,
                promedio_final DECIMAL(5,2),
                estado_final VARCHAR(50),
                fecha_calculo DATE NOT NULL DEFAULT CURRENT_DATE,
                Id_postulante INTEGER NOT NULL,
                PRIMARY KEY (Id_resultado),
                FOREIGN KEY (Id_postulante) REFERENCES POSTULANTE(Id_postulante)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            );

            -- TABLA ASISTENCIA
            CREATE TABLE ASISTENCIA (
                Id_asistencia SERIAL,
                fecha DATE NOT NULL DEFAULT CURRENT_DATE,
                hora TIME NOT NULL DEFAULT CURRENT_TIME,
                estado VARCHAR(20) NOT NULL,
                observacion TEXT,
                Id_materia INTEGER NOT NULL,
                Id_grupo INTEGER NOT NULL,
                Id_postulante INTEGER NOT NULL,
                PRIMARY KEY (Id_asistencia),
                FOREIGN KEY (Id_materia) REFERENCES MATERIA(Id_materia)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_grupo) REFERENCES GRUPO(Id_grupo)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (Id_postulante) REFERENCES POSTULANTE(Id_postulante)
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                CONSTRAINT check_asistencia_estado CHECK (estado IN ('Presente', 'Ausente', 'Tarde'))
            );

            -- ============================================
            -- 3. ÍNDICES PARA OPTIMIZACIÓN
            -- ============================================

            CREATE INDEX idx_persona_ci ON PERSONA(ci);
            CREATE INDEX idx_persona_nombre_apellido ON PERSONA(nombre, apellido);
            CREATE INDEX idx_usuario_nombre ON USUARIO(nombre_usuario);
            CREATE INDEX idx_postulante_persona ON POSTULANTE(Id_postulante);
            CREATE INDEX idx_asistencia_fecha ON ASISTENCIA(fecha);
            CREATE INDEX idx_nota_evaluacion ON NOTA(Id_evaluacion);
            CREATE INDEX idx_inscripcion_postulante ON INSCRIPCION(Id_postulante);
            CREATE INDEX idx_pago_inscripcion ON PAGO(Id_inscripcion);
        ");

        // 2. CREATE TRIGGERS AND FUNCTIONS IN POSTGRESQL
        DB::unprepared("
            -- Trigger function: Update weighted GPA when grades are recorded
            CREATE OR REPLACE FUNCTION actualizar_promedio_resultado()
            RETURNS TRIGGER AS $$
            DECLARE
                v_id_postulante INT;
                v_id_grupo INT;
                v_promedio DECIMAL(5,2);
                v_estado VARCHAR(50);
            BEGIN
                IF TG_OP = 'DELETE' THEN
                    v_id_postulante := OLD.Id_postulante;
                    v_id_grupo := OLD.Id_grupo;
                ELSE
                    v_id_postulante := NEW.Id_postulante;
                    v_id_grupo := NEW.Id_grupo;
                END IF;

                IF v_id_postulante IS NOT NULL AND v_id_grupo IS NOT NULL THEN
                    -- Sum (grade * percentage/100) for all evaluations in the group
                    SELECT COALESCE(SUM(n.nota * (e.porcentaje / 100.0)), 0)
                    INTO v_promedio
                    FROM NOTA n
                    JOIN EVALUACION e ON n.Id_evaluacion = e.Id_evaluacion
                    WHERE n.Id_postulante = v_id_postulante
                      AND n.Id_grupo = v_id_grupo;

                    IF v_promedio >= 51.0 THEN
                        v_estado := 'Aprobado';
                    ELSE
                        v_estado := 'Reprobado';
                    END IF;

                    -- Update or Insert into RESULTADOACADEMICO
                    IF EXISTS (SELECT 1 FROM RESULTADOACADEMICO WHERE Id_postulante = v_id_postulante) THEN
                        UPDATE RESULTADOACADEMICO
                        SET promedio_final = v_promedio,
                            estado_final = v_estado,
                            fecha_calculo = CURRENT_DATE
                        WHERE Id_postulante = v_id_postulante;
                    ELSE
                        INSERT INTO RESULTADOACADEMICO (promedio_final, estado_final, fecha_calculo, Id_postulante)
                        VALUES (v_promedio, v_estado, CURRENT_DATE, v_id_postulante);
                    END IF;
                END IF;
                RETURN NULL;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trg_actualizar_promedio
            AFTER INSERT OR UPDATE OR DELETE ON NOTA
            FOR EACH ROW
            EXECUTE FUNCTION actualizar_promedio_resultado();
        ");

        DB::unprepared("
            -- Trigger function: Auto assign a group when a Payment is approved ('Pagado')
            CREATE OR REPLACE FUNCTION trigger_asignacion_grupo_automatico()
            RETURNS TRIGGER AS $$
            DECLARE
                v_id_postulante INT;
                v_id_gestion INT;
                v_id_grupo INT;
            BEGIN
                IF NEW.estado_pago = 'Pagado' AND (OLD.estado_pago IS NULL OR OLD.estado_pago <> 'Pagado') THEN
                    -- Retrieve postulante from the enrollment
                    SELECT Id_postulante INTO v_id_postulante
                    FROM INSCRIPCION
                    WHERE Id_inscripcion = NEW.Id_inscripcion;

                    IF v_id_postulante IS NOT NULL THEN
                        -- Retrieve the latest active GESTION
                        SELECT Id_gestion INTO v_id_gestion
                        FROM GESTION
                        ORDER BY Id_gestion DESC
                        LIMIT 1;

                        IF v_id_gestion IS NOT NULL THEN
                            -- Find an active group with available capacity
                            SELECT Id_grupo INTO v_id_grupo
                            FROM GRUPO
                            WHERE Id_gestion = v_id_gestion
                              AND estado = 'Activo'
                              AND cant_estudiantes < capacidad_max
                            LIMIT 1;

                            IF v_id_grupo IS NOT NULL THEN
                                -- Check if student is already in the group
                                IF NOT EXISTS (SELECT 1 FROM GRUPO_POSTULANTE WHERE Id_grupo = v_id_grupo AND Id_postulante = v_id_postulante) THEN
                                    -- Assign student
                                    INSERT INTO GRUPO_POSTULANTE (Id_grupo, Id_postulante, estado, fecha_asignacion)
                                    VALUES (v_id_grupo, v_id_postulante, 'Activo', CURRENT_DATE);

                                    -- Increment count
                                    UPDATE GRUPO
                                    SET cant_estudiantes = cant_estudiantes + 1
                                    WHERE Id_grupo = v_id_grupo;

                                    -- Update student state
                                    UPDATE POSTULANTE
                                    SET estado_inscripcion = 'Asignado'
                                    WHERE Id_postulante = v_id_postulante;
                                END IF;
                            END IF;
                        END IF;
                    END IF;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trg_asignacion_grupo
            AFTER UPDATE ON PAGO
            FOR EACH ROW
            EXECUTE FUNCTION trigger_asignacion_grupo_automatico();
        ");

        DB::unprepared("
            -- Stored Procedure: Meritocratic cupo assignment based on final GPA
            CREATE OR REPLACE FUNCTION asignar_cupos_carrera(p_id_carrera INT, p_id_gestion INT)
            RETURNS VOID AS $$
            DECLARE
                v_cantidad_cupos INT;
                v_cupo_id INT;
                v_rec RECORD;
                v_puesto INT := 1;
                v_asignacion_id INT;
                v_estado_asig VARCHAR(20);
                v_estado_insc VARCHAR(20);
            BEGIN
                -- Find cupo limits
                SELECT Id_cupo, cantidad_cupos INTO v_cupo_id, v_cantidad_cupos
                FROM CUPOCARRERA
                WHERE Id_carrera = p_id_carrera AND Id_gestion = p_id_gestion;

                IF v_cantidad_cupos IS NULL THEN
                    v_cantidad_cupos := 0;
                END IF;

                -- Reset cupos occupied/available
                UPDATE CUPOCARRERA
                SET cupos_ocupados = 0,
                    cupos_disponibles = cantidad_cupos
                WHERE Id_carrera = p_id_carrera AND Id_gestion = p_id_gestion;

                -- Loop candidates by GPA desc (only paid enrollments)
                FOR v_rec IN 
                    SELECT p.Id_postulante, ra.promedio_final
                    FROM POSTULANTE p
                    JOIN INSCRIPCION i ON p.Id_postulante = i.Id_postulante
                    JOIN INSCRIPCION_CARRERA ic ON i.Id_inscripcion = ic.Id_inscripcion
                    JOIN PAGO pg ON i.Id_inscripcion = pg.Id_inscripcion
                    LEFT JOIN RESULTADOACADEMICO ra ON p.Id_postulante = ra.Id_postulante
                    WHERE ic.Id_carrera = p_id_carrera
                      AND pg.estado_pago = 'Pagado'
                      AND ic.estado = 'Activo'
                    ORDER BY COALESCE(ra.promedio_final, 0.0) DESC
                LOOP
                    IF v_puesto <= v_cantidad_cupos THEN
                        v_estado_asig := 'Asignado';
                        v_estado_insc := 'Admitido';

                        -- Increment occupied count
                        UPDATE CUPOCARRERA
                        SET cupos_ocupados = cupos_ocupados + 1,
                            cupos_disponibles = cupos_disponibles - 1
                        WHERE Id_cupo = v_cupo_id;
                    ELSE
                        v_estado_asig := 'No Asignado';
                        v_estado_insc := 'No Admitido';
                    END IF;

                    -- Insert into ASIGNACIONCUPO
                    INSERT INTO ASIGNACIONCUPO (fecha_asignacion, promedio_final, puesto_merito, estado_asignacion, Id_carrera, Id_gestion)
                    VALUES (CURRENT_DATE, v_rec.promedio_final, v_puesto, v_estado_asig, p_id_carrera, p_id_gestion)
                    RETURNING Id_asignacioncupo INTO v_asignacion_id;

                    -- Link to Postulante
                    UPDATE POSTULANTE
                    SET Id_asignacion = v_asignacion_id,
                        estado_inscripcion = v_estado_insc
                    WHERE Id_postulante = v_rec.Id_postulante;

                    v_puesto := v_puesto + 1;
                END LOOP;
            END;
            $$ LANGUAGE plpgsql;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers and functions
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_actualizar_promedio ON NOTA;
            DROP FUNCTION IF EXISTS actualizar_promedio_resultado();

            DROP TRIGGER IF EXISTS trg_asignacion_grupo ON PAGO;
            DROP FUNCTION IF EXISTS trigger_asignacion_grupo_automatico();

            DROP FUNCTION IF EXISTS asignar_cupos_carrera(INT, INT);
        ");

        // Drop tables in correct order
        Schema::dropIfExists('ASISTENCIA');
        Schema::dropIfExists('RESULTADOACADEMICO');
        Schema::dropIfExists('NOTA');
        Schema::dropIfExists('GRUPO_POSTULANTE');
        Schema::dropIfExists('EVALUACION');
        Schema::dropIfExists('GRUPO_MATERIA');
        Schema::dropIfExists('GRUPO_HORARIO');
        Schema::dropIfExists('GRUPO');
        Schema::dropIfExists('DOCENTE_ESPECIALIDAD');
        Schema::dropIfExists('PAGO');
        Schema::dropIfExists('CUPOCARRERA');
        Schema::dropIfExists('DOCUMENTO');
        Schema::dropIfExists('INSCRIPCION_CARRERA');
        Schema::dropIfExists('INSCRIPCION');
        Schema::dropIfExists('DETALLE_BITACORA');
        Schema::dropIfExists('BITACORA');
        Schema::dropIfExists('REPORTE');
        Schema::dropIfExists('USUARIO');
        Schema::dropIfExists('POSTULANTE');
        Schema::dropIfExists('ASIGNACIONCUPO');
        Schema::dropIfExists('DOCENTE');
        Schema::dropIfExists('ADMINISTRADOR');
        Schema::dropIfExists('SUPERADMINISTRADOR');
        Schema::dropIfExists('COMPROBANTE');
        Schema::dropIfExists('ROL');
        Schema::dropIfExists('MATERIA');
        Schema::dropIfExists('HORARIO');
        Schema::dropIfExists('TURNO');
        Schema::dropIfExists('MODALIDAD');
        Schema::dropIfExists('AULA');
        Schema::dropIfExists('ESPECIALIDAD');
        Schema::dropIfExists('GESTION');
        Schema::dropIfExists('CARRERA');
        Schema::dropIfExists('PERSONA');
    }
};
