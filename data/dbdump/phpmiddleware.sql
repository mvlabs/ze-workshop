--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.3
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-08-20 10:08:27 CEST

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 12429)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2208 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 189 (class 1259 OID 16421)
-- Name: chocolates; Type: TABLE; Schema: public; Owner: websc
--

CREATE TABLE chocolates (
    id character(36) NOT NULL,
    producer_id integer NOT NULL,
    description character varying(1023) NOT NULL,
    cacao_percentage integer NOT NULL,
    wrapper_type character varying(255) NOT NULL,
    quantity integer NOT NULL
);


ALTER TABLE chocolates OWNER TO websc;

--
-- TOC entry 190 (class 1259 OID 16435)
-- Name: chocolates_history; Type: TABLE; Schema: public; Owner: websc
--

CREATE TABLE chocolates_history (
    chocolate_id character varying(1023) NOT NULL,
    user_id character varying(36) NOT NULL,
    status character varying(1023) NOT NULL,
    date_time timestamp(0) without time zone DEFAULT now() NOT NULL
);


ALTER TABLE chocolates_history OWNER TO websc;

--
-- TOC entry 185 (class 1259 OID 16395)
-- Name: migrations; Type: TABLE; Schema: public; Owner: websc
--

CREATE TABLE migrations (
    version character varying(255) NOT NULL
);


ALTER TABLE migrations OWNER TO websc;

--
-- TOC entry 187 (class 1259 OID 16409)
-- Name: producers_id_seq; Type: SEQUENCE; Schema: public; Owner: websc
--

CREATE SEQUENCE producers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE producers_id_seq OWNER TO websc;

--
-- TOC entry 188 (class 1259 OID 16411)
-- Name: producers; Type: TABLE; Schema: public; Owner: websc
--

CREATE TABLE producers (
    id integer DEFAULT nextval('producers_id_seq'::regclass) NOT NULL,
    name character varying(1023) NOT NULL,
    street character varying(1023) NOT NULL,
    street_number character varying(255) NOT NULL,
    zip_code character varying(1023) NOT NULL,
    city character varying(1023) NOT NULL,
    region character varying(1023) NOT NULL,
    country character varying(2) NOT NULL
);


ALTER TABLE producers OWNER TO websc;

--
-- TOC entry 186 (class 1259 OID 16400)
-- Name: users; Type: TABLE; Schema: public; Owner: websc
--

CREATE TABLE users (
    id character(36) NOT NULL,
    username character varying(1023) NOT NULL,
    password character varying(1023) NOT NULL,
    admin boolean NOT NULL
);


ALTER TABLE users OWNER TO websc;

--
-- TOC entry 2200 (class 0 OID 16421)
-- Dependencies: 189
-- Data for Name: chocolates; Type: TABLE DATA; Schema: public; Owner: websc
--

COPY chocolates (id, producer_id, description, cacao_percentage, wrapper_type, quantity) FROM stdin;
2825e68b-071a-4ece-b2da-23fbfa926115	1	buona	78	box	100
ec12c4b4-f971-41b2-be6f-b9fa6a95f907	10	70% Ghana	70	box	100
a4f7b703-9de8-44bf-a793-a37842c004ff	10	75% Ecuador	75	box	100
\.


--
-- TOC entry 2201 (class 0 OID 16435)
-- Dependencies: 190
-- Data for Name: chocolates_history; Type: TABLE DATA; Schema: public; Owner: websc
--

COPY chocolates_history (chocolate_id, user_id, status, date_time) FROM stdin;
2825e68b-071a-4ece-b2da-23fbfa926115	8d3d2d5e-ddc8-468b-902d-853231b88aa2	submitted	2017-07-27 16:34:38
2825e68b-071a-4ece-b2da-23fbfa926115	8d3d2d5e-ddc8-468b-902d-853231b88aa2	approved	2017-07-27 16:37:31
2825e68b-071a-4ece-b2da-23fbfa926115	8d3d2d5e-ddc8-468b-902d-853231b88aa2	deleted	2017-07-27 16:38:10
2825e68b-071a-4ece-b2da-23fbfa926115	8d3d2d5e-ddc8-468b-902d-853231b88aa2	deleted	2017-07-27 16:38:19
ec12c4b4-f971-41b2-be6f-b9fa6a95f907	6afe33b6-118d-465e-8ec5-87c8baf36423	submitted	2017-08-15 19:41:26
ec12c4b4-f971-41b2-be6f-b9fa6a95f907	3a9ba7cf-6b36-4f80-b3e1-e91f8170dc0b	approved	2017-08-15 19:45:47
ec12c4b4-f971-41b2-be6f-b9fa6a95f907	3a9ba7cf-6b36-4f80-b3e1-e91f8170dc0b	deleted	2017-08-15 19:51:06
a4f7b703-9de8-44bf-a793-a37842c004ff	6afe33b6-118d-465e-8ec5-87c8baf36423	submitted	2017-08-15 19:52:22
\.


