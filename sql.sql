--
-- PostgreSQL database dump
--

-- Dumped from database version 15.2
-- Dumped by pg_dump version 15.2

-- Started on 2025-11-14 19:39:03

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 325 (class 1255 OID 33074)
-- Name: fn_actualizar_rol(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.fn_actualizar_rol() RETURNS trigger
    LANGUAGE plpgsql
    AS $$BEGIN
    -- Cuando se inserta o se modifica un DETALLE_ROL
    IF (TG_OP = 'INSERT' OR TG_OP = 'UPDATE') THEN
        IF NEW.tipo = 'I' THEN
            UPDATE "ROL" SET tingresos = tingresos + NEW.monto, trecibir = trecibir + NEW.monto WHERE id = NEW.idrol;
        ELSIF NEW.tipo = 'E' THEN
            UPDATE "ROL" SET tegresos = tegresos + NEW.monto, trecibir = trecibir - NEW.monto WHERE id = NEW.idrol;
        ELSIF NEW.tipo = 'A' THEN
            UPDATE "ROL" SET tahorro = tahorro + NEW.monto WHERE id = NEW.idrol;
        END IF;
    END IF;

    -- Cuando se elimina un DETALLE_ROL
    IF (TG_OP = 'DELETE') THEN
        IF OLD.tipo = 'I' THEN
            UPDATE "ROL" SET tingresos = tingresos - OLD.monto, trecibir = trecibir - OLD.monto WHERE id = OLD.idrol;
        ELSIF OLD.tipo = 'E' THEN
            UPDATE "ROL" SET tegresos = tegresos - OLD.monto, trecibir = trecibir + OLD.monto WHERE id = OLD.idrol;
        ELSIF OLD.tipo = 'A' THEN
            UPDATE "ROL" SET tahorro = tahorro - OLD.monto WHERE id = OLD.idrol;
        END IF;
    END IF;

    RETURN NEW;
END;

$$;


--
-- TOC entry 326 (class 1255 OID 33748)
-- Name: fn_calcular_metricas_diarias(integer, date); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.fn_calcular_metricas_diarias(p_idhac integer, p_fecha date) RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_total_animales integer;
    v_animales_productivos integer;
    v_produccion_leche numeric(10,2);
    v_ingresos numeric(12,2);
    v_egresos numeric(12,2);
    v_animales_enfermos integer;
BEGIN
    -- Total de animales activos
    SELECT COUNT(*) INTO v_total_animales
    FROM public."ANIMALES" 
    WHERE idhac = p_idhac AND esthac = 1;
    
    -- Animales productivos (en leche)
    SELECT COUNT(*) INTO v_animales_productivos
    FROM public."ANIMALES" 
    WHERE idhac = p_idhac AND esthac = 1 AND estrep = 4;
    
    -- Producción de leche del día
    SELECT COALESCE(SUM(l.totlec), 0) INTO v_produccion_leche
    FROM public."LECHE" l
    JOIN public."GRUPO" g ON l.idgru = g.id
    WHERE g.idhac = p_idhac AND l.feclec = p_fecha;
    
    -- Ingresos del día
    SELECT COALESCE(SUM(i.montoing), 0) INTO v_ingresos
    FROM public."INGRESO" i
    JOIN public."TIPO_INGRESO" ti ON i.idtipi = ti.id
    WHERE ti.idhac = p_idhac AND i.fecing = p_fecha;
    
    -- Egresos del día
    SELECT COALESCE(SUM(e.montoegr), 0) INTO v_egresos
    FROM public."EGRESO" e
    JOIN public."TIPO_EGRESO" te ON e.idtipe = te.id
    WHERE te.idhac = p_idhac AND e.fecegr = p_fecha;
    
    -- Animales enfermos
    SELECT COUNT(*) INTO v_animales_enfermos
    FROM public."ANIMALES"
    WHERE idhac = p_idhac AND estsal IN (2, 3);
    
    -- Insertar o actualizar métricas
    INSERT INTO public."METRICAS_HACIENDA" (
        idhac, fecha, total_animales, animales_productivos, 
        produccion_leche_diaria, ingresos_diarios, egresos_diarios, 
        animales_enfermos
    ) VALUES (
        p_idhac, p_fecha, v_total_animales, v_animales_productivos,
        v_produccion_leche, v_ingresos, v_egresos, v_animales_enfermos
    )
    ON CONFLICT (idhac, fecha) 
    DO UPDATE SET
        total_animales = EXCLUDED.total_animales,
        animales_productivos = EXCLUDED.animales_productivos,
        produccion_leche_diaria = EXCLUDED.produccion_leche_diaria,
        ingresos_diarios = EXCLUDED.ingresos_diarios,
        egresos_diarios = EXCLUDED.egresos_diarios,
        animales_enfermos = EXCLUDED.animales_enfermos;
END;
$$;


--
-- TOC entry 3893 (class 0 OID 0)
-- Dependencies: 326
-- Name: FUNCTION fn_calcular_metricas_diarias(p_idhac integer, p_fecha date); Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON FUNCTION public.fn_calcular_metricas_diarias(p_idhac integer, p_fecha date) IS 'Función para calcular y almacenar las métricas diarias de una hacienda';


--
-- TOC entry 327 (class 1255 OID 33745)
-- Name: fn_registrar_actividad(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.fn_registrar_actividad() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_idhac integer;
    v_descripcion varchar(500);
BEGIN
    -- Obtener el idhac según la tabla
    IF TG_TABLE_NAME = 'ANIMALES' THEN
        v_idhac := COALESCE(NEW.idhac, OLD.idhac);
        IF TG_OP = 'INSERT' THEN
            v_descripcion := 'Nuevo animal registrado: ' || NEW.nombre;
        ELSIF TG_OP = 'UPDATE' THEN
            v_descripcion := 'Animal modificado: ' || NEW.nombre;
        ELSIF TG_OP = 'DELETE' THEN
            v_descripcion := 'Animal eliminado: ' || OLD.nombre;
        END IF;
    ELSIF TG_TABLE_NAME = 'REPRODUCCION' THEN
        SELECT idhac INTO v_idhac FROM public."ANIMALES" WHERE id = COALESCE(NEW.idmadre, OLD.idmadre);
        IF TG_OP = 'INSERT' THEN
            v_descripcion := 'Nuevo proceso de reproducción registrado';
        ELSIF TG_OP = 'UPDATE' THEN
            v_descripcion := 'Proceso de reproducción modificado';
        ELSIF TG_OP = 'DELETE' THEN
            v_descripcion := 'Proceso de reproducción eliminado';
        END IF;
    END IF;
    
    -- Insertar en log de actividades si tenemos los datos necesarios
    IF v_idhac IS NOT NULL THEN
        INSERT INTO public."LOG_ACTIVIDADES" (idhac, tipo_actividad, descripcion, tabla_afectada, id_registro)
        VALUES (v_idhac, TG_OP, v_descripcion, TG_TABLE_NAME, COALESCE(NEW.id, OLD.id));
    END IF;
    
    RETURN COALESCE(NEW, OLD);
END;
$$;


--
-- TOC entry 3894 (class 0 OID 0)
-- Dependencies: 327
-- Name: FUNCTION fn_registrar_actividad(); Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON FUNCTION public.fn_registrar_actividad() IS 'Función trigger para registrar automáticamente las actividades del sistema';


--
-- TOC entry 328 (class 1255 OID 33827)
-- Name: update_hacienda_timestamp(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.update_hacienda_timestamp() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


SET default_table_access_method = heap;

--
-- TOC entry 214 (class 1259 OID 33075)
-- Name: ANIMALES; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."ANIMALES" (
    id integer NOT NULL,
    arete integer,
    nombre character varying(50),
    fecnac date,
    feclle date,
    tiplle integer,
    idraza integer,
    idprov integer,
    idpadre integer,
    idmadre integer,
    estani integer DEFAULT 1,
    pesonac real,
    pesolle real,
    sexani integer,
    espani integer,
    aretea character varying(50),
    esthac integer,
    estsal integer,
    estrep integer,
    idhac integer,
    fecmue date DEFAULT '1900-01-01'::date
);


--
-- TOC entry 215 (class 1259 OID 33080)
-- Name: ANIMALES_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."ANIMALES_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3895 (class 0 OID 0)
-- Dependencies: 215
-- Name: ANIMALES_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."ANIMALES_id_seq" OWNED BY public."ANIMALES".id;


--
-- TOC entry 216 (class 1259 OID 33081)
-- Name: ANIMAL_GRUPO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."ANIMAL_GRUPO" (
    id bigint NOT NULL,
    idani integer,
    idgru integer
);


--
-- TOC entry 217 (class 1259 OID 33084)
-- Name: ANIMAL_GRUPO_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."ANIMAL_GRUPO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3896 (class 0 OID 0)
-- Dependencies: 217
-- Name: ANIMAL_GRUPO_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."ANIMAL_GRUPO_id_seq" OWNED BY public."ANIMAL_GRUPO".id;


--
-- TOC entry 218 (class 1259 OID 33085)
-- Name: CATEGORIA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."CATEGORIA" (
    codcat integer NOT NULL,
    detcat character varying(200),
    estcat integer,
    tipcat integer
);


--
-- TOC entry 219 (class 1259 OID 33088)
-- Name: CATEGORIA_codcat_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."CATEGORIA_codcat_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3897 (class 0 OID 0)
-- Dependencies: 219
-- Name: CATEGORIA_codcat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."CATEGORIA_codcat_seq" OWNED BY public."CATEGORIA".codcat;


--
-- TOC entry 220 (class 1259 OID 33089)
-- Name: CLIENTE; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."CLIENTE" (
    codcli integer NOT NULL,
    nomcli character varying(200),
    telcli character varying(40),
    celcli character varying(40),
    estcli integer,
    idhac integer DEFAULT 1
);


--
-- TOC entry 221 (class 1259 OID 33093)
-- Name: CLIENTE_codcli_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."CLIENTE_codcli_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3898 (class 0 OID 0)
-- Dependencies: 221
-- Name: CLIENTE_codcli_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."CLIENTE_codcli_seq" OWNED BY public."CLIENTE".codcli;


--
-- TOC entry 305 (class 1259 OID 33675)
-- Name: CONFIGURACION_HACIENDA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."CONFIGURACION_HACIENDA" (
    id bigint NOT NULL,
    idhac integer NOT NULL,
    clave character varying(100) NOT NULL,
    valor text,
    descripcion character varying(255),
    tipo_dato character varying(20) DEFAULT 'string'::character varying,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- TOC entry 3899 (class 0 OID 0)
-- Dependencies: 305
-- Name: TABLE "CONFIGURACION_HACIENDA"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public."CONFIGURACION_HACIENDA" IS 'Configuraciones específicas personalizables por hacienda';


--
-- TOC entry 304 (class 1259 OID 33674)
-- Name: CONFIGURACION_HACIENDA_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."CONFIGURACION_HACIENDA_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3900 (class 0 OID 0)
-- Dependencies: 304
-- Name: CONFIGURACION_HACIENDA_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."CONFIGURACION_HACIENDA_id_seq" OWNED BY public."CONFIGURACION_HACIENDA".id;


--
-- TOC entry 222 (class 1259 OID 33094)
-- Name: CONTROLES; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."CONTROLES" (
    id bigint NOT NULL,
    idani integer,
    tipcon integer,
    descon character varying(500),
    vitcon character varying(500),
    reccon character varying(500),
    sigcon character varying(500),
    diacon character varying(500),
    medcon character varying(500),
    tracon character varying(500),
    precon character varying(500),
    revcon character varying(500),
    svicon character varying(500),
    fetcon character varying(500),
    dia2con character varying(500),
    vit2con character varying(500),
    idusu integer,
    fecing time with time zone,
    feccon date
);


--
-- TOC entry 223 (class 1259 OID 33099)
-- Name: CONTROLES_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."CONTROLES_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3901 (class 0 OID 0)
-- Dependencies: 223
-- Name: CONTROLES_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."CONTROLES_id_seq" OWNED BY public."CONTROLES".id;


--
-- TOC entry 224 (class 1259 OID 33100)
-- Name: CUENTA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."CUENTA" (
    codcue integer NOT NULL,
    detcue character varying(100),
    nivel1cue integer DEFAULT 0,
    nivel2cue integer DEFAULT 0,
    nivel3cue integer DEFAULT 0,
    nivel4cue integer DEFAULT 0,
    nivel5cue integer DEFAULT 0,
    codcuedebe integer,
    feccre date DEFAULT CURRENT_DATE
);


--
-- TOC entry 225 (class 1259 OID 33109)
-- Name: CUENTA_codcue_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."CUENTA_codcue_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3902 (class 0 OID 0)
-- Dependencies: 225
-- Name: CUENTA_codcue_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."CUENTA_codcue_seq" OWNED BY public."CUENTA".codcue;


--
-- TOC entry 226 (class 1259 OID 33110)
-- Name: DETALLE_FACTURA_LECHE; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."DETALLE_FACTURA_LECHE" (
    iddetalle bigint NOT NULL,
    idfactura bigint NOT NULL,
    ident bigint NOT NULL
);


--
-- TOC entry 227 (class 1259 OID 33113)
-- Name: DETALLE_FACTURA_LECHE_iddetalle_seq; Type: SEQUENCE; Schema: public; Owner: -
--

ALTER TABLE public."DETALLE_FACTURA_LECHE" ALTER COLUMN iddetalle ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public."DETALLE_FACTURA_LECHE_iddetalle_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 228 (class 1259 OID 33114)
-- Name: DETALLE_TANQUE; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."DETALLE_TANQUE" (
    id integer NOT NULL,
    tanque_id integer NOT NULL,
    milimetros double precision NOT NULL,
    litros double precision NOT NULL
);


--
-- TOC entry 229 (class 1259 OID 33117)
-- Name: DETALLE_TANQUE_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."DETALLE_TANQUE_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3903 (class 0 OID 0)
-- Dependencies: 229
-- Name: DETALLE_TANQUE_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."DETALLE_TANQUE_id_seq" OWNED BY public."DETALLE_TANQUE".id;


--
-- TOC entry 230 (class 1259 OID 33118)
-- Name: DIARIO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."DIARIO" (
    iddia bigint NOT NULL,
    idusu integer,
    fecdia date,
    idgru integer,
    tielec integer
);


--
-- TOC entry 231 (class 1259 OID 33121)
-- Name: DIARIO_ANIMAL; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."DIARIO_ANIMAL" (
    iddiaani bigint NOT NULL,
    idani integer,
    iddia integer,
    lit real
);


--
-- TOC entry 232 (class 1259 OID 33124)
-- Name: DIARIO_ANIMAL_iddiaani_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."DIARIO_ANIMAL_iddiaani_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3904 (class 0 OID 0)
-- Dependencies: 232
-- Name: DIARIO_ANIMAL_iddiaani_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."DIARIO_ANIMAL_iddiaani_seq" OWNED BY public."DIARIO_ANIMAL".iddiaani;


--
-- TOC entry 233 (class 1259 OID 33125)
-- Name: DIARIO_iddia_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."DIARIO_iddia_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3905 (class 0 OID 0)
-- Dependencies: 233
-- Name: DIARIO_iddia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."DIARIO_iddia_seq" OWNED BY public."DIARIO".iddia;


--
-- TOC entry 234 (class 1259 OID 33126)
-- Name: EGRESO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."EGRESO" (
    idegr bigint NOT NULL,
    montoegr numeric(20,2) DEFAULT 0,
    fecegr date DEFAULT ('now'::text)::date,
    idtipe bigint,
    obsegr character varying(255),
    codpro integer DEFAULT 0,
    detegr character varying DEFAULT 500,
    feccre date DEFAULT ('now'::text)::date,
    imagen character varying(255),
    codcuedebe integer,
    codcuehaber integer
);


--
-- TOC entry 235 (class 1259 OID 33136)
-- Name: EGRESO_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."EGRESO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3906 (class 0 OID 0)
-- Dependencies: 235
-- Name: EGRESO_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."EGRESO_id_seq" OWNED BY public."EGRESO".idegr;


--
-- TOC entry 236 (class 1259 OID 33137)
-- Name: EMPLEADOS; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."EMPLEADOS" (
    nombre character varying(50) NOT NULL,
    apellido character varying(50) NOT NULL,
    fecha_nacimiento date NOT NULL,
    cedula_identidad character varying(20) NOT NULL,
    direccion character varying(100) NOT NULL,
    telefono character varying(20) NOT NULL,
    fecha_ingreso date NOT NULL,
    salario_base numeric(20,2) NOT NULL,
    cargo character varying(50) NOT NULL,
    departamento character varying(50) NOT NULL,
    idemp bigint NOT NULL,
    compensacionsalarial numeric(20,2) DEFAULT 0,
    horasextras numeric(20,2) DEFAULT 0,
    dec3 numeric(20,2) DEFAULT 0,
    dec4 numeric(20,2) DEFAULT 0,
    iesspat numeric(20,2) DEFAULT 0,
    iessemp numeric(20,2) DEFAULT 0,
    iessfond numeric(20,2) DEFAULT 0,
    mdec3 integer DEFAULT 1,
    mdec4 integer DEFAULT 1,
    miessfond integer DEFAULT 1,
    nombrecompleto character varying(500),
    horassuple numeric(20,2) DEFAULT 0,
    celular character varying(11),
    correo character varying(500),
    idhac integer DEFAULT 1 NOT NULL,
    estemp integer DEFAULT 1
);


--
-- TOC entry 237 (class 1259 OID 33155)
-- Name: EMPLEADOS_idemp_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."EMPLEADOS_idemp_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3907 (class 0 OID 0)
-- Dependencies: 237
-- Name: EMPLEADOS_idemp_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."EMPLEADOS_idemp_seq" OWNED BY public."EMPLEADOS".idemp;


--
-- TOC entry 238 (class 1259 OID 33156)
-- Name: ENTREGA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."ENTREGA" (
    fecent date,
    totent integer,
    codcli integer,
    idemp integer DEFAULT 0,
    estent integer DEFAULT 1,
    totlit integer,
    obsent character varying(200),
    tieent integer,
    idusu integer,
    alcent character varying(50),
    denent double precision DEFAULT 0,
    tement double precision DEFAULT 0,
    horent time without time zone,
    idhac integer DEFAULT 1,
    ident bigint NOT NULL,
    idfactura bigint,
    imagen character varying(255)
);


--
-- TOC entry 239 (class 1259 OID 33166)
-- Name: ENTREGA_LECHE; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."ENTREGA_LECHE" (
    identlec bigint NOT NULL,
    idlec integer NOT NULL,
    ident integer NOT NULL
);


--
-- TOC entry 240 (class 1259 OID 33169)
-- Name: ENTREGA_LECHE2_identlec_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."ENTREGA_LECHE2_identlec_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3908 (class 0 OID 0)
-- Dependencies: 240
-- Name: ENTREGA_LECHE2_identlec_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."ENTREGA_LECHE2_identlec_seq" OWNED BY public."ENTREGA_LECHE".identlec;


--
-- TOC entry 241 (class 1259 OID 33170)
-- Name: ENTREGA_ident_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."ENTREGA_ident_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3909 (class 0 OID 0)
-- Dependencies: 241
-- Name: ENTREGA_ident_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."ENTREGA_ident_seq" OWNED BY public."ENTREGA".ident;


--
-- TOC entry 242 (class 1259 OID 33171)
-- Name: ESTANCIA_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."ESTANCIA_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 243 (class 1259 OID 33172)
-- Name: ESTANCIA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."ESTANCIA" (
    id integer DEFAULT nextval('public."ESTANCIA_id_seq"'::regclass) NOT NULL,
    detest character varying(50),
    idgru integer,
    idsub integer,
    feciniest date,
    fecfinest date,
    fecsalest date,
    responsable integer,
    idusu integer,
    estest integer DEFAULT 0,
    idhac integer DEFAULT 1 NOT NULL
);


--
-- TOC entry 244 (class 1259 OID 33178)
-- Name: ESTANCIA_idest_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."ESTANCIA_idest_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 245 (class 1259 OID 33179)
-- Name: FACTURA_LECHE; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."FACTURA_LECHE" (
    idfactura bigint NOT NULL,
    fecfac date NOT NULL,
    total_entregas integer NOT NULL,
    precio_leche double precision NOT NULL,
    subtotal double precision NOT NULL,
    seguro double precision NOT NULL,
    retencion double precision NOT NULL,
    bono_compras double precision NOT NULL,
    comision double precision NOT NULL,
    total_pagar double precision NOT NULL,
    codcli integer NOT NULL,
    idusu integer NOT NULL,
    estfac integer DEFAULT 1,
    imagen_factura character varying(255),
    idhac integer NOT NULL,
    fecha_inicio date DEFAULT CURRENT_DATE NOT NULL,
    fecha_fin date DEFAULT CURRENT_DATE NOT NULL,
    seguro_por_litro numeric(10,2) DEFAULT 0,
    retencion_por_litro numeric(10,2) DEFAULT 0,
    bono_compras_por_litro numeric(10,2) DEFAULT 0
);


--
-- TOC entry 246 (class 1259 OID 33188)
-- Name: FACTURA_LECHE_idfactura_seq; Type: SEQUENCE; Schema: public; Owner: -
--

ALTER TABLE public."FACTURA_LECHE" ALTER COLUMN idfactura ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public."FACTURA_LECHE_idfactura_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 247 (class 1259 OID 33189)
-- Name: FOTO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."FOTO" (
    codfot bigint NOT NULL,
    tipofoto integer DEFAULT 0,
    codid integer,
    fecfoto time with time zone,
    codusu integer,
    nomfoto character varying(250)
);


--
-- TOC entry 248 (class 1259 OID 33193)
-- Name: FOTO_codfot_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."FOTO_codfot_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3910 (class 0 OID 0)
-- Dependencies: 248
-- Name: FOTO_codfot_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."FOTO_codfot_seq" OWNED BY public."FOTO".codfot;


--
-- TOC entry 249 (class 1259 OID 33194)
-- Name: GRUPO_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."GRUPO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 150000
    CACHE 1;


--
-- TOC entry 250 (class 1259 OID 33195)
-- Name: GRUPO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."GRUPO" (
    id integer DEFAULT nextval('public."GRUPO_id_seq"'::regclass) NOT NULL,
    detalle character varying(50),
    estgru integer DEFAULT 1,
    idhac integer
);


--
-- TOC entry 313 (class 1259 OID 33823)
-- Name: HACIENDA_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."HACIENDA_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 251 (class 1259 OID 33200)
-- Name: HACIENDA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."HACIENDA" (
    id integer DEFAULT nextval('public."HACIENDA_id_seq"'::regclass) NOT NULL,
    nomhac character varying(500),
    prohac character varying(250),
    ruchac character varying(13),
    lochac character varying(300),
    esthac integer DEFAULT 1,
    litros_terneras integer DEFAULT 0,
    litros_machos integer DEFAULT 0,
    notificaciones_email boolean DEFAULT true,
    reportes_automaticos boolean DEFAULT false,
    email_contacto character varying(255),
    telefono_contacto character varying(20),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- TOC entry 252 (class 1259 OID 33208)
-- Name: INGRESO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."INGRESO" (
    id bigint NOT NULL,
    montoing numeric(20,2) DEFAULT 0,
    fecing date DEFAULT ('now'::text)::date,
    idtipi bigint,
    obsing character varying(255),
    feccre date DEFAULT ('now'::text)::date,
    deting character varying DEFAULT 500,
    codcli integer DEFAULT 0,
    imagen character varying(255),
    codcuedebe integer,
    codcuehaber integer
);


--
-- TOC entry 253 (class 1259 OID 33218)
-- Name: INGRESO_ANIMAL; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."INGRESO_ANIMAL" (
    id bigint NOT NULL,
    idingreso bigint NOT NULL,
    idanimal bigint NOT NULL
);


--
-- TOC entry 254 (class 1259 OID 33221)
-- Name: INGRESO_ANIMAL_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

ALTER TABLE public."INGRESO_ANIMAL" ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public."INGRESO_ANIMAL_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 255 (class 1259 OID 33222)
-- Name: INGRESO_FACTURA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."INGRESO_FACTURA" (
    id bigint NOT NULL,
    idingreso bigint NOT NULL,
    idfactura bigint NOT NULL
);


--
-- TOC entry 256 (class 1259 OID 33225)
-- Name: INGRESO_FACTURA_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

ALTER TABLE public."INGRESO_FACTURA" ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public."INGRESO_FACTURA_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 257 (class 1259 OID 33226)
-- Name: INGRESO_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."INGRESO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3911 (class 0 OID 0)
-- Dependencies: 257
-- Name: INGRESO_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."INGRESO_id_seq" OWNED BY public."INGRESO".id;


--
-- TOC entry 258 (class 1259 OID 33227)
-- Name: LECHE_idlec_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."LECHE_idlec_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 10000000
    CACHE 1;


--
-- TOC entry 259 (class 1259 OID 33228)
-- Name: LECHE; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."LECHE" (
    idlec integer DEFAULT nextval('public."LECHE_idlec_seq"'::regclass) NOT NULL,
    idgru integer,
    idemp integer,
    idusu integer,
    prulec character varying(100),
    nani integer,
    tielec integer,
    feclec date,
    totlec integer,
    t1lec integer,
    m1lec integer,
    t2lec integer,
    m2lec integer,
    ttlec integer,
    totelec integer,
    estlec integer DEFAULT 1,
    conlec integer,
    medida_tanque integer DEFAULT 0,
    medida_anterior_tanque integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now()
);


--
-- TOC entry 303 (class 1259 OID 33665)
-- Name: LOG_ACTIVIDADES; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."LOG_ACTIVIDADES" (
    id bigint NOT NULL,
    idhac integer NOT NULL,
    idusu integer,
    tipo_actividad character varying(50) NOT NULL,
    descripcion character varying(500) NOT NULL,
    tabla_afectada character varying(50),
    id_registro integer,
    fecha_actividad timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    ip_usuario character varying(50),
    datos_anteriores text,
    datos_nuevos text
);


--
-- TOC entry 3912 (class 0 OID 0)
-- Dependencies: 303
-- Name: TABLE "LOG_ACTIVIDADES"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public."LOG_ACTIVIDADES" IS 'Registro de todas las actividades realizadas en el sistema por hacienda';


--
-- TOC entry 302 (class 1259 OID 33664)
-- Name: LOG_ACTIVIDADES_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."LOG_ACTIVIDADES_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3913 (class 0 OID 0)
-- Dependencies: 302
-- Name: LOG_ACTIVIDADES_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."LOG_ACTIVIDADES_id_seq" OWNED BY public."LOG_ACTIVIDADES".id;


--
-- TOC entry 260 (class 1259 OID 33236)
-- Name: MAQUINARIA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."MAQUINARIA" (
    detmaq character varying(250),
    estmaq integer,
    idhac integer DEFAULT 1,
    id bigint NOT NULL
);


--
-- TOC entry 261 (class 1259 OID 33240)
-- Name: MAQUINARIA_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."MAQUINARIA_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3914 (class 0 OID 0)
-- Dependencies: 261
-- Name: MAQUINARIA_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."MAQUINARIA_id_seq" OWNED BY public."MAQUINARIA".id;


--
-- TOC entry 262 (class 1259 OID 33241)
-- Name: MATERIAL; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."MATERIAL" (
    detmat character varying(250),
    canmat integer,
    medmat character varying(25),
    tipmat integer DEFAULT 0,
    id integer NOT NULL,
    idhac integer DEFAULT 1
);


--
-- TOC entry 263 (class 1259 OID 33246)
-- Name: MATERIAL_idmat_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."MATERIAL_idmat_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3915 (class 0 OID 0)
-- Dependencies: 263
-- Name: MATERIAL_idmat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."MATERIAL_idmat_seq" OWNED BY public."MATERIAL".id;


--
-- TOC entry 264 (class 1259 OID 33247)
-- Name: MEDICAMENTOS; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."MEDICAMENTOS" (
    id integer NOT NULL,
    detmed character varying(250),
    dosismed integer,
    tipmed character varying(250)
);


--
-- TOC entry 309 (class 1259 OID 33701)
-- Name: METRICAS_HACIENDA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."METRICAS_HACIENDA" (
    id bigint NOT NULL,
    idhac integer NOT NULL,
    fecha date NOT NULL,
    total_animales integer DEFAULT 0,
    animales_productivos integer DEFAULT 0,
    produccion_leche_diaria numeric(10,2) DEFAULT 0,
    ingresos_diarios numeric(12,2) DEFAULT 0,
    egresos_diarios numeric(12,2) DEFAULT 0,
    animales_enfermos integer DEFAULT 0,
    partos_mes integer DEFAULT 0,
    muertes_mes integer DEFAULT 0,
    ventas_mes integer DEFAULT 0,
    eficiencia_reproductiva numeric(5,2) DEFAULT 0,
    costo_por_litro numeric(8,2) DEFAULT 0,
    ingreso_por_litro numeric(8,2) DEFAULT 0
);


--
-- TOC entry 3916 (class 0 OID 0)
-- Dependencies: 309
-- Name: TABLE "METRICAS_HACIENDA"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public."METRICAS_HACIENDA" IS 'Métricas y KPIs calculados diariamente por hacienda';


--
-- TOC entry 308 (class 1259 OID 33700)
-- Name: METRICAS_HACIENDA_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."METRICAS_HACIENDA_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3917 (class 0 OID 0)
-- Dependencies: 308
-- Name: METRICAS_HACIENDA_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."METRICAS_HACIENDA_id_seq" OWNED BY public."METRICAS_HACIENDA".id;


--
-- TOC entry 307 (class 1259 OID 33689)
-- Name: NOTIFICACIONES; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."NOTIFICACIONES" (
    id bigint NOT NULL,
    idhac integer NOT NULL,
    idusu integer,
    tipo character varying(20) NOT NULL,
    titulo character varying(100) NOT NULL,
    mensaje text NOT NULL,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_lectura timestamp without time zone,
    leida boolean DEFAULT false,
    url_accion character varying(255),
    prioridad integer DEFAULT 1
);


--
-- TOC entry 3918 (class 0 OID 0)
-- Dependencies: 307
-- Name: TABLE "NOTIFICACIONES"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public."NOTIFICACIONES" IS 'Sistema de notificaciones y alertas para usuarios';


--
-- TOC entry 306 (class 1259 OID 33688)
-- Name: NOTIFICACIONES_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."NOTIFICACIONES_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3919 (class 0 OID 0)
-- Dependencies: 306
-- Name: NOTIFICACIONES_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."NOTIFICACIONES_id_seq" OWNED BY public."NOTIFICACIONES".id;


--
-- TOC entry 265 (class 1259 OID 33252)
-- Name: POTREROS; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."POTREROS" (
    idpot integer NOT NULL,
    nompot character varying(255),
    idhac integer NOT NULL,
    suppot numeric(10,2),
    cappot integer,
    estpot integer DEFAULT 1 NOT NULL,
    recpot character varying(255),
    obspot text,
    tippot character varying(255) DEFAULT 0
);


--
-- TOC entry 266 (class 1259 OID 33259)
-- Name: POTREROS_idpot_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."POTREROS_idpot_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3920 (class 0 OID 0)
-- Dependencies: 266
-- Name: POTREROS_idpot_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."POTREROS_idpot_seq" OWNED BY public."POTREROS".idpot;


--
-- TOC entry 267 (class 1259 OID 33260)
-- Name: PROVEEDOR; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."PROVEEDOR" (
    codpro integer NOT NULL,
    nompro character varying(200),
    estpro integer,
    idhac integer DEFAULT 1
);


--
-- TOC entry 268 (class 1259 OID 33264)
-- Name: PROVEEDOR_codpro_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."PROVEEDOR_codpro_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3921 (class 0 OID 0)
-- Dependencies: 268
-- Name: PROVEEDOR_codpro_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."PROVEEDOR_codpro_seq" OWNED BY public."PROVEEDOR".codpro;


--
-- TOC entry 269 (class 1259 OID 33265)
-- Name: RAZA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."RAZA" (
    id integer NOT NULL,
    detalle character varying(100),
    estraza integer DEFAULT 1
);


--
-- TOC entry 270 (class 1259 OID 33269)
-- Name: RAZA_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."RAZA_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3922 (class 0 OID 0)
-- Dependencies: 270
-- Name: RAZA_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."RAZA_id_seq" OWNED BY public."RAZA".id;


--
-- TOC entry 271 (class 1259 OID 33270)
-- Name: REPRODUCCION; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."REPRODUCCION" (
    idrep bigint NOT NULL,
    idmadre integer,
    fecpro date,
    fecres date DEFAULT '1900-01-01'::date,
    idpadre integer,
    idcria integer,
    detrep character varying(200),
    tiprep integer,
    tipres integer,
    obsrep character varying(500),
    fecrev date,
    fecsec date,
    estsec integer DEFAULT 0
);


--
-- TOC entry 3923 (class 0 OID 0)
-- Dependencies: 271
-- Name: TABLE "REPRODUCCION"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public."REPRODUCCION" IS 'Para almancenar el proceso de reproducción del ganado vacuno';


--
-- TOC entry 3924 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "REPRODUCCION".idmadre; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public."REPRODUCCION".idmadre IS 'el id de la vaca madre';


--
-- TOC entry 3925 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "REPRODUCCION".fecpro; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public."REPRODUCCION".fecpro IS 'fecha del proceso';


--
-- TOC entry 3926 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "REPRODUCCION".fecres; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public."REPRODUCCION".fecres IS 'fecha del resultado';


--
-- TOC entry 3927 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "REPRODUCCION".idpadre; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public."REPRODUCCION".idpadre IS 'el id del toro padre o 0 para inseminacion';


--
-- TOC entry 3928 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "REPRODUCCION".idcria; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public."REPRODUCCION".idcria IS 'el id de la cria resultado o 0 para aborto';


--
-- TOC entry 3929 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "REPRODUCCION".detrep; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public."REPRODUCCION".detrep IS 'detalle de la reproduccion';


--
-- TOC entry 3930 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "REPRODUCCION".tiprep; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public."REPRODUCCION".tiprep IS 'tipo 1 para inseminacion y 2 para monta';


--
-- TOC entry 3931 (class 0 OID 0)
-- Dependencies: 271
-- Name: COLUMN "REPRODUCCION".tipres; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public."REPRODUCCION".tipres IS '1 para aborto y 2 para cria';


--
-- TOC entry 272 (class 1259 OID 33277)
-- Name: REPRODUCCION_idrep_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."REPRODUCCION_idrep_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3932 (class 0 OID 0)
-- Dependencies: 272
-- Name: REPRODUCCION_idrep_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."REPRODUCCION_idrep_seq" OWNED BY public."REPRODUCCION".idrep;


--
-- TOC entry 273 (class 1259 OID 33278)
-- Name: ROL; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."ROL" (
    id integer NOT NULL,
    idemp integer NOT NULL,
    anio integer,
    mes integer,
    tingresos numeric(20,2) DEFAULT 0,
    tegresos numeric(20,2) DEFAULT 0,
    trecibir numeric(20,2) DEFAULT 0,
    fecrol date DEFAULT ('now'::text)::date,
    fecpago date,
    fecmostrar date,
    obsrol character varying(250),
    tahorro numeric(20,2) DEFAULT 0
);


--
-- TOC entry 274 (class 1259 OID 33286)
-- Name: ROL_CATEGORIA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."ROL_CATEGORIA" (
    idcat integer NOT NULL,
    detcar character varying(255) NOT NULL,
    tipcat integer NOT NULL
);


--
-- TOC entry 275 (class 1259 OID 33289)
-- Name: ROL_DETALLE; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."ROL_DETALLE" (
    idroldet integer NOT NULL,
    idrol integer,
    descripcion character varying(255) NOT NULL,
    tipo character(1),
    monto numeric(20,2) NOT NULL,
    fecha date DEFAULT ('now'::text)::date,
    idcat integer DEFAULT 1 NOT NULL
);


--
-- TOC entry 276 (class 1259 OID 33294)
-- Name: ROL_DETALLE_idroldet_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."ROL_DETALLE_idroldet_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3933 (class 0 OID 0)
-- Dependencies: 276
-- Name: ROL_DETALLE_idroldet_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."ROL_DETALLE_idroldet_seq" OWNED BY public."ROL_DETALLE".idroldet;


--
-- TOC entry 277 (class 1259 OID 33295)
-- Name: SUBCATEGORIA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."SUBCATEGORIA" (
    codsub integer NOT NULL,
    detsub character varying(200),
    cossub money,
    estsub integer,
    codcat integer
);


--
-- TOC entry 278 (class 1259 OID 33298)
-- Name: SUBCATEGORIA_codsub_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."SUBCATEGORIA_codsub_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3934 (class 0 OID 0)
-- Dependencies: 278
-- Name: SUBCATEGORIA_codsub_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."SUBCATEGORIA_codsub_seq" OWNED BY public."SUBCATEGORIA".codsub;


--
-- TOC entry 279 (class 1259 OID 33299)
-- Name: TANQUE; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TANQUE" (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    capacidad double precision NOT NULL,
    fabricante character varying(100),
    minimo double precision NOT NULL,
    maximo double precision NOT NULL,
    idhac integer
);


--
-- TOC entry 280 (class 1259 OID 33302)
-- Name: TANQUE_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."TANQUE_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3935 (class 0 OID 0)
-- Dependencies: 280
-- Name: TANQUE_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."TANQUE_id_seq" OWNED BY public."TANQUE".id;


--
-- TOC entry 311 (class 1259 OID 33722)
-- Name: TAREAS_PROGRAMADAS; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TAREAS_PROGRAMADAS" (
    id bigint NOT NULL,
    idhac integer NOT NULL,
    idusu integer,
    titulo character varying(100) NOT NULL,
    descripcion text,
    tipo character varying(50),
    prioridad integer DEFAULT 2,
    fecha_programada date NOT NULL,
    hora_programada time without time zone,
    fecha_completada timestamp without time zone,
    completada boolean DEFAULT false,
    notas_completacion text,
    id_animal integer,
    recordatorio_dias integer DEFAULT 1,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    creado_por integer NOT NULL
);


--
-- TOC entry 3936 (class 0 OID 0)
-- Dependencies: 311
-- Name: TABLE "TAREAS_PROGRAMADAS"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public."TAREAS_PROGRAMADAS" IS 'Tareas y recordatorios programados para la gestión de la hacienda';


--
-- TOC entry 310 (class 1259 OID 33721)
-- Name: TAREAS_PROGRAMADAS_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."TAREAS_PROGRAMADAS_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3937 (class 0 OID 0)
-- Dependencies: 310
-- Name: TAREAS_PROGRAMADAS_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."TAREAS_PROGRAMADAS_id_seq" OWNED BY public."TAREAS_PROGRAMADAS".id;


--
-- TOC entry 281 (class 1259 OID 33303)
-- Name: TIPO_EGRESO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TIPO_EGRESO" (
    id bigint NOT NULL,
    dette character varying(100),
    idhac integer NOT NULL
);


--
-- TOC entry 282 (class 1259 OID 33306)
-- Name: TIPO_EGRESO_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."TIPO_EGRESO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3938 (class 0 OID 0)
-- Dependencies: 282
-- Name: TIPO_EGRESO_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."TIPO_EGRESO_id_seq" OWNED BY public."TIPO_EGRESO".id;


--
-- TOC entry 283 (class 1259 OID 33307)
-- Name: TIPO_INGRESO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TIPO_INGRESO" (
    id bigint NOT NULL,
    detti character varying(100),
    idhac integer NOT NULL
);


--
-- TOC entry 284 (class 1259 OID 33310)
-- Name: TIPO_INGRESO_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."TIPO_INGRESO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3939 (class 0 OID 0)
-- Dependencies: 284
-- Name: TIPO_INGRESO_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."TIPO_INGRESO_id_seq" OWNED BY public."TIPO_INGRESO".id;


--
-- TOC entry 285 (class 1259 OID 33311)
-- Name: TRABAJO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TRABAJO" (
    fectra date,
    dettra character varying(250),
    fecfintra date,
    fecinitra date,
    idsub integer,
    tiptra integer,
    idhac integer DEFAULT 1,
    id integer NOT NULL
);


