# génère la liste de toutes les URL de niveau 1
DROP VIEW IF EXISTS Vue_liste_niveau1;
CREATE VIEW Vue_liste_niveau1 AS
SELECT
	alpha AS i,
	URL,
	image,
	texte
FROM Vue_code_item
WHERE alpha >=0 AND beta=0 AND gamma=0
ORDER BY alpha ASC