--
-- TOC entry 2196 (class 0 OID 16395)
-- Dependencies: 185
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: websc
--

COPY migrations (version) FROM stdin;
20170411074250
20170412054906
20170412061033
20170412062043
\.


--
-- TOC entry 2199 (class 0 OID 16411)
-- Dependencies: 188
-- Data for Name: producers; Type: TABLE DATA; Schema: public; Owner: websc
--

COPY producers (id, name, street, street_number, zip_code, city, region, country) FROM stdin;
1	amedei	via	12	12345	codroipo	UD	IT
6	Amedei	via San Gervasio	29	56025	Pontedera	PI	IT
7	Domori	via Pinerolo	72-74	10060	None	TO	IT
8	Kraš	Ravnice	48	10000	Zagreb		HR
10	Novi	unknown	0	00000	Milano	UD	IT
\.


--
-- TOC entry 2209 (class 0 OID 0)
-- Dependencies: 187
-- Name: producers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: websc
--

SELECT pg_catalog.setval('producers_id_seq', 10, true);


--
-- TOC entry 2197 (class 0 OID 16400)
-- Dependencies: 186
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: websc
--

COPY users (id, username, password, admin) FROM stdin;
8d3d2d5e-ddc8-468b-902d-853231b88aa2	gigi	zucon	t
3a9ba7cf-6b36-4f80-b3e1-e91f8170dc0b	admin	password	t
6afe33b6-118d-465e-8ec5-87c8baf36423	user	password	f
\.


--
-- TOC entry 2072 (class 2606 OID 16428)
-- Name: chocolates chocolates_pkey; Type: CONSTRAINT; Schema: public; Owner: websc
--

ALTER TABLE ONLY chocolates
    ADD CONSTRAINT chocolates_pkey PRIMARY KEY (id);


--
-- TOC entry 2064 (class 2606 OID 16399)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: websc
--

ALTER TABLE ONLY migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (version);


--
-- TOC entry 2069 (class 2606 OID 16419)
-- Name: producers producers_pkey; Type: CONSTRAINT; Schema: public; Owner: websc
--

ALTER TABLE ONLY producers
    ADD CONSTRAINT producers_pkey PRIMARY KEY (id);


--
-- TOC entry 2067 (class 2606 OID 16407)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: websc
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2073 (class 1259 OID 16429)
-- Name: idx_29f0d89b89b658fe; Type: INDEX; Schema: public; Owner: websc
--

CREATE INDEX idx_29f0d89b89b658fe ON chocolates USING btree (producer_id);


--
-- TOC entry 2074 (class 1259 OID 16442)
-- Name: idx_607720df94d3f813; Type: INDEX; Schema: public; Owner: websc
--

CREATE INDEX idx_607720df94d3f813 ON chocolates_history USING btree (chocolate_id);


--
-- TOC entry 2075 (class 1259 OID 16443)
-- Name: idx_607720dfa76ed395; Type: INDEX; Schema: public; Owner: websc
--

CREATE INDEX idx_607720dfa76ed395 ON chocolates_history USING btree (user_id);


--
-- TOC entry 2065 (class 1259 OID 16408)
-- Name: uniq_1483a5e9f85e0677; Type: INDEX; Schema: public; Owner: websc
--

CREATE UNIQUE INDEX uniq_1483a5e9f85e0677 ON users USING btree (username);


--
-- TOC entry 2070 (class 1259 OID 16420)
-- Name: uniq_94fc35bd5e237e06; Type: INDEX; Schema: public; Owner: websc
--

CREATE UNIQUE INDEX uniq_94fc35bd5e237e06 ON producers USING btree (name);


--
-- TOC entry 2076 (class 2606 OID 16430)
-- Name: chocolates fk_29f0d89b89b658fe; Type: FK CONSTRAINT; Schema: public; Owner: websc
--

ALTER TABLE ONLY chocolates
    ADD CONSTRAINT fk_29f0d89b89b658fe FOREIGN KEY (producer_id) REFERENCES producers(id);


--
-- TOC entry 2077 (class 2606 OID 16444)
-- Name: chocolates_history fk_607720df94d3f813; Type: FK CONSTRAINT; Schema: public; Owner: websc
--

ALTER TABLE ONLY chocolates_history
    ADD CONSTRAINT fk_607720df94d3f813 FOREIGN KEY (chocolate_id) REFERENCES chocolates(id);


--
-- TOC entry 2078 (class 2606 OID 16449)
-- Name: chocolates_history fk_607720dfa76ed395; Type: FK CONSTRAINT; Schema: public; Owner: websc
--

ALTER TABLE ONLY chocolates_history
    ADD CONSTRAINT fk_607720dfa76ed395 FOREIGN KEY (user_id) REFERENCES users(id);


-- Completed on 2017-08-20 10:08:27 CEST

--
-- PostgreSQL database dump complete
--