--
-- TOC entry 286 (class 1259 OID 33315)
-- Name: TRABAJO_EMPLEADO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TRABAJO_EMPLEADO" (
    id integer NOT NULL,
    idtra integer,
    idemp bigint
);


--
-- TOC entry 287 (class 1259 OID 33318)
-- Name: TRABAJO_EMPLEADO_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."TRABAJO_EMPLEADO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3940 (class 0 OID 0)
-- Dependencies: 287
-- Name: TRABAJO_EMPLEADO_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."TRABAJO_EMPLEADO_id_seq" OWNED BY public."TRABAJO_EMPLEADO".id;


--
-- TOC entry 288 (class 1259 OID 33319)
-- Name: TRABAJO_MAQUINARIA_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."TRABAJO_MAQUINARIA_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 289 (class 1259 OID 33320)
-- Name: TRABAJO_MAQUINARIA; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TRABAJO_MAQUINARIA" (
    id integer DEFAULT nextval('public."TRABAJO_MAQUINARIA_id_seq"'::regclass) NOT NULL,
    idmaq integer,
    idtra integer,
    cantramaq real,
    medtramaq character varying(50),
    esttramaq integer
);


--
-- TOC entry 290 (class 1259 OID 33324)
-- Name: TRABAJO_MATERIAL_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."TRABAJO_MATERIAL_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 291 (class 1259 OID 33325)
-- Name: TRABAJO_MATERIAL; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TRABAJO_MATERIAL" (
    id integer DEFAULT nextval('public."TRABAJO_MATERIAL_id_seq"'::regclass) NOT NULL,
    idmat integer,
    idtra integer,
    cantramat real,
    medtramat character varying(50),
    esttramat integer DEFAULT 1
);


