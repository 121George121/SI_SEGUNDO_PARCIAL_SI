--
-- PostgreSQL database dump
--

\restrict 4X1vikKEZZf4mdVSXbvQkXgfihDyCR1bNoDKEH1cZiEH8NzhLcR26YsLPKXY664

-- Dumped from database version 17.9
-- Dumped by pg_dump version 17.9

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: administrador; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.administrador (
    id_administrador integer NOT NULL,
    cargo character varying(100),
    area character varying(100),
    estado character varying(20) NOT NULL,
    CONSTRAINT check_admin_estado CHECK (((estado)::text = ANY ((ARRAY['Activo'::character varying, 'Inactivo'::character varying])::text[])))
);


ALTER TABLE public.administrador OWNER TO postgres;

--
-- Name: asignacioncupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asignacioncupo (
    id_asignacioncupo integer NOT NULL,
    fecha_asignacion date DEFAULT CURRENT_DATE NOT NULL,
    promedio_final numeric(5,2),
    puesto_merito integer,
    estado_asignacion character varying(20) NOT NULL,
    id_carrera integer NOT NULL,
    id_gestion integer NOT NULL
);


ALTER TABLE public.asignacioncupo OWNER TO postgres;

--
-- Name: asignacioncupo_id_asignacioncupo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asignacioncupo_id_asignacioncupo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asignacioncupo_id_asignacioncupo_seq OWNER TO postgres;

--
-- Name: asignacioncupo_id_asignacioncupo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asignacioncupo_id_asignacioncupo_seq OWNED BY public.asignacioncupo.id_asignacioncupo;


--
-- Name: asistencia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asistencia (
    id_asistencia integer NOT NULL,
    fecha date DEFAULT CURRENT_DATE NOT NULL,
    hora time without time zone DEFAULT CURRENT_TIME NOT NULL,
    estado character varying(20) NOT NULL,
    observacion text,
    id_materia integer NOT NULL,
    id_grupo integer NOT NULL,
    id_postulante integer NOT NULL,
    CONSTRAINT check_asistencia_estado CHECK (((estado)::text = ANY ((ARRAY['Presente'::character varying, 'Ausente'::character varying, 'Tarde'::character varying])::text[])))
);


ALTER TABLE public.asistencia OWNER TO postgres;

--
-- Name: asistencia_id_asistencia_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asistencia_id_asistencia_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asistencia_id_asistencia_seq OWNER TO postgres;

--
-- Name: asistencia_id_asistencia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asistencia_id_asistencia_seq OWNED BY public.asistencia.id_asistencia;


--
-- Name: aula; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aula (
    id_aula integer NOT NULL,
    codigo_aula character varying(50) NOT NULL,
    capacidad integer,
    ubicacion character varying(100)
);


ALTER TABLE public.aula OWNER TO postgres;

--
-- Name: aula_id_aula_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aula_id_aula_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.aula_id_aula_seq OWNER TO postgres;

--
-- Name: aula_id_aula_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aula_id_aula_seq OWNED BY public.aula.id_aula;


--
-- Name: bitacora; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.bitacora (
    id_bitacora integer NOT NULL,
    tipo character varying(50) NOT NULL,
    descripcion text,
    fecha date DEFAULT CURRENT_DATE NOT NULL,
    hora time without time zone DEFAULT CURRENT_TIME NOT NULL,
    estado character varying(20) NOT NULL,
    id_usuario integer NOT NULL
);


ALTER TABLE public.bitacora OWNER TO postgres;

--
-- Name: bitacora_id_bitacora_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.bitacora_id_bitacora_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.bitacora_id_bitacora_seq OWNER TO postgres;

--
-- Name: bitacora_id_bitacora_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.bitacora_id_bitacora_seq OWNED BY public.bitacora.id_bitacora;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: carrera; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carrera (
    id_carrera integer NOT NULL,
    nombre_carrera character varying(150) NOT NULL,
    descripcion text,
    duracion_anios integer
);


ALTER TABLE public.carrera OWNER TO postgres;

--
-- Name: carrera_id_carrera_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carrera_id_carrera_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carrera_id_carrera_seq OWNER TO postgres;

--
-- Name: carrera_id_carrera_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carrera_id_carrera_seq OWNED BY public.carrera.id_carrera;


--
-- Name: comprobante; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.comprobante (
    id_comprobante integer NOT NULL,
    tipo_comprobante character varying(50),
    numero_comprobante character varying(100),
    fecha_emision date
);


ALTER TABLE public.comprobante OWNER TO postgres;

--
-- Name: comprobante_id_comprobante_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.comprobante_id_comprobante_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.comprobante_id_comprobante_seq OWNER TO postgres;

--
-- Name: comprobante_id_comprobante_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.comprobante_id_comprobante_seq OWNED BY public.comprobante.id_comprobante;


--
-- Name: cupocarrera; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cupocarrera (
    id_cupo integer NOT NULL,
    gestion character varying(50),
    cantidad_cupos integer NOT NULL,
    cupos_ocupados integer DEFAULT 0 NOT NULL,
    cupos_disponibles integer NOT NULL,
    id_gestion integer NOT NULL,
    id_carrera integer NOT NULL,
    CONSTRAINT check_cupos CHECK (((cupos_disponibles >= 0) AND (cupos_ocupados <= cantidad_cupos)))
);


ALTER TABLE public.cupocarrera OWNER TO postgres;

--
-- Name: cupocarrera_id_cupo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cupocarrera_id_cupo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cupocarrera_id_cupo_seq OWNER TO postgres;

--
-- Name: cupocarrera_id_cupo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cupocarrera_id_cupo_seq OWNED BY public.cupocarrera.id_cupo;


--
-- Name: detalle_bitacora; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalle_bitacora (
    id_detallebitacora integer NOT NULL,
    id_bitacora integer NOT NULL,
    direccion_ip character varying(45),
    hora_inicio time without time zone,
    hora_fin time without time zone,
    accion text
);


ALTER TABLE public.detalle_bitacora OWNER TO postgres;

--
-- Name: detalle_bitacora_id_detallebitacora_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.detalle_bitacora_id_detallebitacora_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.detalle_bitacora_id_detallebitacora_seq OWNER TO postgres;

--
-- Name: detalle_bitacora_id_detallebitacora_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.detalle_bitacora_id_detallebitacora_seq OWNED BY public.detalle_bitacora.id_detallebitacora;


--
-- Name: docente; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.docente (
    id_docente integer NOT NULL,
    anio_servicio integer,
    estado character varying(20) NOT NULL,
    CONSTRAINT check_docente_estado CHECK (((estado)::text = ANY ((ARRAY['Activo'::character varying, 'Inactivo'::character varying])::text[])))
);


ALTER TABLE public.docente OWNER TO postgres;

--
-- Name: docente_especialidad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.docente_especialidad (
    id_docente integer NOT NULL,
    id_especialidad integer NOT NULL
);


ALTER TABLE public.docente_especialidad OWNER TO postgres;

--
-- Name: documento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.documento (
    id_documento integer NOT NULL,
    tipo_documento character varying(50) NOT NULL,
    nombre character varying(255) NOT NULL,
    estado character varying(20) NOT NULL,
    observacion text,
    fecha_registro date DEFAULT CURRENT_DATE NOT NULL,
    fecha_validacion date,
    id_administrador integer,
    id_postulante integer,
    CONSTRAINT check_doc_estado CHECK (((estado)::text = ANY ((ARRAY['Validado'::character varying, 'Rechazado'::character varying, 'Pendiente'::character varying])::text[])))
);


ALTER TABLE public.documento OWNER TO postgres;

--
-- Name: documento_id_documento_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.documento_id_documento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.documento_id_documento_seq OWNER TO postgres;

--
-- Name: documento_id_documento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.documento_id_documento_seq OWNED BY public.documento.id_documento;


--
-- Name: especialidad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.especialidad (
    id_especialidad integer NOT NULL,
    nombre_especialidad character varying(150) NOT NULL,
    descripcion text
);


ALTER TABLE public.especialidad OWNER TO postgres;

--
-- Name: especialidad_id_especialidad_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.especialidad_id_especialidad_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.especialidad_id_especialidad_seq OWNER TO postgres;

--
-- Name: especialidad_id_especialidad_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.especialidad_id_especialidad_seq OWNED BY public.especialidad.id_especialidad;


--
-- Name: evaluacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.evaluacion (
    id_evaluacion integer NOT NULL,
    numero_evaluacion integer NOT NULL,
    porcentaje numeric(5,2) NOT NULL,
    fecha date NOT NULL,
    estado character varying(20) NOT NULL,
    id_grupo integer NOT NULL,
    id_materia integer,
    CONSTRAINT check_evaluacion_estado CHECK (((estado)::text = ANY ((ARRAY['Activo'::character varying, 'Inactivo'::character varying])::text[]))),
    CONSTRAINT check_evaluacion_porcentaje CHECK (((porcentaje > (0)::numeric) AND (porcentaje <= (100)::numeric)))
);


ALTER TABLE public.evaluacion OWNER TO postgres;

--
-- Name: evaluacion_id_evaluacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.evaluacion_id_evaluacion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.evaluacion_id_evaluacion_seq OWNER TO postgres;

--
-- Name: evaluacion_id_evaluacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.evaluacion_id_evaluacion_seq OWNED BY public.evaluacion.id_evaluacion;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: gestion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.gestion (
    id_gestion integer NOT NULL,
    anio character varying(20) NOT NULL,
    periodo character varying(50) NOT NULL,
    fecha_inicio date,
    fecha_fin date
);


ALTER TABLE public.gestion OWNER TO postgres;

--
-- Name: gestion_id_gestion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.gestion_id_gestion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.gestion_id_gestion_seq OWNER TO postgres;

--
-- Name: gestion_id_gestion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.gestion_id_gestion_seq OWNED BY public.gestion.id_gestion;


