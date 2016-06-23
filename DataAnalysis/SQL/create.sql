USE Fraunhofer;

-- DROP TABLE IF EXISTS storage;
-- DROP TABLE IF EXISTS process_property;
-- DROP TABLE IF EXISTS process_equipment;
-- DROP TABLE IF EXISTS anlys_property;
-- DROP TABLE IF EXISTS anlys_equipment;
-- DROP TABLE IF EXISTS anlys_file;
-- DROP TABLE IF EXISTS anlys_value;
-- DROP TABLE IF EXISTS anlys_comment;
-- DROP TABLE IF EXISTS analysis;
-- DROP TABLE IF EXISTS process;
-- DROP TABLE IF EXISTS sample;
-- DROP TABLE IF EXISTS sample_set;

CREATE TABLE sample_set(
	sample_set_ID INT AUTO_INCREMENT,
    sample_set_name VARCHAR(20),
    PRIMARY KEY (sample_set_ID)
);

CREATE TABLE sample(
	sample_ID INT AUTO_INCREMENT,
    sample_set_ID INT,
	sample_name VARCHAR(20),
	sample_material VARCHAR(50),
	sample_comment VARCHAR(2000),
	-- sample_picture MEDIUMBLOB,
	PRIMARY KEY(sample_ID),
    FOREIGN KEY(sample_set_ID) REFERENCES sample_set(sample_set_ID)
);

CREATE TABLE storage(
	storage_ID INT AUTO_INCREMENT,
    sample_ID INT,
    location VARCHAR (200),
    PRIMARY KEY (storage_ID),
    FOREIGN KEY(sample_ID) REFERENCES sample(sample_ID)
);

CREATE TABLE process_equipment(
	prcs_eq_ID INT AUTO_INCREMENT,
    prcs_eq_name VARCHAR(50),
    prcs_eq_acronym VARCHAR(4),
    prcs_eq_comment VARCHAR(2000),
    prcs_eq_active BOOLEAN,
    PRIMARY KEY(prcs_eq_ID)
);

CREATE TABLE process_property(
	prcs_prop_ID INT AUTO_INCREMENT,
    prcs_eq_ID INT NOT NULL,
    prcs_prop_name VARCHAR(50),
    prcs_prop_comment VARCHAR(2000),
    PRIMARY KEY(prcs_prop_ID),
    FOREIGN KEY(prcs_eq_ID) REFERENCES process_equipment(prcs_eq_ID)
);

CREATE TABLE process(
	process_ID INT AUTO_INCREMENT,
    sample_ID INT,
    prcs_prop_ID INT, 
    process_comment VARCHAR(2000),
    PRIMARY KEY(process_ID),
    FOREIGN KEY(sample_ID) REFERENCES sample(sample_ID),
    FOREIGN KEY(prcs_prop_ID) REFERENCES process_property(prcs_prop_ID)
);

CREATE TABLE anlys_equipment(
	anlys_eq_ID INT AUTO_INCREMENT,
    anlys_eq_name VARCHAR(50),
    anlys_eq_comment VARCHAR(2000),
    anlys_eq_active BOOLEAN,
    PRIMARY KEY(anlys_eq_ID)
);

CREATE TABLE anlys_property(
	anlys_prop_ID INT AUTO_INCREMENT,
    anlys_eq_ID INT NOT NULL,
    anlys_prop_name VARCHAR(50),
    anlys_prop_comment VARCHAR(2000),
    PRIMARY KEY(anlys_prop_ID),
    FOREIGN KEY(anlys_eq_ID) REFERENCES anlys_equipment(anlys_eq_ID)
);

CREATE TABLE analysis(
	analysis_ID INT AUTO_INCREMENT,
	sample_ID INT, 
    anlys_prop_ID INT,
    process_ID INT,
    PRIMARY KEY(analysis_ID),
    FOREIGN KEY(sample_ID) REFERENCES sample(sample_ID),
    FOREIGN KEY(anlys_prop_ID) REFERENCES anlys_property(anlys_prop_ID),
    FOREIGN KEY(process_ID) REFERENCES process(process_ID)
);

CREATE TABLE anlys_file(
	anlys_file_ID INT AUTO_INCREMENT,
    analysis_ID INT NOT NULL,
    -- anlys_file MEDIUMBLOB,
    PRIMARY KEY(anlys_file_ID),
    FOREIGN KEY(analysis_ID) REFERENCES analysis(analysis_ID)
);

CREATE TABLE anlys_value(
	anlys_value_ID INT AUTO_INCREMENT,
    analysis_ID INT NOT NULL,
    anlys_value DOUBLE,
    PRIMARY KEY(anlys_value_ID),
    FOREIGN KEY(analysis_ID) REFERENCES analysis(analysis_ID)
);

CREATE TABLE anlys_comment(
	anlys_comment_ID INT AUTO_INCREMENT,
    analysis_ID INT NOT NULL,
    anlys_comment VARCHAR(2000),
    PRIMARY KEY(anlys_comment_ID),
    FOREIGN KEY(analysis_ID) REFERENCES analysis(analysis_ID)
);




    
    
    
	









