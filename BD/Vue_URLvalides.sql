-- Crée la liste de toutes les URL valides. On peut les rentrer dans la barre d'adresse du navigateur
-- Voir le manuel pour plus de précisions
DROP VIEW IF EXISTS Vue_URLvalides;
CREATE VIEW Vue_URLvalides AS
SELECT
	alpha AS niveau1,
	beta AS niveau2,
	gamma AS niveau3,
	(SELECT ptiNom FROM Squelette WHERE alpha=(SELECT niveau1) AND beta=(SELECT niveau2) AND beta>0 AND gamma=0 AND methode='GET') AS texte2,
	(SELECT ptiNom FROM Squelette WHERE alpha=(SELECT niveau1) AND beta=(SELECT niveau2) AND beta>0 AND gamma=(SELECT niveau3) AND gamma>0 AND methode='GET') AS texte3,
	CONCAT('/', (SELECT ptiNom FROM Squelette WHERE alpha = (SELECT niveau1) AND beta=0 AND gamma=0 AND methode='GET'),
			IF(ISNULL((SELECT texte2)),'',CONCAT('/',(SELECT texte2))),
			IF(ISNULL((SELECT texte3)),'',CONCAT('/',(SELECT texte3))) )
	AS URL
FROM Squelette
ORDER BY niveau1, niveau2, niveau3