--
-- Name: grupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.grupo (
    id_grupo integer NOT NULL,
    sigla_grupo character varying(20) NOT NULL,
    capacidad_max integer NOT NULL,
    estado character varying(20) NOT NULL,
    cant_estudiantes integer DEFAULT 0 NOT NULL,
    id_aula integer NOT NULL,
    id_modalidad integer NOT NULL,
    id_turno integer NOT NULL,
    id_docente integer NOT NULL,
    id_gestion integer NOT NULL,
    CONSTRAINT check_capacidad CHECK ((cant_estudiantes <= capacidad_max)),
    CONSTRAINT check_grupo_estado CHECK (((estado)::text = ANY ((ARRAY['Activo'::character varying, 'Inactivo'::character varying])::text[])))
);


ALTER TABLE public.grupo OWNER TO postgres;

--
-- Name: grupo_horario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.grupo_horario (
    id_grupo integer NOT NULL,
    id_horario integer NOT NULL
);


ALTER TABLE public.grupo_horario OWNER TO postgres;

--
-- Name: grupo_id_grupo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.grupo_id_grupo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.grupo_id_grupo_seq OWNER TO postgres;

--
-- Name: grupo_id_grupo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.grupo_id_grupo_seq OWNED BY public.grupo.id_grupo;


--
-- Name: grupo_materia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.grupo_materia (
    id_grupo integer NOT NULL,
    id_materia integer NOT NULL,
    id_docente integer NOT NULL
);


ALTER TABLE public.grupo_materia OWNER TO postgres;

--
-- Name: grupo_postulante; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.grupo_postulante (
    id_grupo integer NOT NULL,
    id_postulante integer NOT NULL,
    estado character varying(20) NOT NULL,
    fecha_asignacion date DEFAULT CURRENT_DATE NOT NULL
);


ALTER TABLE public.grupo_postulante OWNER TO postgres;

--
-- Name: horario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.horario (
    id_horario integer NOT NULL,
    dia_semana character varying(20),
    hora_inicio time without time zone,
    hora_fin time without time zone
);


ALTER TABLE public.horario OWNER TO postgres;

--
-- Name: horario_id_horario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.horario_id_horario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.horario_id_horario_seq OWNER TO postgres;

--
-- Name: horario_id_horario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.horario_id_horario_seq OWNED BY public.horario.id_horario;


--
-- Name: inscripcion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.inscripcion (
    id_inscripcion integer NOT NULL,
    codigo_inscripcion character varying(50) NOT NULL,
    estado character varying(20) NOT NULL,
    fecha_inscripcion date DEFAULT CURRENT_DATE NOT NULL,
    id_postulante integer NOT NULL
);


ALTER TABLE public.inscripcion OWNER TO postgres;

--
-- Name: inscripcion_carrera; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.inscripcion_carrera (
    id_inscripcion integer NOT NULL,
    id_carrera integer NOT NULL,
    prioridad character varying(50) NOT NULL,
    estado character varying(20) NOT NULL,
    CONSTRAINT check_inscripcion_carrera_estado CHECK (((estado)::text = ANY ((ARRAY['Activo'::character varying, 'Inactivo'::character varying])::text[])))
);


ALTER TABLE public.inscripcion_carrera OWNER TO postgres;

--
-- Name: inscripcion_id_inscripcion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.inscripcion_id_inscripcion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.inscripcion_id_inscripcion_seq OWNER TO postgres;

--
-- Name: inscripcion_id_inscripcion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.inscripcion_id_inscripcion_seq OWNED BY public.inscripcion.id_inscripcion;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: materia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.materia (
    id_materia integer NOT NULL,
    nombre_materia character varying(150) NOT NULL,
    codigo_materia character varying(50),
    creditos integer
);


ALTER TABLE public.materia OWNER TO postgres;

--
-- Name: materia_id_materia_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.materia_id_materia_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.materia_id_materia_seq OWNER TO postgres;

--
-- Name: materia_id_materia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.materia_id_materia_seq OWNED BY public.materia.id_materia;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: modalidad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.modalidad (
    id_modalidad integer NOT NULL,
    nombre_modalidad character varying(100) NOT NULL,
    descripcion text
);


ALTER TABLE public.modalidad OWNER TO postgres;

--
-- Name: modalidad_id_modalidad_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.modalidad_id_modalidad_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.modalidad_id_modalidad_seq OWNER TO postgres;

--
-- Name: modalidad_id_modalidad_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.modalidad_id_modalidad_seq OWNED BY public.modalidad.id_modalidad;


--
-- Name: nota; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.nota (
    id_nota integer NOT NULL,
    nota numeric(5,2),
    estado_academico character varying(50),
    fecha date DEFAULT CURRENT_DATE NOT NULL,
    id_evaluacion integer NOT NULL,
    id_grupo integer NOT NULL,
    id_postulante integer
);


ALTER TABLE public.nota OWNER TO postgres;

--
-- Name: nota_id_nota_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.nota_id_nota_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.nota_id_nota_seq OWNER TO postgres;

--
-- Name: nota_id_nota_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.nota_id_nota_seq OWNED BY public.nota.id_nota;


--
-- Name: pago; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pago (
    id_pago integer NOT NULL,
    monto numeric(10,2) NOT NULL,
    fecha_pago date DEFAULT CURRENT_DATE NOT NULL,
    metodo_pago character varying(50),
    estado_pago character varying(20) NOT NULL,
    observaciones text,
    id_comprobante integer,
    id_inscripcion integer NOT NULL,
    CONSTRAINT check_pago_estado CHECK (((estado_pago)::text = ANY ((ARRAY['Pagado'::character varying, 'Rechazado'::character varying, 'Pendiente'::character varying])::text[]))),
    CONSTRAINT check_pago_monto CHECK ((monto > (0)::numeric))
);


ALTER TABLE public.pago OWNER TO postgres;

--
-- Name: pago_id_pago_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pago_id_pago_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pago_id_pago_seq OWNER TO postgres;

--
-- Name: pago_id_pago_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pago_id_pago_seq OWNED BY public.pago.id_pago;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: persona; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.persona (
    id_persona integer NOT NULL,
    ci character varying(20) NOT NULL,
    nombre character varying(100) NOT NULL,
    apellido character varying(100) NOT NULL,
    fecha_nacimiento date NOT NULL,
    telefono character varying(20),
    direccion text,
    correo character varying(150)
);


ALTER TABLE public.persona OWNER TO postgres;

--
-- Name: persona_id_persona_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.persona_id_persona_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.persona_id_persona_seq OWNER TO postgres;

--
-- Name: persona_id_persona_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.persona_id_persona_seq OWNED BY public.persona.id_persona;


