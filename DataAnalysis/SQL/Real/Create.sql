
CREATE TABLE sample_set(
	sample_set_ID INT AUTO_INCREMENT,
    sample_set_name VARCHAR(50),
    PRIMARY KEY (sample_set_ID)
);

CREATE TABLE sample(
	sample_ID INT AUTO_INCREMENT,
    sample_set_ID INT,
	sample_name VARCHAR(50),
	sample_material VARCHAR(50),
	sample_comment VARCHAR(2000),
	sample_picture VARCHAR(2048),
	PRIMARY KEY(sample_ID),
    FOREIGN KEY(sample_set_ID) REFERENCES sample_set(sample_set_ID)
);

CREATE TABLE anlys_equipment(
	anlys_eq_ID INT AUTO_INCREMENT,
    anlys_eq_name VARCHAR(50),
    anlys_eq_comment VARCHAR(2000),
    anlys_eq_active BOOLEAN,
    PRIMARY KEY(anlys_eq_ID)
);

INSERT INTO anlys_equipment(anlys_eq_name, anlys_eq_active) VALUES
		("Dektak",TRUE), ("Rockwell Hardness Tester", TRUE), ("LaWave", TRUE),
		("Contact Angle Goniometer", TRUE), ("Tribometer", TRUE), ("UV VIS", TRUE),
		("Calotte Grinder", TRUE), ("AFM", TRUE), ("Stereo Microscope", TRUE),
		("Nikon Microscope", TRUE), ("Laurane", TRUE), ("XPS", TRUE);
            
CREATE TABLE anlys_property(
		anlys_prop_ID INT AUTO_INCREMENT,
        anlys_prop_name VARCHAR(50),
        PRIMARY KEY(anlys_prop_ID),
        CONSTRAINT uniq_prop_name UNIQUE (anlys_prop_name)
);

ALTER TABLE anlys_property ADD anlys_prop_active BOOLEAN;

INSERT INTO anlys_property(anlys_prop_name) VALUES
			("Thickness"), ("Roughness"), ("Color"), ("Adhesion"), 
            ("Young's Modulus"), ("Contact angle"), ("Wear rate"),
            ("Coefficient of friction"), ("Transparency"), ("Reflectance"),
            ("Density"), ("Atomic composition");

-- anlys_param_X are names of extra parameters e.g. angle when measuring thickness. --
CREATE TABLE anlys_eq_prop(
		anlys_eq_prop_ID INT AUTO_INCREMENT,
        anlys_eq_ID INT,
        anlys_prop_ID INT,
        anlys_param_1 VARCHAR(50),
        anlys_param_2 VARCHAR(50),
        anlys_param_3 VARCHAR(50),
        PRIMARY KEY(anlys_eq_prop_ID),
        FOREIGN KEY(anlys_eq_ID) REFERENCES anlys_equipment(anlys_eq_ID),
        FOREIGN KEY(anlys_prop_ID) REFERENCES anlys_property(anlys_prop_ID),
        CONSTRAINT uniq_eq_prop UNIQUE (anlys_eq_ID, anlys_prop_ID)
);

ALTER TABLE anlys_eq_prop
ADD CONSTRAINT uniq_eq_prop UNIQUE (anlys_eq_ID, anlys_prop_ID);
-- ALTER TABLE anlys_eq_prop MODIFY anlys_eq_ID INT NOT NULL;
-- ALTER TABLE anlys_eq_prop MODIFY anlys_prop_ID INT NOT NULL;
-- ALTER TABLE anlys_eq_prop MODIFY anlys_aveg BOOLEAN NOT NULL;



INSERT INTO anlys_eq_prop(anlys_eq_ID, anlys_prop_ID) VALUES
		(1,2),(8,2),(1,1),(11,1),(7,1),
        (6,1),(9,3),(10,3),(2,4),(3,5),
        (4,6),(5,7),(5,8),(6,9),(6,10),
        (11,11),(12,12);

-- If we should calculate averages for this pair of eq and prop.
ALTER TABLE anlys_eq_prop ADD anlys_aveg BOOLEAN;
ALTER TABLE anlys_eq_prop ADD anlys_eq_prop_unit VARCHAR(50);
ALTER TABLE anlys_eq_prop ADD anlys_param_1_unit VARCHAR(50);
ALTER TABLE anlys_eq_prop ADD anlys_param_2_unit VARCHAR(50);
ALTER TABLE anlys_eq_prop ADD anlys_param_3_unit VARCHAR(50);


