--
-- PostgreSQL database dump
--

-- Dumped from database version 17.0
-- Dumped by pg_dump version 17.0

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

--
-- Name: rem_pc2; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE "rem_pc2" WITH TEMPLATE = template0 ENCODING = 'UTF8' ;


ALTER DATABASE "rem_pc2" OWNER TO postgres;

\connect "rem_pc2"

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
-- Name: accepted_devices; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.accepted_devices (
    id integer NOT NULL,
    device_uid character varying(100) NOT NULL,
    client_id integer NOT NULL,
    device_catalog_id integer,
    serial_number character varying(200),
    accessories text,
    reported_problem text,
    received_at timestamp without time zone DEFAULT now() NOT NULL,
    expected_return_date date,
    notes text
);


ALTER TABLE public.accepted_devices OWNER TO postgres;

--
-- Name: accepted_devices_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.accepted_devices ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.accepted_devices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: clients; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.clients (
    id integer NOT NULL,
    client_type character varying(20),
    first_name character varying(100),
    last_name character varying(100),
    middle_name character varying(100),
    company_name character varying(200),
    phone character varying(50),
    email character varying(200),
    address text,
    requisites text,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    notes text
);


ALTER TABLE public.clients OWNER TO postgres;

--
-- Name: clients_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.clients ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: device_movements; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.device_movements (
    id integer NOT NULL,
    accepted_device_id integer NOT NULL,
    state_id integer NOT NULL,
    employee_id integer,
    moved_at timestamp without time zone DEFAULT now() NOT NULL,
    note text
);


ALTER TABLE public.device_movements OWNER TO postgres;

--
-- Name: device_movements_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.device_movements ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.device_movements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: device_states; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.device_states (
    id integer NOT NULL,
    status character varying(100) NOT NULL,
    description text
);


ALTER TABLE public.device_states OWNER TO postgres;

--
-- Name: device_states_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.device_states ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.device_states_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: devices_catalog; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.devices_catalog (
    id integer NOT NULL,
    category character varying(100) NOT NULL,
    description text,
    warranty_months integer
);


ALTER TABLE public.devices_catalog OWNER TO postgres;

--
-- Name: devices_catalog_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.devices_catalog ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.devices_catalog_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: employees; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.employees (
    id integer NOT NULL,
    first_name character varying(100) NOT NULL,
    last_name character varying(100) NOT NULL,
    middle_name character varying(100),
    login character varying(100),
    password character varying(300),
    "position" character varying(100),
    phone character varying(50),
    email character varying(200),
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    notes text
);


ALTER TABLE public.employees OWNER TO postgres;

--
-- Name: employees_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.employees ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.employees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Data for Name: accepted_devices; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.accepted_devices (id, device_uid, client_id, device_catalog_id, serial_number, accessories, reported_problem, received_at, expected_return_date, notes) FROM stdin;
2	ACPT-20251101-001	1	1	SN-LTP-001	Зарядное устройство	Не включается	2025-11-01 00:00:00	2025-11-06	Батарея изношена
3	ACPT-20251102-002	2	2	SN-PC-12345	Кабель питания	Шум вентилятора	2025-11-02 00:00:00	2025-11-05	\N
4	ACPT-20251103-003	3	3	SN-MON-987	\N	Пятна на экране	2025-11-03 00:00:00	2025-11-10	Беречь при транспортировке
\.


--
-- Data for Name: clients; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.clients (id, client_type, first_name, last_name, middle_name, company_name, phone, email, address, requisites, created_at, notes) FROM stdin;
1	индивидуальный	Иван	Петров	Алексеевич	\N	+37129123456	ivan.petrov@example.com	Рига, ул. Ленина, 5	\N	2025-10-01 00:00:00	Постоянный клиент
2	компания	\N	\N	\N	ООО ТехСервис	+37129876543	office@techservice.lv	Рига, ул. Гриня, 12	УНП 123456789	2025-09-20 00:00:00	Юр. лицо
3	индивидуальный	Мария	Иванова	Сергеевна	\N	+37129111222	m.ivanova@example.com	Даугавпилс, ул. Озолниеку, 3	\N	2025-11-05 00:00:00	\N
\.