--
-- TOC entry 292 (class 1259 OID 33330)
-- Name: TRABAJO_POTRERO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TRABAJO_POTRERO" (
    id integer NOT NULL,
    idtra integer,
    idpot integer
);


--
-- TOC entry 293 (class 1259 OID 33333)
-- Name: TRABAJO_POTRERO_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."TRABAJO_POTRERO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3941 (class 0 OID 0)
-- Dependencies: 293
-- Name: TRABAJO_POTRERO_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."TRABAJO_POTRERO_id_seq" OWNED BY public."TRABAJO_POTRERO".id;


--
-- TOC entry 294 (class 1259 OID 33334)
-- Name: TRABAJO_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."TRABAJO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3942 (class 0 OID 0)
-- Dependencies: 294
-- Name: TRABAJO_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."TRABAJO_id_seq" OWNED BY public."TRABAJO".id;


--
-- TOC entry 295 (class 1259 OID 33335)
-- Name: TRATAMIENTO; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."TRATAMIENTO" (
    id integer NOT NULL,
    idani integer,
    dettra character varying(500),
    fecinitra date,
    fecfintra date,
    obstra character varying(500)
);


--
-- TOC entry 296 (class 1259 OID 33340)
-- Name: USUARIOS; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."USUARIOS" (
    id integer NOT NULL,
    nomusu character varying(100),
    username character varying(300),
    pass character varying(300),
    emailusu character varying(250),
    estusu integer,
    idhac integer,
    tipusu integer,
    ultimo_acceso timestamp without time zone,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    intentos_fallidos integer DEFAULT 0,
    bloqueado_hasta timestamp without time zone
);