-- INSERT INTO anlys_property(anlys_prop_name, anlys_eq_ID) VALUES
-- 			("Roughness", 1), ("Roughness", 8), ("Thickness", 1), ("Thickness", 11),
--             ("Thickness", 7), ("Thickness", 6), ("Color", 9), ("Color", 10),
-- 			("Adhesion", 2), ("Young's Modulus", 3), ("Contact angle", 4),
--             ("Wear rate", 5), ("Coefficient of friction", 5), ("Transparency", 6),
--             ("Reflectance", 6), ("Density", 11), ("Atomic composition", 12);

CREATE TABLE anlys_result(
	anlys_res_ID INT AUTO_INCREMENT,
    sample_ID INT,
    anlys_eq_prop_ID INT,
    anlys_res_result DOUBLE,
    anlys_res_date DATE,
    anlys_res_comment VARCHAR(2000),
    anlys_res_file MEDIUMBLOB,
    anlys_res_1 DOUBLE,	-- Result of anlys_param_1 --
    anlys_res_2 DOUBLE, -- Result of anlys_param_2 --
    anlys_res_3 DOUBLE, -- Result of anlys_param_3 --
    PRIMARY KEY(anlys_res_ID),
    FOREIGN KEY(sample_ID) REFERENCES sample(sample_ID),
    FOREIGN KEY (anlys_eq_prop_ID) REFERENCES anlys_eq_prop(anlys_eq_prop_ID)
);

ALTER TABLE anlys_result ADD employee_ID INT;
ALTER TABLE anlys_result ADD CONSTRAINT FOREIGN KEY(employee_ID) REFERENCES employee(employee_ID);
-- ALTER TABLE anlys_result MODIFY sample_ID INT NOT NULL;
-- ALTER TABLE anlys_result MODIFY anlys_eq_prop_ID INT NOT NULL;


CREATE TABLE process(
	prcs_ID INT AUTO_INCREMENT,
    sample_ID INT,
    employee_ID INT,
	prcs_date DATE,
    prcs_coating VARCHAR(50),
    prcs_position VARCHAR(50),
    prcs_rotation VARCHAR(50),
    prcs_comment VARCHAR (2000),
	PRIMARY KEY(prcs_ID),
    FOREIGN KEY(sample_ID) REFERENCES sample(sample_ID),
    FOREIGN KEY(employee_ID) REFERENCES employee(employee_ID)
);

ALTER TABLE process ADD prcs_eq_ID INT;
-- ALTER TABLE process ADD CONSTRAINT FOREIGN KEY(prcs_eq_ID) REFERENCES prcs_equipment(prcs_eq_ID);
-- ALTER TABLE process MODIFY sample_ID INT NOT NULL;

CREATE TABLE prcs_equipment(
	prcs_eq_ID INT AUTO_INCREMENT,
	prcs_eq_name VARCHAR(50),
	prcs_eq_acronym VARCHAR(20),
	prcs_eq_comment VARCHAR(2000),
	prcs_eq_active BOOLEAN,
	PRIMARY KEY(prcs_eq_ID)
);

ALTER TABLE prcs_equipment ADD CONSTRAINT uniq_prcs_eq_acronym UNIQUE (prcs_eq_acronym);


INSERT INTO prcs_equipment(prcs_eq_name, prcs_eq_acronym, prcs_eq_active) VALUES
			('PVD Coating Chamber K1', 'K1', TRUE),
            ('PVD Coating Chamber K2', 'K2', TRUE),
            ('PVD Coating Chamber LA', 'LA', TRUE),
            ('PVD Coating Chamber PVD75', 'PVD75', TRUE);
            
CREATE TABLE da_feedback(
	fdbk_ID INT AUTO_INCREMENT,
	employee_ID INT,
	fdbk_date DATE,
	fdbk_description VARCHAR(10000),
    fdbk_location VARCHAR(512),
    fdbk_sample VARCHAR(200),
    fdbk_resolved BOOLEAN,
    fdbk_dev_comment VARCHAR(10000),
	PRIMARY KEY(fdbk_ID),
	FOREIGN KEY (employee_ID) REFERENCES employee(employee_ID)
);

ALTER DATABASE fraunhofer CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE anlys_eq_prop CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE anlys_result CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE sample CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE process CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;