--
-- Data for Name: device_movements; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.device_movements (id, accepted_device_id, state_id, employee_id, moved_at, note) FROM stdin;
3	2	1	1	2025-11-01 00:00:00	Принял приёмщик
4	3	2	2	2025-11-02 00:00:00	На диагностике
5	4	3	2	2025-11-03 00:00:00	Начал чинить
\.


--
-- Data for Name: device_states; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.device_states (id, status, description) FROM stdin;
1	Приём	Устройство принято в мастерскую
2	Диагностика	Идёт диагностика
3	Ремонт	Идёт ремонт/замена деталей
4	Готов к выдаче	Ремонт завершён, готово к выдаче
5	Возврат без ремонта	Устройство возвращено без ремонта
\.


--
-- Data for Name: devices_catalog; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.devices_catalog (id, category, description, warranty_months) FROM stdin;
1	Ноутбук	"Устройства портативные, разные модели"	\N
2	ПК (системный блок)	"Настольные компьютеры"	\N
3	Монитор	"Дисплеи всех типов"	\N
\.


--
-- Data for Name: employees; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.employees (id, first_name, last_name, middle_name, login, password, "position", phone, email, created_at, notes) FROM stdin;
1	Алексей	Смирнов	Игоревич	asmirnov	123456	Приёмщик	+37129200011	a.smirnov@workshop.com	2024-06-10 00:00:00	Работает утром
2	Ольга	Кузнецова	Петровна	okuznecov	654321	Мастер	+37129200022	o.kuznecov@workshop.com	2024-06-12 00:00:00	\N
3	Петр	Новиков	Дмитриевич	pnovikov	123456	Администратор	+37129200033	\N	2024-06-15 00:00:00	Админ
\.


--
-- Name: accepted_devices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.accepted_devices_id_seq', 4, true);


--
-- Name: clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.clients_id_seq', 3, true);


--
-- Name: device_movements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.device_movements_id_seq', 5, true);


--
-- Name: device_states_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.device_states_id_seq', 5, true);


--
-- Name: devices_catalog_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.devices_catalog_id_seq', 3, true);


--
-- Name: employees_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.employees_id_seq', 3, true);


--
-- Name: accepted_devices accepted_devices_device_uid_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accepted_devices
    ADD CONSTRAINT accepted_devices_device_uid_key UNIQUE (device_uid);


--
-- Name: accepted_devices accepted_devices_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accepted_devices
    ADD CONSTRAINT accepted_devices_pkey PRIMARY KEY (id);


--
-- Name: clients clients_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_pkey PRIMARY KEY (id);


--
-- Name: device_movements device_movements_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.device_movements
    ADD CONSTRAINT device_movements_pkey PRIMARY KEY (id);


--
-- Name: device_states device_states_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.device_states
    ADD CONSTRAINT device_states_pkey PRIMARY KEY (id);


--
-- Name: devices_catalog devices_catalog_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.devices_catalog
    ADD CONSTRAINT devices_catalog_pkey PRIMARY KEY (id);


--
-- Name: employees employees_login_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.employees
    ADD CONSTRAINT employees_login_key UNIQUE (login);


--
-- Name: employees employees_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.employees
    ADD CONSTRAINT employees_pkey PRIMARY KEY (id);


--
-- Name: accepted_devices fk_accepted_catalog; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accepted_devices
    ADD CONSTRAINT fk_accepted_catalog FOREIGN KEY (device_catalog_id) REFERENCES public.devices_catalog(id) ON DELETE SET NULL;


--
-- Name: accepted_devices fk_accepted_client; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accepted_devices
    ADD CONSTRAINT fk_accepted_client FOREIGN KEY (client_id) REFERENCES public.clients(id) ON DELETE RESTRICT;


--
-- Name: device_movements fk_mov_accepted; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.device_movements
    ADD CONSTRAINT fk_mov_accepted FOREIGN KEY (accepted_device_id) REFERENCES public.accepted_devices(id) ON DELETE CASCADE;


--
-- Name: device_movements fk_mov_employee; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.device_movements
    ADD CONSTRAINT fk_mov_employee FOREIGN KEY (employee_id) REFERENCES public.employees(id) ON DELETE SET NULL;


--
-- Name: device_movements fk_mov_state; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.device_movements
    ADD CONSTRAINT fk_mov_state FOREIGN KEY (state_id) REFERENCES public.device_states(id) ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

