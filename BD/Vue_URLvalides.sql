-- Crée la liste de toutes les URL valides. On peut les rentrer dans la barre d'adresse du navigateur
-- Voir le manuel pour plus de précisions
DROP VIEW IF EXISTS Vue_URLvalides;
CREATE VIEW Vue_URLvalides AS
SELECT
	alpha AS niveau1,
	beta AS niveau2,
	gamma AS niveau3,
	CONCAT('/',
		-- niveau 1
		(SELECT ptiNom FROM Squelette WHERE alpha = (SELECT niveau1) AND beta=0 AND gamma=0 AND methodeHttp='GET'),
		-- niveau 2
		IFNULL((SELECT CONCAT('/',ptiNom)
				FROM Squelette
				WHERE alpha=(SELECT niveau1) AND beta=(SELECT niveau2) AND beta>0 AND gamma=0 AND methodeHttp='GET'),
			''),
		-- niveau 3
		IFNULL((SELECT CONCAT('/',ptiNom)
				FROM Squelette
				WHERE alpha=(SELECT niveau1) AND beta=(SELECT niveau2) AND beta>0 AND gamma=(SELECT niveau3) AND gamma>0
						AND methodeHttp='GET'),
			'')
	) AS URL
FROM Squelette
WHERE methodeHttp='GET'