CREATE TABLE plants (
id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
name TEXT NOT NULL,
sci_name TEXT NOT NULL UNIQUE,
pp_id TEXT NOT NULL UNIQUE,
exploratory_constructive INTEGER NOT NULL,
exploratory_sensory INTEGER NOT NULL,
physical INTEGER NOT NULL,
imaginative INTEGER NOT NULL,
restorative INTEGER NOT NULL,
expressive INTEGER NOT NULL,
play_with_rules INTEGER NOT NULL,
bio INTEGER NOT NULL,
hardiness_level TEXT NOT NULL
);

CREATE TABLE tags (
id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
name TEXT NOT NULL UNIQUE
);

CREATE TABLE entry_tags (
id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
plant_id INTEGER NOT NULL,
tag_id INTEGER NOT NULL,
FOREIGN KEY (plant_id) REFERENCES plants(id),
FOREIGN KEY (tag_id) REFERENCES tags(id)
);

CREATE TABLE users (
id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
username TEXT NOT NULL UNIQUE,
password TEXT NOT NULL
);

--insert plants
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (1, "3 Sisters-Corn", "Red Mohawk Corn", "FE_07", 0, 1, 1, 1, 1, 0, 1, 0, "4-9");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (2, "American Groundnut", "Apius americana", "VI_05", 0, 1, 1, 1, 0, 0, 0, 1, "5-9");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (3, "Common Nasturtiums", "Tropaeolum (group)", "VI_01", 0, 1, 0, 1, 0, 0, 0, 1, "2-10");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (4, "Downy skullcap", "Scutellaria incana", "FL_28", 0, 1, 0, 1, 0, 0, 0, 1, "5-8");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (5, "White Turtlehead", "Chelone glabra", "FL_10", 0, 1, 0, 1, 0, 0, 0, 1, "3-8");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (6, "Blue Grama Grass", "Bouteloua gracilis", "GA_12", 1, 1, 1, 0, 0, 0, 0, 1, "3-10");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (7, "Feather Moss", "Hypnum imponens", "FE_16", 0, 1, 0, 1, 1, 0, 0, 1, "4-8");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (8, "Cattails", "Typha latifolia", "GA_14-W", 1, 1, 1, 1, 1, 0, 0, 1, "3-10");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (9, "American Persimmon", "Diospyros virginiana", "TR_14", 1, 1, 1, 0, 0, 0, 0, 1, "4-9");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (10, "Northern Bayberry", "Myrica pensylvanica", "SH_04", 0, 1, 1, 0, 1, 0, 1, 1, "4-9");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (11, "Russian Sage", "Perovskia atriplicifolia", "FL_18", 0, 1, 0, 0, 0, 0, 0, 1, "5-9");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (12, "Chinese Chestnuts", "Castanea mollissima", "TR_16", 1, 1, 1, 1, 1, 0, 0, 1, "4-8");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (13, "Cranesbill", "Geranium maculatum", "FL_04", 0, 1, 0, 0, 0, 0, 0, 1, "4-9");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (14, "Jack-in-the-Pulpit", "Arisaema triphyllum", "FL_36", 0, 1, 1, 1, 0, 0, 0, 1, "4-9");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (15, "Dappled Willow", "Salix integra 'Hakuro Nishiki", "SH_08", 0, 1, 1, 1, 1, 0, 1, 1, "4-9");
INSERT INTO plants (id, name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (16, "Sensitive Fern", "Onodea sensibilis", "FE_03", 0, 1, 0, 0, 1, 0, 0, 0, "4-8");

--insert tags
INSERT INTO tags (id, name) VALUES (1, 'Growth: Perennial');
INSERT INTO tags (id, name) VALUES (2, 'Growth: Annual');
INSERT INTO tags (id, name) VALUES (3, 'Sun: Full Sun');
INSERT INTO tags (id, name) VALUES (4, 'Sun: Partial Shade');
INSERT INTO tags (id, name) VALUES (5, 'Sun: Full Shade');
INSERT INTO tags (id, name) VALUES (6, 'Classification: Shrub');
INSERT INTO tags (id, name) VALUES (7, 'Classification: Grass');
INSERT INTO tags (id, name) VALUES (8, 'Classification: Vine');
INSERT INTO tags (id, name) VALUES (9, 'Classification: Tree');
INSERT INTO tags (id, name) VALUES (10, 'Classification: Flower');
INSERT INTO tags (id, name) VALUES (11, 'Classification: Groundcover');
INSERT INTO tags (id, name) VALUES (12, 'Classification: Other');

--insert entry tags
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (1, 2, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (2, 4, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (3, 5, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (4, 6, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (5, 8, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (6, 9, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (7, 10, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (8, 11, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (9, 12, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (10, 13, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (11, 14, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (12, 16, 1);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (13, 1, 2);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (14, 3, 2);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (15, 1, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (16, 2, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (17, 3, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (18, 4, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (19, 6, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (20, 8, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (21, 9, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (22, 11, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (23, 12, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (24, 13, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (25, 15, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (26, 16, 3);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (27, 2, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (28, 4, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (29, 5, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (30, 7, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (31, 8, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (32, 9, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (33, 10, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (34, 12, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (35, 13, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (36, 14, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (37, 15, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (38, 16, 4);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (39, 7, 5);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (40, 14, 5);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (41, 16, 5);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (42, 10, 6);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (43, 15, 6);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (44, 6, 7);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (45, 8, 7);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (46, 2, 8);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (47, 3, 8);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (48, 9, 9);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (49, 12, 9);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (50, 4, 10);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (51, 5, 10);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (52, 11, 10);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (53, 13, 10);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (54, 14, 10);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (55, 1, 12);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (56, 7, 12);
INSERT INTO entry_tags (id, plant_id, tag_id) VALUES (57, 16, 12);