--
-- Name: posts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.posts (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.posts OWNER TO postgres;

--
-- Name: posts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.posts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.posts_id_seq OWNER TO postgres;

--
-- Name: posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.posts_id_seq OWNED BY public.posts.id;


--
-- Name: postulante; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.postulante (
    id_postulante integer NOT NULL,
    estado_inscripcion character varying(20) NOT NULL,
    fecha_registro date DEFAULT CURRENT_DATE NOT NULL,
    id_asignacion integer
);


ALTER TABLE public.postulante OWNER TO postgres;

--
-- Name: reporte; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reporte (
    id_reporte integer NOT NULL,
    tipo_reporte character varying(50) NOT NULL,
    fecha_generacion date DEFAULT CURRENT_DATE NOT NULL,
    descripcion text,
    id_usuario integer NOT NULL
);


ALTER TABLE public.reporte OWNER TO postgres;

--
-- Name: reporte_id_reporte_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reporte_id_reporte_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reporte_id_reporte_seq OWNER TO postgres;

--
-- Name: reporte_id_reporte_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reporte_id_reporte_seq OWNED BY public.reporte.id_reporte;


--
-- Name: resultadoacademico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.resultadoacademico (
    id_resultado integer NOT NULL,
    promedio_final numeric(5,2),
    estado_final character varying(50),
    fecha_calculo date DEFAULT CURRENT_DATE NOT NULL,
    id_postulante integer NOT NULL
);


ALTER TABLE public.resultadoacademico OWNER TO postgres;

--
-- Name: resultadoacademico_id_resultado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.resultadoacademico_id_resultado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.resultadoacademico_id_resultado_seq OWNER TO postgres;

--
-- Name: resultadoacademico_id_resultado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.resultadoacademico_id_resultado_seq OWNED BY public.resultadoacademico.id_resultado;


--
-- Name: rol; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rol (
    id_rol integer NOT NULL,
    nombre_rol character varying(50) NOT NULL,
    descripcion text
);


ALTER TABLE public.rol OWNER TO postgres;

--
-- Name: rol_id_rol_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rol_id_rol_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.rol_id_rol_seq OWNER TO postgres;

--
-- Name: rol_id_rol_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rol_id_rol_seq OWNED BY public.rol.id_rol;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: superadministrador; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.superadministrador (
    id_superadministrador integer NOT NULL,
    cargo character varying(100),
    estado character varying(20) NOT NULL,
    CONSTRAINT check_superadmin_estado CHECK (((estado)::text = ANY ((ARRAY['Activo'::character varying, 'Inactivo'::character varying])::text[])))
);


ALTER TABLE public.superadministrador OWNER TO postgres;

--
-- Name: turno; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.turno (
    id_turno integer NOT NULL,
    nombre_turno character varying(50) NOT NULL,
    hora_inicio time without time zone,
    hora_fin time without time zone
);


ALTER TABLE public.turno OWNER TO postgres;

--
-- Name: turno_id_turno_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.turno_id_turno_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.turno_id_turno_seq OWNER TO postgres;

--
-- Name: turno_id_turno_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.turno_id_turno_seq OWNED BY public.turno.id_turno;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuario (
    id_usuario integer NOT NULL,
    nombre_usuario character varying(50) NOT NULL,
    correo character varying(150) NOT NULL,
    "contraseña" character varying(255) NOT NULL,
    estado character varying(20) NOT NULL,
    fecha_creacion date DEFAULT CURRENT_DATE NOT NULL,
    id_rol integer NOT NULL,
    id_persona integer NOT NULL,
    CONSTRAINT check_usuario_estado CHECK (((estado)::text = ANY ((ARRAY['Activo'::character varying, 'Inactivo'::character varying])::text[])))
);


ALTER TABLE public.usuario OWNER TO postgres;

--
-- Name: usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuario_id_usuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuario_id_usuario_seq OWNER TO postgres;

--
-- Name: usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuario_id_usuario_seq OWNED BY public.usuario.id_usuario;


--
-- Name: asignacioncupo id_asignacioncupo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asignacioncupo ALTER COLUMN id_asignacioncupo SET DEFAULT nextval('public.asignacioncupo_id_asignacioncupo_seq'::regclass);


--
-- Name: asistencia id_asistencia; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asistencia ALTER COLUMN id_asistencia SET DEFAULT nextval('public.asistencia_id_asistencia_seq'::regclass);


--
-- Name: aula id_aula; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aula ALTER COLUMN id_aula SET DEFAULT nextval('public.aula_id_aula_seq'::regclass);


--
-- Name: bitacora id_bitacora; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bitacora ALTER COLUMN id_bitacora SET DEFAULT nextval('public.bitacora_id_bitacora_seq'::regclass);


--
-- Name: carrera id_carrera; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carrera ALTER COLUMN id_carrera SET DEFAULT nextval('public.carrera_id_carrera_seq'::regclass);


--
-- Name: comprobante id_comprobante; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comprobante ALTER COLUMN id_comprobante SET DEFAULT nextval('public.comprobante_id_comprobante_seq'::regclass);


--
-- Name: cupocarrera id_cupo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cupocarrera ALTER COLUMN id_cupo SET DEFAULT nextval('public.cupocarrera_id_cupo_seq'::regclass);


--
-- Name: detalle_bitacora id_detallebitacora; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_bitacora ALTER COLUMN id_detallebitacora SET DEFAULT nextval('public.detalle_bitacora_id_detallebitacora_seq'::regclass);


--
-- Name: documento id_documento; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documento ALTER COLUMN id_documento SET DEFAULT nextval('public.documento_id_documento_seq'::regclass);


--
-- Name: especialidad id_especialidad; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.especialidad ALTER COLUMN id_especialidad SET DEFAULT nextval('public.especialidad_id_especialidad_seq'::regclass);


--
-- Name: evaluacion id_evaluacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.evaluacion ALTER COLUMN id_evaluacion SET DEFAULT nextval('public.evaluacion_id_evaluacion_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: gestion id_gestion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gestion ALTER COLUMN id_gestion SET DEFAULT nextval('public.gestion_id_gestion_seq'::regclass);


--
-- Name: grupo id_grupo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo ALTER COLUMN id_grupo SET DEFAULT nextval('public.grupo_id_grupo_seq'::regclass);


--
-- Name: horario id_horario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.horario ALTER COLUMN id_horario SET DEFAULT nextval('public.horario_id_horario_seq'::regclass);


--
-- Name: inscripcion id_inscripcion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inscripcion ALTER COLUMN id_inscripcion SET DEFAULT nextval('public.inscripcion_id_inscripcion_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: materia id_materia; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materia ALTER COLUMN id_materia SET DEFAULT nextval('public.materia_id_materia_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: modalidad id_modalidad; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modalidad ALTER COLUMN id_modalidad SET DEFAULT nextval('public.modalidad_id_modalidad_seq'::regclass);


--
-- Name: nota id_nota; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nota ALTER COLUMN id_nota SET DEFAULT nextval('public.nota_id_nota_seq'::regclass);


--
-- Name: pago id_pago; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago ALTER COLUMN id_pago SET DEFAULT nextval('public.pago_id_pago_seq'::regclass);


--
-- Name: persona id_persona; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.persona ALTER COLUMN id_persona SET DEFAULT nextval('public.persona_id_persona_seq'::regclass);


--
-- Name: posts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts ALTER COLUMN id SET DEFAULT nextval('public.posts_id_seq'::regclass);


--
-- Name: reporte id_reporte; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reporte ALTER COLUMN id_reporte SET DEFAULT nextval('public.reporte_id_reporte_seq'::regclass);


--
-- Name: resultadoacademico id_resultado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultadoacademico ALTER COLUMN id_resultado SET DEFAULT nextval('public.resultadoacademico_id_resultado_seq'::regclass);


--
-- Name: rol id_rol; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol ALTER COLUMN id_rol SET DEFAULT nextval('public.rol_id_rol_seq'::regclass);


--
-- Name: turno id_turno; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.turno ALTER COLUMN id_turno SET DEFAULT nextval('public.turno_id_turno_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: usuario id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.usuario_id_usuario_seq'::regclass);


--
-- Data for Name: administrador; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.administrador (id_administrador, cargo, area, estado) FROM stdin;
7	Encargado de DTIC	Contaduria	Activo
\.


--
-- Data for Name: asignacioncupo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asignacioncupo (id_asignacioncupo, fecha_asignacion, promedio_final, puesto_merito, estado_asignacion, id_carrera, id_gestion) FROM stdin;
\.


--
-- Data for Name: asistencia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asistencia (id_asistencia, fecha, hora, estado, observacion, id_materia, id_grupo, id_postulante) FROM stdin;
\.


--
-- Data for Name: aula; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.aula (id_aula, codigo_aula, capacidad, ubicacion) FROM stdin;
\.


--
-- Data for Name: bitacora; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.bitacora (id_bitacora, tipo, descripcion, fecha, hora, estado, id_usuario) FROM stdin;
1	AUTH	Inicio de sesión exitoso	2026-05-28	13:56:41	Activo	1
2	AUTH	Cierre de sesión	2026-05-28	14:28:46	Activo	1
3	AUTH	Recuperación de contraseña	2026-05-28	14:47:16	Activo	1
4	AUTH	Inicio de sesión exitoso	2026-05-28	14:47:44	Activo	1
5	AUTH	Cierre de sesión	2026-05-28	15:05:15	Activo	1
6	AUTH	Inicio de sesión exitoso	2026-05-28	15:07:30	Activo	1
7	MODIFICACION	Acción POST en la ruta: admin/usuarios	2026-05-28	15:40:30	Activo	1
8	CREATE	Usuario creado	2026-05-28	15:41:04	Activo	1
9	MODIFICACION	Acción POST en la ruta: admin/usuarios	2026-05-28	15:41:04	Activo	1
10	AUTH	Cierre de sesión	2026-05-28	15:41:19	Activo	1
11	AUTH	Inicio de sesión exitoso	2026-05-28	15:41:40	Activo	1
12	AUTH	Cierre de sesión	2026-05-28	15:59:52	Activo	1
13	AUTH	Inicio de sesión exitoso	2026-05-28	16:00:05	Activo	3
14	CREATE	Usuario creado	2026-05-28	16:02:13	Activo	3
15	MODIFICACION	Acción POST en la ruta: admin/usuarios	2026-05-28	16:02:13	Activo	3
16	AUTH	Cierre de sesión	2026-05-28	16:02:19	Activo	3
21	AUTH	Inicio de sesión exitoso	2026-05-28	16:03:39	Activo	1
22	AUTH	Cierre de sesión	2026-05-28	16:23:49	Activo	1
23	AUTH	Inicio de sesión exitoso	2026-05-28	16:24:29	Activo	3
24	CREATE	Usuario creado	2026-05-28	16:26:23	Activo	3
25	MODIFICACION	Acción POST en la ruta: admin/usuarios	2026-05-28	16:26:23	Activo	3
26	AUTH	Cierre de sesión	2026-05-28	16:26:30	Activo	3
29	AUTH	Inicio de sesión exitoso	2026-05-28	16:31:26	Activo	1
30	AUTH	Cierre de sesión	2026-05-28	16:31:36	Activo	1
33	AUTH	Inicio de sesión exitoso	2026-05-28	16:32:10	Activo	1
34	DELETE	Usuario eliminado	2026-05-28	18:51:04	Activo	1
35	MODIFICACION	Acción DELETE en la ruta: admin/usuarios/2	2026-05-28	18:51:04	Activo	1
36	UPDATE	Usuario editado	2026-05-28	18:57:03	Activo	1
37	MODIFICACION	Acción PUT en la ruta: admin/usuarios/1	2026-05-28	18:57:03	Activo	1
38	UPDATE	Usuario editado	2026-05-28	19:04:14	Activo	1
39	MODIFICACION	Acción PUT en la ruta: admin/usuarios/4	2026-05-28	19:04:14	Activo	1
40	UPDATE	Usuario editado	2026-05-28	19:22:14	Activo	1
41	MODIFICACION	Acción PUT en la ruta: admin/usuarios/1	2026-05-28	19:22:14	Activo	1
42	PROCESS	Inscripción validada administrativamente	2026-05-28	21:46:13	Activo	1
43	MODIFICACION	Acción POST en la ruta: admin/inscripciones/4/validar	2026-05-28	21:46:13	Activo	1
44	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/4	2026-05-28	21:48:07	Activo	1
45	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/4	2026-05-28	21:48:10	Activo	1
46	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/4	2026-05-28	21:48:14	Activo	1
47	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/3	2026-05-28	21:49:25	Activo	1
48	PROCESS	Inscripción validada administrativamente	2026-05-28	21:49:42	Activo	1
49	MODIFICACION	Acción POST en la ruta: admin/inscripciones/3/validar	2026-05-28	21:49:42	Activo	1
50	AUTH	Cierre de sesión	2026-05-28	21:50:14	Activo	1
53	AUTH	Inicio de sesión exitoso	2026-05-28	21:50:48	Activo	1
54	AUTH	Cierre de sesión	2026-05-28	21:50:55	Activo	1
55	AUTH	Inicio de sesión exitoso	2026-05-28	21:52:38	Activo	1
56	AUTH	Cierre de sesión	2026-05-28	21:57:40	Activo	1
57	AUTH	Recuperación de contraseña	2026-05-28	22:15:19	Activo	1
58	AUTH	Inicio de sesión exitoso	2026-05-28	22:16:47	Activo	1
59	AUTH	Cierre de sesión	2026-05-28	22:19:57	Activo	1
60	AUTH	Recuperación de contraseña	2026-05-28	22:33:29	Activo	1
61	AUTH	Inicio de sesión exitoso	2026-05-28	22:33:47	Activo	1
62	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/4	2026-05-28	22:39:46	Activo	1
63	AUTH	Cierre de sesión	2026-05-28	22:41:53	Activo	1
64	AUTH	Inicio de sesión exitoso	2026-05-28	22:42:05	Activo	1
65	AUTH	Cierre de sesión	2026-05-28	22:42:12	Activo	1
66	AUTH	Recuperación de contraseña	2026-05-28	22:51:53	Activo	1
67	AUTH	Inicio de sesión exitoso	2026-05-28	22:52:12	Activo	1
68	AUTH	Cierre de sesión	2026-05-28	22:53:26	Activo	1
69	AUTH	Inicio de sesión exitoso	2026-05-28	22:55:11	Activo	1
70	DELETE	Usuario eliminado	2026-05-28	22:55:23	Activo	1
71	MODIFICACION	Acción DELETE en la ruta: admin/usuarios/4	2026-05-28	22:55:23	Activo	1
72	CREATE	Usuario creado	2026-05-28	22:56:43	Activo	1
73	MODIFICACION	Acción POST en la ruta: admin/usuarios	2026-05-28	22:56:43	Activo	1
74	AUTH	Cierre de sesión	2026-05-28	22:56:52	Activo	1
75	AUTH	Inicio de sesión exitoso	2026-05-28	22:56:59	Activo	6
76	AUTH	Cierre de sesión	2026-05-28	22:59:20	Activo	6
77	AUTH	Inicio de sesión exitoso	2026-05-28	22:59:58	Activo	1
78	AUTH	Cierre de sesión	2026-05-28	23:02:06	Activo	1
81	AUTH	Inicio de sesión exitoso	2026-05-28	23:09:10	Activo	1
82	MODIFICACION	Acción POST en la ruta: admin/gestionar-documentos	2026-05-29	00:15:51	Activo	1
83	MODIFICACION	Acción POST en la ruta: admin/gestionar-documentos	2026-05-29	00:15:56	Activo	1
84	MODIFICACION	Acción POST en la ruta: admin/gestionar-documentos	2026-05-29	00:29:25	Activo	1
85	CREATE	Documento requisito creado	2026-05-29	00:29:46	Activo	1
86	MODIFICACION	Acción POST en la ruta: admin/gestionar-documentos	2026-05-29	00:29:46	Activo	1
87	DELETE	Documento requisito eliminado	2026-05-29	00:31:32	Activo	1
88	MODIFICACION	Acción DELETE en la ruta: admin/gestionar-documentos/9	2026-05-29	00:31:32	Activo	1
89	CREATE	Documento requisito creado	2026-05-29	00:32:41	Activo	1
90	MODIFICACION	Acción POST en la ruta: admin/gestionar-documentos	2026-05-29	00:32:41	Activo	1
91	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/3	2026-05-29	00:48:47	Activo	1
92	PROCESS	Estado de documento actualizado	2026-05-29	00:49:40	Activo	1
93	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/3	2026-05-29	00:49:40	Activo	1
94	CREATE	Documento requisito creado	2026-05-29	00:51:48	Activo	1
95	MODIFICACION	Acción POST en la ruta: admin/gestionar-documentos	2026-05-29	00:51:48	Activo	1
96	PROCESS	Estado de documento actualizado	2026-05-29	00:52:16	Activo	1
97	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/4	2026-05-29	00:52:16	Activo	1
98	PROCESS	Estado de documento actualizado	2026-05-29	00:52:26	Activo	1
99	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/4	2026-05-29	00:52:26	Activo	1
100	PROCESS	Estado de documento actualizado	2026-05-29	00:58:15	Activo	1
101	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/4	2026-05-29	00:58:15	Activo	1
102	PROCESS	Estado de documento actualizado	2026-05-29	00:58:26	Activo	1
103	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/4	2026-05-29	00:58:26	Activo	1
104	PROCESS	Estado de documento actualizado	2026-05-29	00:58:37	Activo	1
105	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/4	2026-05-29	00:58:37	Activo	1
106	AUTH	Cierre de sesión	2026-05-29	02:55:09	Activo	1
107	AUTH	Recuperación de contraseña	2026-05-29	03:00:55	Activo	1
108	AUTH	Inicio de sesión exitoso	2026-05-29	03:01:45	Activo	1
109	MODIFICACION	Acción POST en la ruta: admin/usuarios	2026-05-29	03:08:17	Activo	1
110	CREATE	Usuario creado	2026-05-29	03:09:04	Activo	1
111	MODIFICACION	Acción POST en la ruta: admin/usuarios	2026-05-29	03:09:04	Activo	1
112	DELETE	Usuario eliminado	2026-05-29	03:09:52	Activo	1
113	MODIFICACION	Acción DELETE en la ruta: admin/usuarios/5	2026-05-29	03:09:52	Activo	1
114	UPDATE	Usuario editado	2026-05-29	03:11:35	Activo	1
115	MODIFICACION	Acción PUT en la ruta: admin/usuarios/1	2026-05-29	03:11:35	Activo	1
116	UPDATE	Usuario editado	2026-05-29	03:12:34	Activo	1
117	MODIFICACION	Acción PUT en la ruta: admin/usuarios/3	2026-05-29	03:12:34	Activo	1
118	PROCESS	Estado de documento actualizado	2026-05-29	03:17:40	Activo	1
119	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/3	2026-05-29	03:17:40	Activo	1
120	PROCESS	Estado de documento actualizado	2026-05-29	03:20:00	Activo	1
121	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/3	2026-05-29	03:20:00	Activo	1
122	PROCESS	Estado de documento actualizado	2026-05-29	03:20:02	Activo	1
123	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/3	2026-05-29	03:20:02	Activo	1
124	PROCESS	Estado de documento actualizado	2026-05-29	03:20:13	Activo	1
125	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/3	2026-05-29	03:20:14	Activo	1
126	CREATE	Documento requisito creado	2026-05-29	03:22:50	Activo	1
127	MODIFICACION	Acción POST en la ruta: admin/gestionar-documentos	2026-05-29	03:22:50	Activo	1
128	PROCESS	Estado de documento actualizado	2026-05-29	03:24:13	Activo	1
129	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/3	2026-05-29	03:24:13	Activo	1
130	PROCESS	Estado de documento actualizado	2026-05-29	03:24:15	Activo	1
131	MODIFICACION	Acción POST en la ruta: admin/inscripciones/documento/3	2026-05-29	03:24:15	Activo	1
132	AUTH	Cierre de sesión	2026-05-29	03:30:52	Activo	1
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
laravel-cache-jorgealanoca@gmail.com|127.0.0.1:timer	i:1780009246;	1780009246
laravel-cache-jorgealanoca@gmail.com|127.0.0.1	i:1;	1780009246
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: carrera; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carrera (id_carrera, nombre_carrera, descripcion, duracion_anios) FROM stdin;
\.


--
-- Data for Name: comprobante; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comprobante (id_comprobante, tipo_comprobante, numero_comprobante, fecha_emision) FROM stdin;
\.


--
-- Data for Name: cupocarrera; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cupocarrera (id_cupo, gestion, cantidad_cupos, cupos_ocupados, cupos_disponibles, id_gestion, id_carrera) FROM stdin;
\.


--
-- Data for Name: detalle_bitacora; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalle_bitacora (id_detallebitacora, id_bitacora, direccion_ip, hora_inicio, hora_fin, accion) FROM stdin;
1	1	127.0.0.1	13:56:41	13:56:41	Usuario: jorge
2	2	127.0.0.1	14:28:46	14:28:46	Usuario: jorge
3	3	127.0.0.1	14:47:16	14:47:16	Usuario: jorge
4	4	127.0.0.1	14:47:44	14:47:44	Usuario: jorge
5	5	127.0.0.1	15:05:15	15:05:15	Usuario: jorge
6	6	127.0.0.1	15:07:30	15:07:30	Usuario: jorge
7	7	127.0.0.1	15:40:30	15:40:30	{"ci":"1234","id_rol":"4","nombre":"Ana","apellido":"Oliveira","fecha_nacimiento":"2005-10-29","telefono":"99999999","correo":"anamaria@gmail.com","direccion":"Calle La Paz","nombre_usuario":"Ana","cargo":null,"area":null}
8	8	127.0.0.1	15:41:04	15:41:04	Usuario: Ana
9	9	127.0.0.1	15:41:04	15:41:04	{"ci":"1234","id_rol":"4","nombre":"Ana","apellido":"Oliveira","fecha_nacimiento":"2005-10-29","telefono":"99999999","correo":"anamaria@gmail.com","direccion":"Calle La Paz","nombre_usuario":"Ana","cargo":null,"area":null}
10	10	127.0.0.1	15:41:20	15:41:20	Usuario: jorge
11	11	127.0.0.1	15:41:40	15:41:40	Usuario: jorge
12	12	127.0.0.1	15:59:52	15:59:52	Usuario: jorge
13	13	127.0.0.1	16:00:05	16:00:05	Usuario: Ana
14	14	127.0.0.1	16:02:13	16:02:13	Usuario: paolalimon@gmail.com
15	15	127.0.0.1	16:02:13	16:02:13	{"ci":"12345","id_rol":"3","nombre":"Paola","apellido":"Limon","fecha_nacimiento":"2026-05-16","telefono":"63317491","correo":"paolalimon@gmail.com","direccion":"B\\/San Juan","nombre_usuario":"paolalimon@gmail.com","cargo":null,"area":null}
16	16	127.0.0.1	16:02:19	16:02:19	Usuario: Ana
21	21	127.0.0.1	16:03:39	16:03:39	Usuario: jorge
22	22	127.0.0.1	16:23:49	16:23:49	Usuario: jorge
23	23	127.0.0.1	16:24:29	16:24:29	Usuario: Ana
24	24	127.0.0.1	16:26:23	16:26:23	Usuario: Oscar
25	25	127.0.0.1	16:26:23	16:26:23	{"ci":"2314","id_rol":"4","nombre":"Diego","apellido":"Arancibia","fecha_nacimiento":"2026-05-02","telefono":"655566","correo":"oscararancibia@gmail.com","direccion":"Calle La Paz","nombre_usuario":"Oscar","cargo":null,"area":null}
26	26	127.0.0.1	16:26:30	16:26:30	Usuario: Ana
29	29	127.0.0.1	16:31:26	16:31:26	Usuario: jorge
30	30	127.0.0.1	16:31:36	16:31:36	Usuario: jorge
33	33	127.0.0.1	16:32:10	16:32:10	Usuario: jorge
34	34	127.0.0.1	18:51:04	18:51:04	Usuario: admin
35	35	127.0.0.1	18:51:04	18:51:04	[]
36	36	127.0.0.1	18:57:03	18:57:03	Usuario: jorge con rol SuperAdministrador
37	37	127.0.0.1	18:57:03	18:57:03	{"ci":"9694251","id_rol":"1","nombre":"jorge","apellido":"Alanoca Oliveira","fecha_nacimiento":"2005-04-23","telefono":"67838705","correo":"jorgealanoca2005@gmail.com","direccion":"Av. Libertador 123","nombre_usuario":"jorge","cargo":null,"area":null}
38	38	127.0.0.1	19:04:14	19:04:14	Usuario: paolalimon@gmail.com con rol Docente
39	39	127.0.0.1	19:04:14	19:04:14	{"ci":"12345","id_rol":"3","nombre":"Paola","apellido":"Limon Perez","fecha_nacimiento":"2026-05-16","telefono":"63317491","correo":"paolalimon@gmail.com","nombre_usuario":"paolalimon@gmail.com"}
40	40	127.0.0.1	19:22:14	19:22:14	Usuario: jorge con rol SuperAdministrador
41	41	127.0.0.1	19:22:14	19:22:14	{"ci":"9694251","id_rol":"1","nombre":"Jorge","apellido":"Alanoca Oliveira","fecha_nacimiento":"2005-04-23","telefono":"67838705","correo":"jorgealanoca2005@gmail.com","direccion":"Av. Libertador 123","nombre_usuario":"jorge","cargo":null,"area":null}
42	42	127.0.0.1	21:46:13	21:46:13	Inscripción ID: 4
43	43	127.0.0.1	21:46:13	21:46:13	[]
44	44	127.0.0.1	21:48:07	21:48:07	{"id_postulante":"5","documento_nombre":"Currículum Vitae","val_estado_display":"Aprobado","estado":"Aprobado","observacion":null}
45	45	127.0.0.1	21:48:11	21:48:11	{"id_postulante":"5","documento_nombre":"Currículum Vitae","val_estado_display":"Aprobado","estado":"Aprobado","observacion":null}
46	46	127.0.0.1	21:48:14	21:48:14	{"id_postulante":"5","documento_nombre":"Currículum Vitae","val_estado_display":"Aprobado","estado":"Aprobado","observacion":null}
47	47	127.0.0.1	21:49:25	21:49:25	{"id_postulante":"3","documento_nombre":"Cédula de Identidad","val_estado_display":"Aprobado","estado":"Aprobado","observacion":null}
48	48	127.0.0.1	21:49:42	21:49:42	Inscripción ID: 3
49	49	127.0.0.1	21:49:42	21:49:42	[]
50	50	127.0.0.1	21:50:14	21:50:14	Usuario: jorge
53	53	127.0.0.1	21:50:48	21:50:48	Usuario: jorge
54	54	127.0.0.1	21:50:55	21:50:55	Usuario: jorge
55	55	127.0.0.1	21:52:38	21:52:38	Usuario: jorge
56	56	127.0.0.1	21:57:40	21:57:40	Usuario: jorge
57	57	127.0.0.1	22:15:19	22:15:19	Usuario: jorge
58	58	127.0.0.1	22:16:47	22:16:47	Usuario: jorge
59	59	127.0.0.1	22:19:57	22:19:57	Usuario: jorge
60	60	127.0.0.1	22:33:29	22:33:29	Usuario: jorge
61	61	127.0.0.1	22:33:47	22:33:47	Usuario: jorge
62	62	127.0.0.1	22:39:46	22:39:46	{"id_postulante":"5","documento_nombre":"Cédula de Identidad","val_estado_display":"Aprobado","estado":"Aprobado","observacion":null}
63	63	127.0.0.1	22:41:53	22:41:53	Usuario: jorge
64	64	127.0.0.1	22:42:05	22:42:05	Usuario: jorge
65	65	127.0.0.1	22:42:12	22:42:12	Usuario: jorge
66	66	127.0.0.1	22:51:53	22:51:53	Usuario: jorge
67	67	127.0.0.1	22:52:12	22:52:12	Usuario: jorge
68	68	127.0.0.1	22:53:26	22:53:26	Usuario: jorge
69	69	127.0.0.1	22:55:11	22:55:11	Usuario: jorge
70	70	127.0.0.1	22:55:23	22:55:23	Usuario: paolalimon@gmail.com
71	71	127.0.0.1	22:55:23	22:55:23	[]
72	72	127.0.0.1	22:56:43	22:56:43	Usuario: paolalimon@gmail.com con rol Docente
73	73	127.0.0.1	22:56:43	22:56:43	{"ci":"55555","id_rol":"3","nombre":"Paola","apellido":"Limon","fecha_nacimiento":"5555-05-04","telefono":"5255552","correo":"paolalimon@gmail.com","nombre_usuario":"paolalimon@gmail.com"}
74	74	127.0.0.1	22:56:52	22:56:52	Usuario: jorge
75	75	127.0.0.1	22:56:59	22:56:59	Usuario: paolalimon@gmail.com
76	76	127.0.0.1	22:59:20	22:59:20	Usuario: paolalimon@gmail.com
77	77	127.0.0.1	22:59:58	22:59:58	Usuario: jorge
78	78	127.0.0.1	23:02:06	23:02:06	Usuario: jorge
81	81	127.0.0.1	23:09:10	23:09:10	Usuario: jorge
82	82	127.0.0.1	00:15:51	00:15:51	{"tipo_documento":"Obligatorio","estado":"Validado","nombre":"Fotocopia de carnet","observacion":"Que no este vencido","fecha_registro":"2026-05-29","fecha_validacion":"2025-05-28"}
83	83	127.0.0.1	00:15:56	00:15:56	{"tipo_documento":"Obligatorio","estado":"Validado","nombre":"Fotocopia de carnet","observacion":"Que no este vencido","fecha_registro":"2026-05-29","fecha_validacion":"2025-05-28"}
84	84	127.0.0.1	00:29:25	00:29:25	{"tipo_documento":"Obligatorio","estado":"Validado","nombre":"Fotocopia de carnet","observacion":"Que no este vencido","fecha_registro":"2026-05-29","fecha_validacion":"2025-05-28"}
85	85	127.0.0.1	00:29:46	00:29:46	Documento: Fotocopia de carnet (Tipo: Obligatorio)
86	86	127.0.0.1	00:29:46	00:29:46	{"tipo_documento":"Obligatorio","estado":"Validado","nombre":"Fotocopia de carnet","observacion":"Que no este vencido","fecha_registro":"2026-05-29","fecha_validacion":"2025-05-28"}
87	87	127.0.0.1	00:31:32	00:31:32	Documento: Fotocopia de carnet
88	88	127.0.0.1	00:31:32	00:31:32	[]
89	89	127.0.0.1	00:32:41	00:32:41	Documento: Certificado de Bachiller (Tipo: Obligatorio)
90	90	127.0.0.1	00:32:41	00:32:41	{"tipo_documento":"Obligatorio","estado":"Validado","nombre":"Certificado de Bachiller","observacion":"Que este validado en el sistema eduacativo","fecha_registro":"2026-05-29","fecha_validacion":"2026-05-29"}
91	91	127.0.0.1	00:48:47	00:48:47	{"id_postulante":"3","documento_nombre":"Certificado de Bachiller","estado":"En revisión","observacion":null}
92	92	127.0.0.1	00:49:40	00:49:40	Doc ID 12 -> Validado
93	93	127.0.0.1	00:49:40	00:49:40	{"id_postulante":"3","documento_nombre":"Certificado de Bachiller","estado":"Validado","observacion":null}
94	94	127.0.0.1	00:51:48	00:51:48	Documento: CI (Tipo: Obligatorio)
95	95	127.0.0.1	00:51:48	00:51:48	{"tipo_documento":"Obligatorio","estado":"Validado","nombre":"CI","observacion":"No vencido","fecha_registro":"2026-05-29","fecha_validacion":"2026-05-29"}
96	96	127.0.0.1	00:52:16	00:52:16	Doc ID 14 -> Validado
97	97	127.0.0.1	00:52:16	00:52:16	{"id_postulante":"5","documento_nombre":"Certificado de Bachiller","estado":"Validado","observacion":null}
98	98	127.0.0.1	00:52:26	00:52:26	Doc ID 15 -> Validado
99	99	127.0.0.1	00:52:26	00:52:26	{"id_postulante":"5","documento_nombre":"CI","estado":"Validado","observacion":null}
100	100	127.0.0.1	00:58:15	00:58:15	Doc ID 14 -> Pendiente
101	101	127.0.0.1	00:58:15	00:58:15	{"id_postulante":"5","documento_nombre":"Certificado de Bachiller","estado":"Pendiente","observacion":null}
102	102	127.0.0.1	00:58:26	00:58:26	Doc ID 14 -> Rechazado
103	103	127.0.0.1	00:58:26	00:58:26	{"id_postulante":"5","documento_nombre":"Certificado de Bachiller","estado":"Rechazado","observacion":null}
104	104	127.0.0.1	00:58:37	00:58:37	Doc ID 14 -> Validado
105	105	127.0.0.1	00:58:37	00:58:37	{"id_postulante":"5","documento_nombre":"Certificado de Bachiller","estado":"Validado","observacion":null}
106	106	127.0.0.1	02:55:09	02:55:09	Usuario: jorge
107	107	127.0.0.1	03:00:55	03:00:55	Usuario: jorge
108	108	127.0.0.1	03:01:45	03:01:45	Usuario: jorge
109	109	127.0.0.1	03:08:17	03:08:17	{"ci":"14389163","id_rol":"1","nombre":"Oscar","apellido":"Merlos Arancibia","fecha_nacimiento":"2004-08-26","telefono":"69055973","correo":"arancibiaoscar08@gmail.com","direccion":"C\\/123","nombre_usuario":"Oscar","cargo":"Encargado de DTIC","area":"Contaduria"}
110	110	127.0.0.1	03:09:04	03:09:04	Usuario: arancibiaoscar08@gmail.com con rol Admin
111	111	127.0.0.1	03:09:04	03:09:04	{"ci":"14389163","id_rol":"2","nombre":"Oscar","apellido":"Merlos Arancibia","fecha_nacimiento":"2004-08-26","telefono":"69055973","correo":"arancibiaoscar08@gmail.com","direccion":"C\\/123","nombre_usuario":"arancibiaoscar08@gmail.com","cargo":"Encargado de DTIC","area":"Contaduria"}
112	112	127.0.0.1	03:09:52	03:09:52	Usuario: Oscar
113	113	127.0.0.1	03:09:52	03:09:52	[]
114	114	127.0.0.1	03:11:35	03:11:35	Usuario: jorge con rol SuperAdministrador
115	115	127.0.0.1	03:11:35	03:11:35	{"ci":"9694251","id_rol":"1","nombre":"Jorge","apellido":"Alanoca Oliveira","fecha_nacimiento":"2005-04-23","telefono":"67838705","correo":"jorgealanoca2005@gmail.com","direccion":"Av. Libertador 123","nombre_usuario":"jorge","cargo":"Encargado de DTIC","area":"Finanza"}
116	116	127.0.0.1	03:12:34	03:12:34	Usuario: anamaria@gmail.com con rol Postulante
117	117	127.0.0.1	03:12:34	03:12:34	{"ci":"1234","id_rol":"4","nombre":"Ana","apellido":"Oliveira","fecha_nacimiento":"2005-10-29","correo":"anamaria@gmail.com","nombre_usuario":"anamaria@gmail.com"}
118	118	127.0.0.1	03:17:40	03:17:40	Doc ID 16 -> Validado
119	119	127.0.0.1	03:17:40	03:17:40	{"id_postulante":"3","documento_nombre":"CI","estado":"Validado","observacion":null}
120	120	127.0.0.1	03:20:00	03:20:00	Doc ID 12 -> Rechazado
121	121	127.0.0.1	03:20:00	03:20:00	{"id_postulante":"3","documento_nombre":"Certificado de Bachiller","estado":"Rechazado","observacion":null}
122	122	127.0.0.1	03:20:02	03:20:02	Doc ID 12 -> Rechazado
123	123	127.0.0.1	03:20:02	03:20:02	{"id_postulante":"3","documento_nombre":"Certificado de Bachiller","estado":"Rechazado","observacion":null}
124	124	127.0.0.1	03:20:13	03:20:13	Doc ID 12 -> Validado
125	125	127.0.0.1	03:20:14	03:20:14	{"id_postulante":"3","documento_nombre":"Certificado de Bachiller","estado":"Validado","observacion":null}
126	126	127.0.0.1	03:22:50	03:22:50	Documento: Certificado de Nacimiento (Tipo: Obligatorio)
127	127	127.0.0.1	03:22:50	03:22:50	{"tipo_documento":"Obligatorio","estado":"Validado","nombre":"Certificado de Nacimiento","observacion":"NULL","fecha_registro":"2026-05-29","fecha_validacion":"2026-05-28"}
128	128	127.0.0.1	03:24:13	03:24:13	Doc ID 18 -> Validado
129	129	127.0.0.1	03:24:13	03:24:13	{"id_postulante":"3","documento_nombre":"Certificado de Nacimiento","estado":"Validado","observacion":null}
130	130	127.0.0.1	03:24:15	03:24:15	Doc ID 18 -> Validado
131	131	127.0.0.1	03:24:15	03:24:15	{"id_postulante":"3","documento_nombre":"Certificado de Nacimiento","estado":"Validado","observacion":null}
132	132	127.0.0.1	03:30:52	03:30:52	Usuario: jorge
\.


--
-- Data for Name: docente; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.docente (id_docente, anio_servicio, estado) FROM stdin;
6	1	Activo
\.


--
-- Data for Name: docente_especialidad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.docente_especialidad (id_docente, id_especialidad) FROM stdin;
\.


--
-- Data for Name: documento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.documento (id_documento, tipo_documento, nombre, estado, observacion, fecha_registro, fecha_validacion, id_administrador, id_postulante) FROM stdin;
10	Obligatorio	Certificado de Bachiller	Validado	Que este validado en el sistema eduacativo	2026-05-29	2026-05-29	\N	\N
13	Obligatorio	CI	Validado	No vencido	2026-05-29	2026-05-29	\N	\N
16	CI	Documento_CI.pdf	Validado	\N	2026-05-29	2026-05-29	7	3
12	Certificado de Bachiller	Documento_Certificado_de_Bachiller.pdf	Validado	\N	2026-05-29	2026-05-29	7	3
17	Obligatorio	Certificado de Nacimiento	Validado	NULL	2026-05-29	2026-05-28	7	\N
18	Certificado de Nacimiento	Documento_Certificado_de_Nacimiento.pdf	Validado	\N	2026-05-29	2026-05-29	7	3
\.


--
-- Data for Name: especialidad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.especialidad (id_especialidad, nombre_especialidad, descripcion) FROM stdin;
\.


--
-- Data for Name: evaluacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.evaluacion (id_evaluacion, numero_evaluacion, porcentaje, fecha, estado, id_grupo, id_materia) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: gestion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gestion (id_gestion, anio, periodo, fecha_inicio, fecha_fin) FROM stdin;
\.


--
-- Data for Name: grupo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.grupo (id_grupo, sigla_grupo, capacidad_max, estado, cant_estudiantes, id_aula, id_modalidad, id_turno, id_docente, id_gestion) FROM stdin;
\.


--
-- Data for Name: grupo_horario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.grupo_horario (id_grupo, id_horario) FROM stdin;
\.


--
-- Data for Name: grupo_materia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.grupo_materia (id_grupo, id_materia, id_docente) FROM stdin;
\.


--
-- Data for Name: grupo_postulante; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.grupo_postulante (id_grupo, id_postulante, estado, fecha_asignacion) FROM stdin;
\.


--
-- Data for Name: horario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.horario (id_horario, dia_semana, hora_inicio, hora_fin) FROM stdin;
\.


--
-- Data for Name: inscripcion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inscripcion (id_inscripcion, codigo_inscripcion, estado, fecha_inscripcion, id_postulante) FROM stdin;
3	INS-6A186D8E79175	Validado	2026-05-28	3
\.


--
-- Data for Name: inscripcion_carrera; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inscripcion_carrera (id_inscripcion, id_carrera, prioridad, estado) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: materia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.materia (id_materia, nombre_materia, codigo_materia, creditos) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_05_28_034632_create_posts_table	1
\.


--
-- Data for Name: modalidad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.modalidad (id_modalidad, nombre_modalidad, descripcion) FROM stdin;
\.


--
-- Data for Name: nota; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.nota (id_nota, nota, estado_academico, fecha, id_evaluacion, id_grupo, id_postulante) FROM stdin;
\.


--
-- Data for Name: pago; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pago (id_pago, monto, fecha_pago, metodo_pago, estado_pago, observaciones, id_comprobante, id_inscripcion) FROM stdin;
3	250.00	2026-05-28	Transferencia Bancaria	Pagado	Pago validado por Administración	\N	3
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: persona; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona (id_persona, ci, nombre, apellido, fecha_nacimiento, telefono, direccion, correo) FROM stdin;
1	9694251	Jorge	Alanoca Oliveira	2005-04-23	67838705	Av. Libertador 123	jorgealanoca2005@gmail.com
6	55555	Paola	Limon	5555-05-04	5255552	\N	paolalimon@gmail.com
7	14389163	Oscar	Merlos Arancibia	2004-08-26	69055973	C/123	arancibiaoscar08@gmail.com
3	1234	Ana	Oliveira	2005-10-29	\N	\N	anamaria@gmail.com
\.


--
-- Data for Name: posts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.posts (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: postulante; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.postulante (id_postulante, estado_inscripcion, fecha_registro, id_asignacion) FROM stdin;
3	Pendiente	2026-05-28	\N
\.


--
-- Data for Name: reporte; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reporte (id_reporte, tipo_reporte, fecha_generacion, descripcion, id_usuario) FROM stdin;
\.


--
-- Data for Name: resultadoacademico; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.resultadoacademico (id_resultado, promedio_final, estado_final, fecha_calculo, id_postulante) FROM stdin;
\.


--
-- Data for Name: rol; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rol (id_rol, nombre_rol, descripcion) FROM stdin;
1	SuperAdministrador	Control total del sistema
2	Admin	Administrador del sistema
3	Docente	Docente de la institución
4	Postulante	Postulante al sistema
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
RBKslaCrgpLdxN2KaoFNQBdEGIrMnEkhBhVTjE57	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0	eyJfdG9rZW4iOiJOc3BvVm1aQXdSQkYxS2FZNVZrQVcyeGlHQkxTV0Y2dnBJOE5ZbU9mIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJyZWNvdmVyeV9lbWFpbCI6ImpvcmdlYWxhbm9jYTIwMDVAZ21haWwuY29tIn0=	1780025453
\.


--
-- Data for Name: superadministrador; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.superadministrador (id_superadministrador, cargo, estado) FROM stdin;
1	Encargado de DTIC	Activo
\.


--
-- Data for Name: turno; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.turno (id_turno, nombre_turno, hora_inicio, hora_fin) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuario (id_usuario, nombre_usuario, correo, "contraseña", estado, fecha_creacion, id_rol, id_persona) FROM stdin;
6	paolalimon@gmail.com	paolalimon@gmail.com	$2y$12$EYo8EcPmCbQpixZ8ItAMKOerAfgoHexlj/LikUVUyy5m5r5yTUiSa	Activo	2026-05-28	3	6
1	jorge	jorgealanoca2005@gmail.com	$2y$12$nlFVoxfAEtLdKqm2DJp4R.pmfFHqJ4m0Yy27C7o.fLCobnlvyOzIe	Activo	2024-01-01	1	1
7	arancibiaoscar08@gmail.com	arancibiaoscar08@gmail.com	$2y$12$GLW2Xps126YzBBfS6DoWbuao2EWMKy0XON0egheM6pKvctkb18tmO	Activo	2026-05-29	2	7
3	anamaria@gmail.com	anamaria@gmail.com	$2y$12$gR6L642mohJKvIz/FsWsxuwJ17VzXMqdM69YPi4/8ce9S79uCsCWC	Activo	2026-05-28	4	3
\.


--
-- Name: asignacioncupo_id_asignacioncupo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asignacioncupo_id_asignacioncupo_seq', 1, false);


--
-- Name: asistencia_id_asistencia_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asistencia_id_asistencia_seq', 1, false);


--
-- Name: aula_id_aula_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.aula_id_aula_seq', 1, false);


--
-- Name: bitacora_id_bitacora_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.bitacora_id_bitacora_seq', 132, true);


--
-- Name: carrera_id_carrera_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carrera_id_carrera_seq', 1, false);


--
-- Name: comprobante_id_comprobante_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.comprobante_id_comprobante_seq', 1, false);


--
-- Name: cupocarrera_id_cupo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cupocarrera_id_cupo_seq', 1, false);


--
-- Name: detalle_bitacora_id_detallebitacora_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.detalle_bitacora_id_detallebitacora_seq', 132, true);


--
-- Name: documento_id_documento_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.documento_id_documento_seq', 18, true);


--
-- Name: especialidad_id_especialidad_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.especialidad_id_especialidad_seq', 1, false);


--
-- Name: evaluacion_id_evaluacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.evaluacion_id_evaluacion_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: gestion_id_gestion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gestion_id_gestion_seq', 1, false);


--
-- Name: grupo_id_grupo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.grupo_id_grupo_seq', 1, false);


--
-- Name: horario_id_horario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.horario_id_horario_seq', 1, false);


--
-- Name: inscripcion_id_inscripcion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.inscripcion_id_inscripcion_seq', 4, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: materia_id_materia_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.materia_id_materia_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 4, true);


--
-- Name: modalidad_id_modalidad_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.modalidad_id_modalidad_seq', 1, false);


--
-- Name: nota_id_nota_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.nota_id_nota_seq', 1, false);


--
-- Name: pago_id_pago_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pago_id_pago_seq', 4, true);


--
-- Name: persona_id_persona_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.persona_id_persona_seq', 7, true);


--
-- Name: posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.posts_id_seq', 1, false);


--
-- Name: reporte_id_reporte_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reporte_id_reporte_seq', 1, false);


--
-- Name: resultadoacademico_id_resultado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.resultadoacademico_id_resultado_seq', 1, false);


--
-- Name: rol_id_rol_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rol_id_rol_seq', 4, true);


--
-- Name: turno_id_turno_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.turno_id_turno_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: usuario_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuario_id_usuario_seq', 7, true);


--
-- Name: administrador administrador_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.administrador
    ADD CONSTRAINT administrador_pkey PRIMARY KEY (id_administrador);


--
-- Name: asignacioncupo asignacioncupo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asignacioncupo
    ADD CONSTRAINT asignacioncupo_pkey PRIMARY KEY (id_asignacioncupo);


--
-- Name: asistencia asistencia_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asistencia
    ADD CONSTRAINT asistencia_pkey PRIMARY KEY (id_asistencia);


--
-- Name: aula aula_codigo_aula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aula
    ADD CONSTRAINT aula_codigo_aula_key UNIQUE (codigo_aula);


--
-- Name: aula aula_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aula
    ADD CONSTRAINT aula_pkey PRIMARY KEY (id_aula);


--
-- Name: bitacora bitacora_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bitacora
    ADD CONSTRAINT bitacora_pkey PRIMARY KEY (id_bitacora);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: carrera carrera_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carrera
    ADD CONSTRAINT carrera_pkey PRIMARY KEY (id_carrera);


--
-- Name: comprobante comprobante_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comprobante
    ADD CONSTRAINT comprobante_pkey PRIMARY KEY (id_comprobante);


--
-- Name: cupocarrera cupocarrera_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cupocarrera
    ADD CONSTRAINT cupocarrera_pkey PRIMARY KEY (id_cupo);


--
-- Name: detalle_bitacora detalle_bitacora_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_bitacora
    ADD CONSTRAINT detalle_bitacora_pkey PRIMARY KEY (id_detallebitacora, id_bitacora);


--
-- Name: docente_especialidad docente_especialidad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docente_especialidad
    ADD CONSTRAINT docente_especialidad_pkey PRIMARY KEY (id_docente, id_especialidad);


--
-- Name: docente docente_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docente
    ADD CONSTRAINT docente_pkey PRIMARY KEY (id_docente);


--
-- Name: documento documento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documento
    ADD CONSTRAINT documento_pkey PRIMARY KEY (id_documento);


--
-- Name: especialidad especialidad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.especialidad
    ADD CONSTRAINT especialidad_pkey PRIMARY KEY (id_especialidad);


--
-- Name: evaluacion evaluacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.evaluacion
    ADD CONSTRAINT evaluacion_pkey PRIMARY KEY (id_evaluacion);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: gestion gestion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gestion
    ADD CONSTRAINT gestion_pkey PRIMARY KEY (id_gestion);


--
-- Name: grupo_horario grupo_horario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_horario
    ADD CONSTRAINT grupo_horario_pkey PRIMARY KEY (id_grupo, id_horario);


--
-- Name: grupo_materia grupo_materia_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_materia
    ADD CONSTRAINT grupo_materia_pkey PRIMARY KEY (id_grupo, id_materia);


--
-- Name: grupo grupo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo
    ADD CONSTRAINT grupo_pkey PRIMARY KEY (id_grupo);


--
-- Name: grupo_postulante grupo_postulante_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_postulante
    ADD CONSTRAINT grupo_postulante_pkey PRIMARY KEY (id_grupo, id_postulante);


--
-- Name: grupo grupo_sigla_grupo_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo
    ADD CONSTRAINT grupo_sigla_grupo_key UNIQUE (sigla_grupo);


--
-- Name: horario horario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.horario
    ADD CONSTRAINT horario_pkey PRIMARY KEY (id_horario);


--
-- Name: inscripcion_carrera inscripcion_carrera_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inscripcion_carrera
    ADD CONSTRAINT inscripcion_carrera_pkey PRIMARY KEY (id_inscripcion, id_carrera);


--
-- Name: inscripcion inscripcion_codigo_inscripcion_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inscripcion
    ADD CONSTRAINT inscripcion_codigo_inscripcion_key UNIQUE (codigo_inscripcion);


--
-- Name: inscripcion inscripcion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inscripcion
    ADD CONSTRAINT inscripcion_pkey PRIMARY KEY (id_inscripcion);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: materia materia_codigo_materia_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materia
    ADD CONSTRAINT materia_codigo_materia_key UNIQUE (codigo_materia);


--
-- Name: materia materia_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materia
    ADD CONSTRAINT materia_pkey PRIMARY KEY (id_materia);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: modalidad modalidad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modalidad
    ADD CONSTRAINT modalidad_pkey PRIMARY KEY (id_modalidad);


--
-- Name: nota nota_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nota
    ADD CONSTRAINT nota_pkey PRIMARY KEY (id_nota);


--
-- Name: pago pago_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago
    ADD CONSTRAINT pago_pkey PRIMARY KEY (id_pago);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: persona persona_ci_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.persona
    ADD CONSTRAINT persona_ci_key UNIQUE (ci);


--
-- Name: persona persona_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.persona
    ADD CONSTRAINT persona_pkey PRIMARY KEY (id_persona);


--
-- Name: posts posts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts
    ADD CONSTRAINT posts_pkey PRIMARY KEY (id);


--
-- Name: postulante postulante_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulante
    ADD CONSTRAINT postulante_pkey PRIMARY KEY (id_postulante);


--
-- Name: reporte reporte_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reporte
    ADD CONSTRAINT reporte_pkey PRIMARY KEY (id_reporte);


--
-- Name: resultadoacademico resultadoacademico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultadoacademico
    ADD CONSTRAINT resultadoacademico_pkey PRIMARY KEY (id_resultado);


--
-- Name: rol rol_nombre_rol_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_nombre_rol_key UNIQUE (nombre_rol);


--
-- Name: rol rol_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_pkey PRIMARY KEY (id_rol);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: superadministrador superadministrador_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.superadministrador
    ADD CONSTRAINT superadministrador_pkey PRIMARY KEY (id_superadministrador);


--
-- Name: turno turno_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.turno
    ADD CONSTRAINT turno_pkey PRIMARY KEY (id_turno);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: usuario usuario_correo_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_correo_key UNIQUE (correo);


--
-- Name: usuario usuario_nombre_usuario_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_nombre_usuario_key UNIQUE (nombre_usuario);


--
-- Name: usuario usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id_usuario);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: failed_jobs_connection_queue_failed_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);


--
-- Name: idx_asistencia_fecha; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_asistencia_fecha ON public.asistencia USING btree (fecha);


--
-- Name: idx_inscripcion_postulante; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_inscripcion_postulante ON public.inscripcion USING btree (id_postulante);


--
-- Name: idx_nota_evaluacion; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_nota_evaluacion ON public.nota USING btree (id_evaluacion);


--
-- Name: idx_pago_inscripcion; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_pago_inscripcion ON public.pago USING btree (id_inscripcion);


--
-- Name: idx_persona_ci; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_persona_ci ON public.persona USING btree (ci);


--
-- Name: idx_persona_nombre_apellido; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_persona_nombre_apellido ON public.persona USING btree (nombre, apellido);


--
-- Name: idx_postulante_persona; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_postulante_persona ON public.postulante USING btree (id_postulante);


--
-- Name: idx_usuario_nombre; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_usuario_nombre ON public.usuario USING btree (nombre_usuario);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: administrador administrador_id_administrador_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.administrador
    ADD CONSTRAINT administrador_id_administrador_fkey FOREIGN KEY (id_administrador) REFERENCES public.persona(id_persona) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: asignacioncupo asignacioncupo_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asignacioncupo
    ADD CONSTRAINT asignacioncupo_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carrera(id_carrera) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: asignacioncupo asignacioncupo_id_gestion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asignacioncupo
    ADD CONSTRAINT asignacioncupo_id_gestion_fkey FOREIGN KEY (id_gestion) REFERENCES public.gestion(id_gestion) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: asistencia asistencia_id_grupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asistencia
    ADD CONSTRAINT asistencia_id_grupo_fkey FOREIGN KEY (id_grupo) REFERENCES public.grupo(id_grupo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: asistencia asistencia_id_materia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asistencia
    ADD CONSTRAINT asistencia_id_materia_fkey FOREIGN KEY (id_materia) REFERENCES public.materia(id_materia) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: asistencia asistencia_id_postulante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asistencia
    ADD CONSTRAINT asistencia_id_postulante_fkey FOREIGN KEY (id_postulante) REFERENCES public.postulante(id_postulante) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: bitacora bitacora_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bitacora
    ADD CONSTRAINT bitacora_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cupocarrera cupocarrera_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cupocarrera
    ADD CONSTRAINT cupocarrera_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carrera(id_carrera) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cupocarrera cupocarrera_id_gestion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cupocarrera
    ADD CONSTRAINT cupocarrera_id_gestion_fkey FOREIGN KEY (id_gestion) REFERENCES public.gestion(id_gestion) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: detalle_bitacora detalle_bitacora_id_bitacora_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_bitacora
    ADD CONSTRAINT detalle_bitacora_id_bitacora_fkey FOREIGN KEY (id_bitacora) REFERENCES public.bitacora(id_bitacora) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: docente_especialidad docente_especialidad_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docente_especialidad
    ADD CONSTRAINT docente_especialidad_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.docente(id_docente) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: docente_especialidad docente_especialidad_id_especialidad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docente_especialidad
    ADD CONSTRAINT docente_especialidad_id_especialidad_fkey FOREIGN KEY (id_especialidad) REFERENCES public.especialidad(id_especialidad) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: docente docente_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docente
    ADD CONSTRAINT docente_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.persona(id_persona) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: documento documento_id_administrador_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documento
    ADD CONSTRAINT documento_id_administrador_fkey FOREIGN KEY (id_administrador) REFERENCES public.administrador(id_administrador) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: documento documento_id_postulante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documento
    ADD CONSTRAINT documento_id_postulante_fkey FOREIGN KEY (id_postulante) REFERENCES public.postulante(id_postulante) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: evaluacion evaluacion_id_grupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.evaluacion
    ADD CONSTRAINT evaluacion_id_grupo_fkey FOREIGN KEY (id_grupo) REFERENCES public.grupo(id_grupo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: evaluacion evaluacion_id_materia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.evaluacion
    ADD CONSTRAINT evaluacion_id_materia_fkey FOREIGN KEY (id_materia) REFERENCES public.materia(id_materia) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: grupo_horario grupo_horario_id_grupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_horario
    ADD CONSTRAINT grupo_horario_id_grupo_fkey FOREIGN KEY (id_grupo) REFERENCES public.grupo(id_grupo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo_horario grupo_horario_id_horario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_horario
    ADD CONSTRAINT grupo_horario_id_horario_fkey FOREIGN KEY (id_horario) REFERENCES public.horario(id_horario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo grupo_id_aula_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo
    ADD CONSTRAINT grupo_id_aula_fkey FOREIGN KEY (id_aula) REFERENCES public.aula(id_aula) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo grupo_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo
    ADD CONSTRAINT grupo_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.docente(id_docente) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo grupo_id_gestion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo
    ADD CONSTRAINT grupo_id_gestion_fkey FOREIGN KEY (id_gestion) REFERENCES public.gestion(id_gestion) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo grupo_id_modalidad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo
    ADD CONSTRAINT grupo_id_modalidad_fkey FOREIGN KEY (id_modalidad) REFERENCES public.modalidad(id_modalidad) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo grupo_id_turno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo
    ADD CONSTRAINT grupo_id_turno_fkey FOREIGN KEY (id_turno) REFERENCES public.turno(id_turno) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo_materia grupo_materia_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_materia
    ADD CONSTRAINT grupo_materia_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.docente(id_docente) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo_materia grupo_materia_id_grupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_materia
    ADD CONSTRAINT grupo_materia_id_grupo_fkey FOREIGN KEY (id_grupo) REFERENCES public.grupo(id_grupo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo_materia grupo_materia_id_materia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_materia
    ADD CONSTRAINT grupo_materia_id_materia_fkey FOREIGN KEY (id_materia) REFERENCES public.materia(id_materia) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo_postulante grupo_postulante_id_grupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_postulante
    ADD CONSTRAINT grupo_postulante_id_grupo_fkey FOREIGN KEY (id_grupo) REFERENCES public.grupo(id_grupo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: grupo_postulante grupo_postulante_id_postulante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupo_postulante
    ADD CONSTRAINT grupo_postulante_id_postulante_fkey FOREIGN KEY (id_postulante) REFERENCES public.postulante(id_postulante) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: inscripcion_carrera inscripcion_carrera_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inscripcion_carrera
    ADD CONSTRAINT inscripcion_carrera_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carrera(id_carrera) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: inscripcion_carrera inscripcion_carrera_id_inscripcion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inscripcion_carrera
    ADD CONSTRAINT inscripcion_carrera_id_inscripcion_fkey FOREIGN KEY (id_inscripcion) REFERENCES public.inscripcion(id_inscripcion) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: inscripcion inscripcion_id_postulante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inscripcion
    ADD CONSTRAINT inscripcion_id_postulante_fkey FOREIGN KEY (id_postulante) REFERENCES public.postulante(id_postulante) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: nota nota_id_evaluacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nota
    ADD CONSTRAINT nota_id_evaluacion_fkey FOREIGN KEY (id_evaluacion) REFERENCES public.evaluacion(id_evaluacion) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: nota nota_id_grupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nota
    ADD CONSTRAINT nota_id_grupo_fkey FOREIGN KEY (id_grupo) REFERENCES public.grupo(id_grupo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: nota nota_id_postulante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nota
    ADD CONSTRAINT nota_id_postulante_fkey FOREIGN KEY (id_postulante) REFERENCES public.postulante(id_postulante) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: pago pago_id_comprobante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago
    ADD CONSTRAINT pago_id_comprobante_fkey FOREIGN KEY (id_comprobante) REFERENCES public.comprobante(id_comprobante) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: pago pago_id_inscripcion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago
    ADD CONSTRAINT pago_id_inscripcion_fkey FOREIGN KEY (id_inscripcion) REFERENCES public.inscripcion(id_inscripcion) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: postulante postulante_id_asignacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulante
    ADD CONSTRAINT postulante_id_asignacion_fkey FOREIGN KEY (id_asignacion) REFERENCES public.asignacioncupo(id_asignacioncupo) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: postulante postulante_id_postulante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulante
    ADD CONSTRAINT postulante_id_postulante_fkey FOREIGN KEY (id_postulante) REFERENCES public.persona(id_persona) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: reporte reporte_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reporte
    ADD CONSTRAINT reporte_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: resultadoacademico resultadoacademico_id_postulante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultadoacademico
    ADD CONSTRAINT resultadoacademico_id_postulante_fkey FOREIGN KEY (id_postulante) REFERENCES public.postulante(id_postulante) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: superadministrador superadministrador_id_superadministrador_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.superadministrador
    ADD CONSTRAINT superadministrador_id_superadministrador_fkey FOREIGN KEY (id_superadministrador) REFERENCES public.persona(id_persona) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: usuario usuario_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES public.persona(id_persona) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: usuario usuario_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.rol(id_rol) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict 4X1vikKEZZf4mdVSXbvQkXgfihDyCR1bNoDKEH1cZiEH8NzhLcR26YsLPKXY664