--
-- TOC entry 297 (class 1259 OID 33345)
-- Name: USUARIOS_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."USUARIOS_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3943 (class 0 OID 0)
-- Dependencies: 297
-- Name: USUARIOS_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."USUARIOS_id_seq" OWNED BY public."USUARIOS".id;


--
-- TOC entry 312 (class 1259 OID 33749)
-- Name: VW_DASHBOARD_METRICAS; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public."VW_DASHBOARD_METRICAS" AS
 SELECT h.id AS idhac,
    h.nomhac,
    ( SELECT count(*) AS count
           FROM public."ANIMALES" a
          WHERE ((a.idhac = h.id) AND (a.esthac = 1))) AS total_animales,
    ( SELECT count(*) AS count
           FROM public."ANIMALES" a
          WHERE ((a.idhac = h.id) AND (a.esthac = 1) AND (a.estrep = 4))) AS animales_lecheros,
    ( SELECT count(*) AS count
           FROM public."ANIMALES" a
          WHERE ((a.idhac = h.id) AND (a.estsal = ANY (ARRAY[2, 3])))) AS animales_enfermos,
    ( SELECT count(*) AS count
           FROM public."USUARIOS" u
          WHERE ((u.idhac = h.id) AND (u.estusu = 1))) AS usuarios_activos,
    ( SELECT COALESCE(sum(l.totlec), (0)::bigint) AS "coalesce"
           FROM (public."LECHE" l
             JOIN public."GRUPO" g ON ((l.idgru = g.id)))
          WHERE ((g.idhac = h.id) AND (EXTRACT(month FROM l.feclec) = EXTRACT(month FROM CURRENT_DATE)) AND (EXTRACT(year FROM l.feclec) = EXTRACT(year FROM CURRENT_DATE)))) AS leche_mes_actual,
    ( SELECT COALESCE(sum(i.montoing), (0)::numeric) AS "coalesce"
           FROM (public."INGRESO" i
             JOIN public."TIPO_INGRESO" ti ON ((i.idtipi = ti.id)))
          WHERE ((ti.idhac = h.id) AND (EXTRACT(month FROM i.fecing) = EXTRACT(month FROM CURRENT_DATE)) AND (EXTRACT(year FROM i.fecing) = EXTRACT(year FROM CURRENT_DATE)))) AS ingresos_mes_actual,
    ( SELECT COALESCE(sum(e.montoegr), (0)::numeric) AS "coalesce"
           FROM (public."EGRESO" e
             JOIN public."TIPO_EGRESO" te ON ((e.idtipe = te.id)))
          WHERE ((te.idhac = h.id) AND (EXTRACT(month FROM e.fecegr) = EXTRACT(month FROM CURRENT_DATE)) AND (EXTRACT(year FROM e.fecegr) = EXTRACT(year FROM CURRENT_DATE)))) AS egresos_mes_actual,
    ( SELECT count(*) AS count
           FROM (public."REPRODUCCION" r
             JOIN public."ANIMALES" a ON ((r.idmadre = a.id)))
          WHERE ((a.idhac = h.id) AND (r.tipres = ANY (ARRAY[0, 4])))) AS reproducciones_activas,
    ( SELECT count(*) AS count
           FROM (public."REPRODUCCION" r
             JOIN public."ANIMALES" a ON ((r.idmadre = a.id)))
          WHERE ((a.idhac = h.id) AND ((r.fecres >= CURRENT_DATE) AND (r.fecres <= (CURRENT_DATE + '30 days'::interval))) AND (r.tipres = 4))) AS proximos_partos
   FROM public."HACIENDA" h
  WHERE (h.esthac = 1);


