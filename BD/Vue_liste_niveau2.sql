# génère la liste de toutes les URL de niveau 2
DROP VIEW IF EXISTS Vue_liste_niveau2;
CREATE VIEW Vue_liste_niveau2 AS
SELECT
	alpha,
	beta AS i,
	URL,
	image,
	texte
FROM Vue_code_item
WHERE alpha >=0 AND beta>0 AND gamma=0
ORDER BY alpha ASC, beta ASC
