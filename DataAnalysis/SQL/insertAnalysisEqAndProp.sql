INSERT INTO anlys_equipment(anlys_eq_name, anlys_eq_active) VALUES
			("Dektak",TRUE), ("Rockwell Hardness Tester", TRUE), ("LaWave", TRUE),
            ("Contact Angle Gonionmeter", TRUE), ("Tribometer", TRUE), ("UV VIS", TRUE),
            ("Calotte Grinder", TRUE), ("AFM", TRUE), ("Stereo Microscope", TRUE),
            ("Nikon Microscope", TRUE), ("Laurane", TRUE), ("XPS", TRUE);

INSERT INTO anlys_property(nlys_prop_name, anlys_eq_ID) VALUES
			("Roughness", 1), ("Roughness", 8), ("Thickness", 1), ("Thickness", 11),
            ("Thickness", 7), ("Thickness", 6), ("Color", 9), ("Color", 10),
			("Adhesion", 2), ("Young's Modulus", 3), ("Contact angle", 4),
            ("Wear rate", 5), ("Coefficient of friction", 5), ("Transparency", 6),
            ("Reflectance", 6), ("Density", 11), ("Atomic composition", 12);