# génère la totalité des routes valides du site avec la méthode de la requête et la liste des paramètres autorisés au format JSON
DROP VIEW IF EXISTS Vue_Routes;
CREATE VIEW Vue_Routes AS
SELECT
	ID,
	alpha AS niveau1,
	beta AS niveau2,
	gamma AS niveau3,
	methode AS methodeHttp,
	CONCAT(
		# niveau alpha
		IF((SELECT niveau2) = 0 AND (SELECT niveau3) = 0,
			CONCAT('/',(SELECT ptiNom FROM Squelette WHERE alpha = (SELECT niveau1) AND beta = 0 AND gamma = 0 AND methode = (SELECT methodeHttp))),
			''
		),

		# niveau beta
		IF((SELECT niveau2) > 0 AND (SELECT niveau3) = 0,
			CONCAT( '/',(SELECT ptiNom FROM Squelette WHERE alpha = (SELECT niveau1) AND beta = 0 AND gamma = 0 AND methode = 'GET'),
					'/',(SELECT ptiNom FROM Squelette WHERE alpha = (SELECT niveau1) AND beta = (SELECT niveau2) AND gamma = 0 AND methode = (SELECT methodeHttp))
				),
			''
		),

		# niveau gamma
		IF((SELECT niveau2) > 0 AND (SELECT niveau3) > 0,
			CONCAT( '/',(SELECT ptiNom FROM Squelette WHERE alpha = (SELECT niveau1) AND beta = 0 AND gamma = 0 AND methode = 'GET'),
					'/',(SELECT ptiNom FROM Squelette WHERE alpha = (SELECT niveau1) AND beta = (SELECT niveau2) AND gamma = 0 AND methode = 'GET'),
					'/',(SELECT ptiNom FROM Squelette WHERE alpha = (SELECT niveau1) AND beta = (SELECT niveau2) AND gamma = (SELECT niveau3) AND methode = (SELECT methodeHttp))
				),
			''
		)
	) AS URL,
	paramAutorise
FROM Squelette
ORDER BY niveau1, niveau2, niveau3, methodeHttp
