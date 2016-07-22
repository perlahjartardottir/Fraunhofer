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
	sample_picture MEDIUMBLOB,
	PRIMARY KEY(sample_ID),
    FOREIGN KEY(sample_set_ID) REFERENCES sample_set(sample_set_ID)
);

ALTER TABLE sample MODIFY sample_name VARCHAR(50);

ALTER TABLE sample_set MODIFY sample_set_name VARCHAR(50);