-- recherche s'il n'y a pas d'URL en doublon dans la liste des URL valides
DROP VIEW IF EXISTS Vue_doublonURL;
CREATE VIEW Vue_doublonURL AS
SELECT VUE1.ID AS "ID1", VUE2.ID AS "ID2", VUE1.URL
FROM Vue_Routes AS VUE1
LEFT OUTER JOIN Vue_Routes AS VUE2 ON VUE2.URL = VUE1.URL
WHERE  VUE1.ID < VUE2.ID -- évite les doublons du style (5;6) et (6;5)
ORDER BY VUE1.ID;