--
-- TOC entry 3944 (class 0 OID 0)
-- Dependencies: 312
-- Name: VIEW "VW_DASHBOARD_METRICAS"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON VIEW public."VW_DASHBOARD_METRICAS" IS 'Vista con métricas principales para el dashboard de cada hacienda';


--
-- TOC entry 298 (class 1259 OID 33346)
-- Name: prueba; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.prueba (
    id integer NOT NULL,
    numero_inicio integer NOT NULL,
    tipo character varying(50) NOT NULL,
    url character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- TOC entry 299 (class 1259 OID 33350)
-- Name: prueba_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.prueba_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3945 (class 0 OID 0)
-- Dependencies: 299
-- Name: prueba_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.prueba_id_seq OWNED BY public.prueba.id;


--
-- TOC entry 300 (class 1259 OID 33351)
-- Name: rol_categoria_idcat_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rol_categoria_idcat_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3946 (class 0 OID 0)
-- Dependencies: 300
-- Name: rol_categoria_idcat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rol_categoria_idcat_seq OWNED BY public."ROL_CATEGORIA".idcat;


--
-- TOC entry 301 (class 1259 OID 33352)
-- Name: rol_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rol_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3947 (class 0 OID 0)
-- Dependencies: 301
-- Name: rol_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rol_id_seq OWNED BY public."ROL".id;


--
-- TOC entry 3425 (class 2604 OID 33353)
-- Name: ANIMALES id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ANIMALES" ALTER COLUMN id SET DEFAULT nextval('public."ANIMALES_id_seq"'::regclass);


--
-- TOC entry 3428 (class 2604 OID 33354)
-- Name: ANIMAL_GRUPO id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ANIMAL_GRUPO" ALTER COLUMN id SET DEFAULT nextval('public."ANIMAL_GRUPO_id_seq"'::regclass);


--
-- TOC entry 3429 (class 2604 OID 33355)
-- Name: CATEGORIA codcat; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CATEGORIA" ALTER COLUMN codcat SET DEFAULT nextval('public."CATEGORIA_codcat_seq"'::regclass);


--
-- TOC entry 3430 (class 2604 OID 33356)
-- Name: CLIENTE codcli; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CLIENTE" ALTER COLUMN codcli SET DEFAULT nextval('public."CLIENTE_codcli_seq"'::regclass);


--
-- TOC entry 3545 (class 2604 OID 33678)
-- Name: CONFIGURACION_HACIENDA id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CONFIGURACION_HACIENDA" ALTER COLUMN id SET DEFAULT nextval('public."CONFIGURACION_HACIENDA_id_seq"'::regclass);


--
-- TOC entry 3432 (class 2604 OID 33357)
-- Name: CONTROLES id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CONTROLES" ALTER COLUMN id SET DEFAULT nextval('public."CONTROLES_id_seq"'::regclass);


--
-- TOC entry 3433 (class 2604 OID 33358)
-- Name: CUENTA codcue; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CUENTA" ALTER COLUMN codcue SET DEFAULT nextval('public."CUENTA_codcue_seq"'::regclass);


--
-- TOC entry 3440 (class 2604 OID 33359)
-- Name: DETALLE_TANQUE id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DETALLE_TANQUE" ALTER COLUMN id SET DEFAULT nextval('public."DETALLE_TANQUE_id_seq"'::regclass);


--
-- TOC entry 3441 (class 2604 OID 33360)
-- Name: DIARIO iddia; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DIARIO" ALTER COLUMN iddia SET DEFAULT nextval('public."DIARIO_iddia_seq"'::regclass);


--
-- TOC entry 3442 (class 2604 OID 33361)
-- Name: DIARIO_ANIMAL iddiaani; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DIARIO_ANIMAL" ALTER COLUMN iddiaani SET DEFAULT nextval('public."DIARIO_ANIMAL_iddiaani_seq"'::regclass);


--
-- TOC entry 3443 (class 2604 OID 33362)
-- Name: EGRESO idegr; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."EGRESO" ALTER COLUMN idegr SET DEFAULT nextval('public."EGRESO_id_seq"'::regclass);


--
-- TOC entry 3449 (class 2604 OID 33363)
-- Name: EMPLEADOS idemp; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."EMPLEADOS" ALTER COLUMN idemp SET DEFAULT nextval('public."EMPLEADOS_idemp_seq"'::regclass);


--
-- TOC entry 3468 (class 2604 OID 33364)
-- Name: ENTREGA ident; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ENTREGA" ALTER COLUMN ident SET DEFAULT nextval('public."ENTREGA_ident_seq"'::regclass);


--
-- TOC entry 3469 (class 2604 OID 33365)
-- Name: ENTREGA_LECHE identlec; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ENTREGA_LECHE" ALTER COLUMN identlec SET DEFAULT nextval('public."ENTREGA_LECHE2_identlec_seq"'::regclass);


--
-- TOC entry 3479 (class 2604 OID 33366)
-- Name: FOTO codfot; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."FOTO" ALTER COLUMN codfot SET DEFAULT nextval('public."FOTO_codfot_seq"'::regclass);


--
-- TOC entry 3491 (class 2604 OID 33367)
-- Name: INGRESO id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO" ALTER COLUMN id SET DEFAULT nextval('public."INGRESO_id_seq"'::regclass);


--
-- TOC entry 3543 (class 2604 OID 33668)
-- Name: LOG_ACTIVIDADES id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."LOG_ACTIVIDADES" ALTER COLUMN id SET DEFAULT nextval('public."LOG_ACTIVIDADES_id_seq"'::regclass);


--
-- TOC entry 3503 (class 2604 OID 33368)
-- Name: MAQUINARIA id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."MAQUINARIA" ALTER COLUMN id SET DEFAULT nextval('public."MAQUINARIA_id_seq"'::regclass);


--
-- TOC entry 3505 (class 2604 OID 33369)
-- Name: MATERIAL id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."MATERIAL" ALTER COLUMN id SET DEFAULT nextval('public."MATERIAL_idmat_seq"'::regclass);


--
-- TOC entry 3553 (class 2604 OID 33704)
-- Name: METRICAS_HACIENDA id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."METRICAS_HACIENDA" ALTER COLUMN id SET DEFAULT nextval('public."METRICAS_HACIENDA_id_seq"'::regclass);


--
-- TOC entry 3549 (class 2604 OID 33692)
-- Name: NOTIFICACIONES id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."NOTIFICACIONES" ALTER COLUMN id SET DEFAULT nextval('public."NOTIFICACIONES_id_seq"'::regclass);


--
-- TOC entry 3507 (class 2604 OID 33370)
-- Name: POTREROS idpot; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."POTREROS" ALTER COLUMN idpot SET DEFAULT nextval('public."POTREROS_idpot_seq"'::regclass);


--
-- TOC entry 3510 (class 2604 OID 33371)
-- Name: PROVEEDOR codpro; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."PROVEEDOR" ALTER COLUMN codpro SET DEFAULT nextval('public."PROVEEDOR_codpro_seq"'::regclass);


--
-- TOC entry 3512 (class 2604 OID 33372)
-- Name: RAZA id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."RAZA" ALTER COLUMN id SET DEFAULT nextval('public."RAZA_id_seq"'::regclass);


--
-- TOC entry 3514 (class 2604 OID 33373)
-- Name: REPRODUCCION idrep; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."REPRODUCCION" ALTER COLUMN idrep SET DEFAULT nextval('public."REPRODUCCION_idrep_seq"'::regclass);


--
-- TOC entry 3517 (class 2604 OID 33374)
-- Name: ROL id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ROL" ALTER COLUMN id SET DEFAULT nextval('public.rol_id_seq'::regclass);


--
-- TOC entry 3523 (class 2604 OID 33375)
-- Name: ROL_CATEGORIA idcat; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ROL_CATEGORIA" ALTER COLUMN idcat SET DEFAULT nextval('public.rol_categoria_idcat_seq'::regclass);


--
-- TOC entry 3524 (class 2604 OID 33376)
-- Name: ROL_DETALLE idroldet; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ROL_DETALLE" ALTER COLUMN idroldet SET DEFAULT nextval('public."ROL_DETALLE_idroldet_seq"'::regclass);


--
-- TOC entry 3527 (class 2604 OID 33377)
-- Name: SUBCATEGORIA codsub; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."SUBCATEGORIA" ALTER COLUMN codsub SET DEFAULT nextval('public."SUBCATEGORIA_codsub_seq"'::regclass);


--
-- TOC entry 3528 (class 2604 OID 33378)
-- Name: TANQUE id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TANQUE" ALTER COLUMN id SET DEFAULT nextval('public."TANQUE_id_seq"'::regclass);


--
-- TOC entry 3566 (class 2604 OID 33725)
-- Name: TAREAS_PROGRAMADAS id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TAREAS_PROGRAMADAS" ALTER COLUMN id SET DEFAULT nextval('public."TAREAS_PROGRAMADAS_id_seq"'::regclass);


--
-- TOC entry 3529 (class 2604 OID 33379)
-- Name: TIPO_EGRESO id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TIPO_EGRESO" ALTER COLUMN id SET DEFAULT nextval('public."TIPO_EGRESO_id_seq"'::regclass);


--
-- TOC entry 3530 (class 2604 OID 33380)
-- Name: TIPO_INGRESO id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TIPO_INGRESO" ALTER COLUMN id SET DEFAULT nextval('public."TIPO_INGRESO_id_seq"'::regclass);


--
-- TOC entry 3532 (class 2604 OID 33381)
-- Name: TRABAJO id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO" ALTER COLUMN id SET DEFAULT nextval('public."TRABAJO_id_seq"'::regclass);


--
-- TOC entry 3533 (class 2604 OID 33382)
-- Name: TRABAJO_EMPLEADO id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_EMPLEADO" ALTER COLUMN id SET DEFAULT nextval('public."TRABAJO_EMPLEADO_id_seq"'::regclass);


--
-- TOC entry 3537 (class 2604 OID 33383)
-- Name: TRABAJO_POTRERO id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_POTRERO" ALTER COLUMN id SET DEFAULT nextval('public."TRABAJO_POTRERO_id_seq"'::regclass);


--
-- TOC entry 3538 (class 2604 OID 33384)
-- Name: USUARIOS id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."USUARIOS" ALTER COLUMN id SET DEFAULT nextval('public."USUARIOS_id_seq"'::regclass);


--
-- TOC entry 3541 (class 2604 OID 33385)
-- Name: prueba id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.prueba ALTER COLUMN id SET DEFAULT nextval('public.prueba_id_seq'::regclass);


--
-- TOC entry 3694 (class 2606 OID 33687)
-- Name: CONFIGURACION_HACIENDA CONFIGURACION_HACIENDA_idhac_clave_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CONFIGURACION_HACIENDA"
    ADD CONSTRAINT "CONFIGURACION_HACIENDA_idhac_clave_key" UNIQUE (idhac, clave);


--
-- TOC entry 3696 (class 2606 OID 33685)
-- Name: CONFIGURACION_HACIENDA CONFIGURACION_HACIENDA_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CONFIGURACION_HACIENDA"
    ADD CONSTRAINT "CONFIGURACION_HACIENDA_pkey" PRIMARY KEY (id);


--
-- TOC entry 3591 (class 2606 OID 33387)
-- Name: DETALLE_FACTURA_LECHE DETALLE_FACTURA_LECHE_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DETALLE_FACTURA_LECHE"
    ADD CONSTRAINT "DETALLE_FACTURA_LECHE_pkey" PRIMARY KEY (iddetalle);


--
-- TOC entry 3593 (class 2606 OID 33389)
-- Name: DETALLE_TANQUE DETALLE_TANQUE_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DETALLE_TANQUE"
    ADD CONSTRAINT "DETALLE_TANQUE_pkey" PRIMARY KEY (id);


--
-- TOC entry 3595 (class 2606 OID 33391)
-- Name: DETALLE_TANQUE DETALLE_TANQUE_tanque_id_milimetros_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DETALLE_TANQUE"
    ADD CONSTRAINT "DETALLE_TANQUE_tanque_id_milimetros_key" UNIQUE (tanque_id, milimetros);


--
-- TOC entry 3602 (class 2606 OID 33393)
-- Name: DIARIO_ANIMAL DIARIO_ANIMAL_idani_iddia_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DIARIO_ANIMAL"
    ADD CONSTRAINT "DIARIO_ANIMAL_idani_iddia_key" UNIQUE (idani, iddia);


--
-- TOC entry 3604 (class 2606 OID 33395)
-- Name: DIARIO_ANIMAL DIARIO_ANIMAL_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DIARIO_ANIMAL"
    ADD CONSTRAINT "DIARIO_ANIMAL_pkey" PRIMARY KEY (iddiaani);


--
-- TOC entry 3598 (class 2606 OID 33397)
-- Name: DIARIO DIARIO_fecdia_idgru_tielec_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DIARIO"
    ADD CONSTRAINT "DIARIO_fecdia_idgru_tielec_key" UNIQUE (fecdia, idgru, tielec);


--
-- TOC entry 3600 (class 2606 OID 33399)
-- Name: DIARIO DIARIO_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DIARIO"
    ADD CONSTRAINT "DIARIO_pkey" PRIMARY KEY (iddia);


--
-- TOC entry 3606 (class 2606 OID 33401)
-- Name: EGRESO EGRESO_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."EGRESO"
    ADD CONSTRAINT "EGRESO_pkey" PRIMARY KEY (idegr);


--
-- TOC entry 3613 (class 2606 OID 33403)
-- Name: ENTREGA_LECHE ENTREGA_LECHE2_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ENTREGA_LECHE"
    ADD CONSTRAINT "ENTREGA_LECHE2_pkey" PRIMARY KEY (identlec);


--
-- TOC entry 3617 (class 2606 OID 33405)
-- Name: FACTURA_LECHE FACTURA_LECHE_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."FACTURA_LECHE"
    ADD CONSTRAINT "FACTURA_LECHE_pkey" PRIMARY KEY (idfactura);


--
-- TOC entry 3631 (class 2606 OID 33407)
-- Name: INGRESO_ANIMAL INGRESO_ANIMAL_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO_ANIMAL"
    ADD CONSTRAINT "INGRESO_ANIMAL_pkey" PRIMARY KEY (id);


--
-- TOC entry 3633 (class 2606 OID 33409)
-- Name: INGRESO_FACTURA INGRESO_FACTURA_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO_FACTURA"
    ADD CONSTRAINT "INGRESO_FACTURA_pkey" PRIMARY KEY (id);


--
-- TOC entry 3627 (class 2606 OID 33411)
-- Name: INGRESO INGRESO_id_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO"
    ADD CONSTRAINT "INGRESO_id_key" UNIQUE (id);


--
-- TOC entry 3629 (class 2606 OID 33413)
-- Name: INGRESO INGRESO_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO"
    ADD CONSTRAINT "INGRESO_pkey" PRIMARY KEY (id);


--
-- TOC entry 3690 (class 2606 OID 33673)
-- Name: LOG_ACTIVIDADES LOG_ACTIVIDADES_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."LOG_ACTIVIDADES"
    ADD CONSTRAINT "LOG_ACTIVIDADES_pkey" PRIMARY KEY (id);


--
-- TOC entry 3643 (class 2606 OID 33415)
-- Name: MATERIAL MATERIAL_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."MATERIAL"
    ADD CONSTRAINT "MATERIAL_pkey" PRIMARY KEY (id);


--
-- TOC entry 3702 (class 2606 OID 33720)
-- Name: METRICAS_HACIENDA METRICAS_HACIENDA_idhac_fecha_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."METRICAS_HACIENDA"
    ADD CONSTRAINT "METRICAS_HACIENDA_idhac_fecha_key" UNIQUE (idhac, fecha);


--
-- TOC entry 3704 (class 2606 OID 33718)
-- Name: METRICAS_HACIENDA METRICAS_HACIENDA_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."METRICAS_HACIENDA"
    ADD CONSTRAINT "METRICAS_HACIENDA_pkey" PRIMARY KEY (id);


--
-- TOC entry 3698 (class 2606 OID 33699)
-- Name: NOTIFICACIONES NOTIFICACIONES_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."NOTIFICACIONES"
    ADD CONSTRAINT "NOTIFICACIONES_pkey" PRIMARY KEY (id);


--
-- TOC entry 3647 (class 2606 OID 33417)
-- Name: POTREROS POTREROS_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."POTREROS"
    ADD CONSTRAINT "POTREROS_pkey" PRIMARY KEY (idpot);


--
-- TOC entry 3653 (class 2606 OID 33419)
-- Name: REPRODUCCION REPRODUCCION_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."REPRODUCCION"
    ADD CONSTRAINT "REPRODUCCION_pkey" PRIMARY KEY (idrep);


--
-- TOC entry 3663 (class 2606 OID 33421)
-- Name: ROL_DETALLE ROL_DETALLE_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ROL_DETALLE"
    ADD CONSTRAINT "ROL_DETALLE_pkey" PRIMARY KEY (idroldet);


--
-- TOC entry 3656 (class 2606 OID 33423)
-- Name: ROL ROL_idemp_anio_mes_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ROL"
    ADD CONSTRAINT "ROL_idemp_anio_mes_key" UNIQUE (idemp, anio, mes);


--
-- TOC entry 3667 (class 2606 OID 33425)
-- Name: TANQUE TANQUE_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TANQUE"
    ADD CONSTRAINT "TANQUE_pkey" PRIMARY KEY (id);


--
-- TOC entry 3707 (class 2606 OID 33733)
-- Name: TAREAS_PROGRAMADAS TAREAS_PROGRAMADAS_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TAREAS_PROGRAMADAS"
    ADD CONSTRAINT "TAREAS_PROGRAMADAS_pkey" PRIMARY KEY (id);


--
-- TOC entry 3669 (class 2606 OID 33427)
-- Name: TIPO_EGRESO TIPO_EGRESO_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TIPO_EGRESO"
    ADD CONSTRAINT "TIPO_EGRESO_pkey" PRIMARY KEY (id);


--
-- TOC entry 3671 (class 2606 OID 33429)
-- Name: TIPO_INGRESO TIPO_INGRESO_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TIPO_INGRESO"
    ADD CONSTRAINT "TIPO_INGRESO_pkey" PRIMARY KEY (id);


--
-- TOC entry 3675 (class 2606 OID 33431)
-- Name: TRABAJO_EMPLEADO TRABAJO_EMPLEADO_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_EMPLEADO"
    ADD CONSTRAINT "TRABAJO_EMPLEADO_pkey" PRIMARY KEY (id);


--
-- TOC entry 3681 (class 2606 OID 33433)
-- Name: TRABAJO_POTRERO TRABAJO_POTRERO_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_POTRERO"
    ADD CONSTRAINT "TRABAJO_POTRERO_pkey" PRIMARY KEY (id);


--
-- TOC entry 3623 (class 2606 OID 33435)
-- Name: HACIENDA hacid; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."HACIENDA"
    ADD CONSTRAINT hacid PRIMARY KEY (id);


--
-- TOC entry 3572 (class 2606 OID 33437)
-- Name: ANIMALES idanimales; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ANIMALES"
    ADD CONSTRAINT idanimales PRIMARY KEY (id);


--
-- TOC entry 3685 (class 2606 OID 33439)
-- Name: USUARIOS idusu; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."USUARIOS"
    ADD CONSTRAINT idusu PRIMARY KEY (id);


--
-- TOC entry 3589 (class 2606 OID 33441)
-- Name: CUENTA pk_cuenta; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CUENTA"
    ADD CONSTRAINT pk_cuenta PRIMARY KEY (codcue);


--
-- TOC entry 3579 (class 2606 OID 33443)
-- Name: CATEGORIA pkcate; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CATEGORIA"
    ADD CONSTRAINT pkcate PRIMARY KEY (codcat);


--
-- TOC entry 3581 (class 2606 OID 33445)
-- Name: CLIENTE pkcodcli; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CLIENTE"
    ADD CONSTRAINT pkcodcli PRIMARY KEY (codcli);


--
-- TOC entry 3585 (class 2606 OID 33447)
-- Name: CONTROLES pkcontrol; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CONTROLES"
    ADD CONSTRAINT pkcontrol PRIMARY KEY (id);


--
-- TOC entry 3608 (class 2606 OID 33449)
-- Name: EMPLEADOS pkempleado; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."EMPLEADOS"
    ADD CONSTRAINT pkempleado UNIQUE (idemp);


--
-- TOC entry 3611 (class 2606 OID 33451)
-- Name: ENTREGA pkentrega; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ENTREGA"
    ADD CONSTRAINT pkentrega PRIMARY KEY (ident);


--
-- TOC entry 3615 (class 2606 OID 33453)
-- Name: ESTANCIA pkestancia; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ESTANCIA"
    ADD CONSTRAINT pkestancia PRIMARY KEY (id);


--
-- TOC entry 3619 (class 2606 OID 33455)
-- Name: FOTO pkfotos; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."FOTO"
    ADD CONSTRAINT pkfotos PRIMARY KEY (codfot);


--
-- TOC entry 3621 (class 2606 OID 33457)
-- Name: GRUPO pkgrupo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."GRUPO"
    ADD CONSTRAINT pkgrupo PRIMARY KEY (id);


--
-- TOC entry 3575 (class 2606 OID 33459)
-- Name: ANIMAL_GRUPO pkidanigru; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ANIMAL_GRUPO"
    ADD CONSTRAINT pkidanigru PRIMARY KEY (id);


--
-- TOC entry 3635 (class 2606 OID 33461)
-- Name: LECHE pkleche; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."LECHE"
    ADD CONSTRAINT pkleche PRIMARY KEY (idlec);


--
-- TOC entry 3645 (class 2606 OID 33463)
-- Name: MEDICAMENTOS pkmedi; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."MEDICAMENTOS"
    ADD CONSTRAINT pkmedi PRIMARY KEY (id);


--
-- TOC entry 3639 (class 2606 OID 33465)
-- Name: MAQUINARIA pkmquinaria; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."MAQUINARIA"
    ADD CONSTRAINT pkmquinaria PRIMARY KEY (id);


--
-- TOC entry 3649 (class 2606 OID 33467)
-- Name: PROVEEDOR pkprov; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."PROVEEDOR"
    ADD CONSTRAINT pkprov PRIMARY KEY (codpro);


--
-- TOC entry 3651 (class 2606 OID 33469)
-- Name: RAZA pkraza; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."RAZA"
    ADD CONSTRAINT pkraza PRIMARY KEY (id);


--
-- TOC entry 3665 (class 2606 OID 33471)
-- Name: SUBCATEGORIA pksubcat; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."SUBCATEGORIA"
    ADD CONSTRAINT pksubcat PRIMARY KEY (codsub);


--
-- TOC entry 3673 (class 2606 OID 33473)
-- Name: TRABAJO pktrabajo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO"
    ADD CONSTRAINT pktrabajo PRIMARY KEY (id);


--
-- TOC entry 3677 (class 2606 OID 33475)
-- Name: TRABAJO_MAQUINARIA pktramaq; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_MAQUINARIA"
    ADD CONSTRAINT pktramaq PRIMARY KEY (id);


--
-- TOC entry 3679 (class 2606 OID 33477)
-- Name: TRABAJO_MATERIAL pktramat; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_MATERIAL"
    ADD CONSTRAINT pktramat PRIMARY KEY (id);


--
-- TOC entry 3683 (class 2606 OID 33479)
-- Name: TRATAMIENTO pktratamineto; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRATAMIENTO"
    ADD CONSTRAINT pktratamineto PRIMARY KEY (id);


--
-- TOC entry 3625 (class 2606 OID 33481)
-- Name: HACIENDA pkunihac; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."HACIENDA"
    ADD CONSTRAINT pkunihac UNIQUE (id);


--
-- TOC entry 3688 (class 2606 OID 33483)
-- Name: prueba prueba_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.prueba
    ADD CONSTRAINT prueba_pkey PRIMARY KEY (id);


--
-- TOC entry 3661 (class 2606 OID 33485)
-- Name: ROL_CATEGORIA rol_categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ROL_CATEGORIA"
    ADD CONSTRAINT rol_categoria_pkey PRIMARY KEY (idcat);


--
-- TOC entry 3658 (class 2606 OID 33487)
-- Name: ROL rol_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ROL"
    ADD CONSTRAINT rol_pkey PRIMARY KEY (id);


--
-- TOC entry 3583 (class 2606 OID 33489)
-- Name: CLIENTE unicodcl; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CLIENTE"
    ADD CONSTRAINT unicodcl UNIQUE (codcli);


--
-- TOC entry 3587 (class 2606 OID 33491)
-- Name: CONTROLES unicontrol; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CONTROLES"
    ADD CONSTRAINT unicontrol UNIQUE (idani, fecing, idusu, tipcon);


--
-- TOC entry 3577 (class 2606 OID 33493)
-- Name: ANIMAL_GRUPO unigrupos; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ANIMAL_GRUPO"
    ADD CONSTRAINT unigrupos UNIQUE (idani, idgru);


--
-- TOC entry 3637 (class 2606 OID 33495)
-- Name: LECHE unilecdiatip; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."LECHE"
    ADD CONSTRAINT unilecdiatip UNIQUE (idgru, feclec, tielec);


--
-- TOC entry 3641 (class 2606 OID 33497)
-- Name: MAQUINARIA unmaqui; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."MAQUINARIA"
    ADD CONSTRAINT unmaqui UNIQUE (id);


--
-- TOC entry 3573 (class 1259 OID 33829)
-- Name: idx_animales_hacienda_estado; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_animales_hacienda_estado ON public."ANIMALES" USING btree (idhac, esthac);


--
-- TOC entry 3609 (class 1259 OID 33830)
-- Name: idx_entrega_fecha_hacienda; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_entrega_fecha_hacienda ON public."ENTREGA" USING btree (fecent, idhac);


--
-- TOC entry 3691 (class 1259 OID 33738)
-- Name: idx_log_actividades_fecha; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_log_actividades_fecha ON public."LOG_ACTIVIDADES" USING btree (fecha_actividad);


--
-- TOC entry 3692 (class 1259 OID 33739)
-- Name: idx_log_actividades_hacienda; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_log_actividades_hacienda ON public."LOG_ACTIVIDADES" USING btree (idhac);


--
-- TOC entry 3705 (class 1259 OID 33742)
-- Name: idx_metricas_fecha_hacienda; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_metricas_fecha_hacienda ON public."METRICAS_HACIENDA" USING btree (idhac, fecha);


--
-- TOC entry 3699 (class 1259 OID 33740)
-- Name: idx_notificaciones_hacienda_usuario; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_notificaciones_hacienda_usuario ON public."NOTIFICACIONES" USING btree (idhac, idusu);


--
-- TOC entry 3700 (class 1259 OID 33741)
-- Name: idx_notificaciones_leida; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_notificaciones_leida ON public."NOTIFICACIONES" USING btree (leida);


--
-- TOC entry 3654 (class 1259 OID 33831)
-- Name: idx_reproduccion_fechas; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_reproduccion_fechas ON public."REPRODUCCION" USING btree (fecpro, fecres, fecrev);


--
-- TOC entry 3596 (class 1259 OID 33498)
-- Name: idx_tanque_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_tanque_id ON public."DETALLE_TANQUE" USING btree (tanque_id);


--
-- TOC entry 3708 (class 1259 OID 33744)
-- Name: idx_tareas_completada; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_tareas_completada ON public."TAREAS_PROGRAMADAS" USING btree (completada);


--
-- TOC entry 3709 (class 1259 OID 33743)
-- Name: idx_tareas_fecha_hacienda; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_tareas_fecha_hacienda ON public."TAREAS_PROGRAMADAS" USING btree (idhac, fecha_programada);


--
-- TOC entry 3686 (class 1259 OID 33832)
-- Name: idx_usuarios_hacienda; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_usuarios_hacienda ON public."USUARIOS" USING btree (idhac, estusu);


--
-- TOC entry 3659 (class 1259 OID 33499)
-- Name: roliduniop; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX roliduniop ON public."ROL" USING btree (id);


--
-- TOC entry 3744 (class 2620 OID 33500)
-- Name: ROL_DETALLE tr_actualizar_rol; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER tr_actualizar_rol AFTER INSERT OR DELETE OR UPDATE ON public."ROL_DETALLE" FOR EACH ROW EXECUTE FUNCTION public.fn_actualizar_rol();


--
-- TOC entry 3741 (class 2620 OID 33833)
-- Name: ANIMALES tr_log_animales; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER tr_log_animales AFTER INSERT OR DELETE OR UPDATE ON public."ANIMALES" FOR EACH ROW EXECUTE FUNCTION public.fn_registrar_actividad();


--
-- TOC entry 3743 (class 2620 OID 33834)
-- Name: REPRODUCCION tr_log_reproduccion; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER tr_log_reproduccion AFTER INSERT OR DELETE OR UPDATE ON public."REPRODUCCION" FOR EACH ROW EXECUTE FUNCTION public.fn_registrar_actividad();


--
-- TOC entry 3742 (class 2620 OID 33828)
-- Name: HACIENDA tr_update_hacienda_timestamp; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER tr_update_hacienda_timestamp BEFORE UPDATE ON public."HACIENDA" FOR EACH ROW EXECUTE FUNCTION public.update_hacienda_timestamp();


--
-- TOC entry 3713 (class 2606 OID 33501)
-- Name: DETALLE_FACTURA_LECHE DETALLE_FACTURA_LECHE_ident_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DETALLE_FACTURA_LECHE"
    ADD CONSTRAINT "DETALLE_FACTURA_LECHE_ident_fkey" FOREIGN KEY (ident) REFERENCES public."ENTREGA"(ident);


--
-- TOC entry 3714 (class 2606 OID 33506)
-- Name: DETALLE_FACTURA_LECHE DETALLE_FACTURA_LECHE_idfactura_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DETALLE_FACTURA_LECHE"
    ADD CONSTRAINT "DETALLE_FACTURA_LECHE_idfactura_fkey" FOREIGN KEY (idfactura) REFERENCES public."FACTURA_LECHE"(idfactura);


--
-- TOC entry 3716 (class 2606 OID 33511)
-- Name: EGRESO EGRESO_tipo_egreso_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."EGRESO"
    ADD CONSTRAINT "EGRESO_tipo_egreso_id_fkey" FOREIGN KEY (idtipe) REFERENCES public."TIPO_EGRESO"(id);


--
-- TOC entry 3719 (class 2606 OID 33516)
-- Name: ESTANCIA ESTANCIA_responsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ESTANCIA"
    ADD CONSTRAINT "ESTANCIA_responsable_fkey" FOREIGN KEY (responsable) REFERENCES public."EMPLEADOS"(idemp);


--
-- TOC entry 3721 (class 2606 OID 33521)
-- Name: INGRESO INGRESO_tipo_ingreso_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO"
    ADD CONSTRAINT "INGRESO_tipo_ingreso_id_fkey" FOREIGN KEY (idtipi) REFERENCES public."TIPO_INGRESO"(id);


--
-- TOC entry 3730 (class 2606 OID 33526)
-- Name: ROL_DETALLE ROL_DETALLE_idrol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ROL_DETALLE"
    ADD CONSTRAINT "ROL_DETALLE_idrol_fkey" FOREIGN KEY (idrol) REFERENCES public."ROL"(id);


--
-- TOC entry 3733 (class 2606 OID 33531)
-- Name: TRABAJO_EMPLEADO TRABAJO_EMPLEADO_idemp_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_EMPLEADO"
    ADD CONSTRAINT "TRABAJO_EMPLEADO_idemp_fkey" FOREIGN KEY (idemp) REFERENCES public."EMPLEADOS"(idemp);


--
-- TOC entry 3738 (class 2606 OID 33536)
-- Name: TRABAJO_POTRERO TRABAJO_POTRERO_idpot_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_POTRERO"
    ADD CONSTRAINT "TRABAJO_POTRERO_idpot_fkey" FOREIGN KEY (idpot) REFERENCES public."POTREROS"(idpot);


--
-- TOC entry 3729 (class 2606 OID 33541)
-- Name: POTREROS fk_hacienda; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."POTREROS"
    ADD CONSTRAINT fk_hacienda FOREIGN KEY (idhac) REFERENCES public."HACIENDA"(id) ON DELETE CASCADE;


--
-- TOC entry 3732 (class 2606 OID 33546)
-- Name: TANQUE fk_hacienda; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TANQUE"
    ADD CONSTRAINT fk_hacienda FOREIGN KEY (idhac) REFERENCES public."HACIENDA"(id);


--
-- TOC entry 3722 (class 2606 OID 33659)
-- Name: INGRESO fk_ingreso_cuenta_haber; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO"
    ADD CONSTRAINT fk_ingreso_cuenta_haber FOREIGN KEY (codcuehaber) REFERENCES public."CUENTA"(codcue);


--
-- TOC entry 3715 (class 2606 OID 33551)
-- Name: DETALLE_TANQUE fk_tanque; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."DETALLE_TANQUE"
    ADD CONSTRAINT fk_tanque FOREIGN KEY (tanque_id) REFERENCES public."TANQUE"(id) ON DELETE CASCADE;


--
-- TOC entry 3710 (class 2606 OID 33556)
-- Name: ANIMAL_GRUPO fkanigurp; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ANIMAL_GRUPO"
    ADD CONSTRAINT fkanigurp FOREIGN KEY (idani) REFERENCES public."ANIMALES"(id);


--
-- TOC entry 3740 (class 2606 OID 33561)
-- Name: TRATAMIENTO fkanimao; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRATAMIENTO"
    ADD CONSTRAINT fkanimao FOREIGN KEY (idani) REFERENCES public."ANIMALES"(id);


--
-- TOC entry 3731 (class 2606 OID 33566)
-- Name: SUBCATEGORIA fkcatsub; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."SUBCATEGORIA"
    ADD CONSTRAINT fkcatsub FOREIGN KEY (codcat) REFERENCES public."CATEGORIA"(codcat);


--
-- TOC entry 3712 (class 2606 OID 33571)
-- Name: CONTROLES fkcontani; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."CONTROLES"
    ADD CONSTRAINT fkcontani FOREIGN KEY (idani) REFERENCES public."ANIMALES"(id);


--
-- TOC entry 3718 (class 2606 OID 33576)
-- Name: ENTREGA_LECHE fkentrega; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ENTREGA_LECHE"
    ADD CONSTRAINT fkentrega FOREIGN KEY (ident) REFERENCES public."ENTREGA"(ident);


--
-- TOC entry 3717 (class 2606 OID 33581)
-- Name: ENTREGA fkentregacliente; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ENTREGA"
    ADD CONSTRAINT fkentregacliente FOREIGN KEY (codcli) REFERENCES public."CLIENTE"(codcli);


--
-- TOC entry 3727 (class 2606 OID 33586)
-- Name: LECHE fkgru; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."LECHE"
    ADD CONSTRAINT fkgru FOREIGN KEY (idgru) REFERENCES public."GRUPO"(id);


--
-- TOC entry 3720 (class 2606 OID 33591)
-- Name: ESTANCIA fkgrupo; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ESTANCIA"
    ADD CONSTRAINT fkgrupo FOREIGN KEY (idgru) REFERENCES public."GRUPO"(id);


--
-- TOC entry 3735 (class 2606 OID 33596)
-- Name: TRABAJO_MAQUINARIA fkmaquinaria; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_MAQUINARIA"
    ADD CONSTRAINT fkmaquinaria FOREIGN KEY (idmaq) REFERENCES public."MAQUINARIA"(id);


--
-- TOC entry 3734 (class 2606 OID 33601)
-- Name: TRABAJO_EMPLEADO fktrabajo; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_EMPLEADO"
    ADD CONSTRAINT fktrabajo FOREIGN KEY (idtra) REFERENCES public."TRABAJO"(id);


--
-- TOC entry 3736 (class 2606 OID 33606)
-- Name: TRABAJO_MAQUINARIA fktrabajom; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_MAQUINARIA"
    ADD CONSTRAINT fktrabajom FOREIGN KEY (idtra) REFERENCES public."TRABAJO"(id);


--
-- TOC entry 3737 (class 2606 OID 33611)
-- Name: TRABAJO_MATERIAL fktrabajom2; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_MATERIAL"
    ADD CONSTRAINT fktrabajom2 FOREIGN KEY (idtra) REFERENCES public."TRABAJO"(id);


--
-- TOC entry 3739 (class 2606 OID 33616)
-- Name: TRABAJO_POTRERO fktrabajop; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."TRABAJO_POTRERO"
    ADD CONSTRAINT fktrabajop FOREIGN KEY (idtra) REFERENCES public."TRABAJO"(id);


--
-- TOC entry 3728 (class 2606 OID 33621)
-- Name: LECHE fkusulec; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."LECHE"
    ADD CONSTRAINT fkusulec FOREIGN KEY (idusu) REFERENCES public."USUARIOS"(id);


--
-- TOC entry 3723 (class 2606 OID 33626)
-- Name: INGRESO_ANIMAL ingreso_animal_fk_animal; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO_ANIMAL"
    ADD CONSTRAINT ingreso_animal_fk_animal FOREIGN KEY (idanimal) REFERENCES public."ANIMALES"(id) ON DELETE CASCADE;


--
-- TOC entry 3724 (class 2606 OID 33631)
-- Name: INGRESO_ANIMAL ingreso_animal_fk_ingreso; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO_ANIMAL"
    ADD CONSTRAINT ingreso_animal_fk_ingreso FOREIGN KEY (idingreso) REFERENCES public."INGRESO"(id) ON DELETE CASCADE;


--
-- TOC entry 3725 (class 2606 OID 33636)
-- Name: INGRESO_FACTURA ingreso_factura_fk_factura; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO_FACTURA"
    ADD CONSTRAINT ingreso_factura_fk_factura FOREIGN KEY (idfactura) REFERENCES public."FACTURA_LECHE"(idfactura) ON DELETE CASCADE;


--
-- TOC entry 3726 (class 2606 OID 33641)
-- Name: INGRESO_FACTURA ingreso_factura_fk_ingreso; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."INGRESO_FACTURA"
    ADD CONSTRAINT ingreso_factura_fk_ingreso FOREIGN KEY (idingreso) REFERENCES public."INGRESO"(id) ON DELETE CASCADE;


--
-- TOC entry 3711 (class 2606 OID 33646)
-- Name: ANIMAL_GRUPO pkunigrupani; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."ANIMAL_GRUPO"
    ADD CONSTRAINT pkunigrupani FOREIGN KEY (idgru) REFERENCES public."GRUPO"(id);


-- Completed on 2025-11-14 19:39:03

--
-- PostgreSQL database dump complete
--

