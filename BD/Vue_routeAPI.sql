DROP VIEW IF EXISTS Vue_routeAPI;
CREATE VIEW Vue_routeAPI AS
SELECT alpha, beta, gamma,
CONCAT('/',
    -- niveau alpha
	(SELECT ptiNom 
        FROM Squelette AS T1 
        WHERE T1.alpha = Squelette.alpha AND T1.beta=0 AND T1.gamma=0 
            AND T1.methodeHttp='GET' AND site='API'),
    -- niveau beta
	IFNULL((SELECT CONCAT('/',ptiNom) 
            FROM Squelette AS T2 
            WHERE T2.alpha = Squelette.alpha AND T2.beta = Squelette.beta 
                AND T2.beta > 0 AND T2.gamma = 0 AND T2.methodeHttp = 'GET' AND site='API')
        ,''),
    -- niveau gamma
	IFNULL((SELECT CONCAT('/',ptiNom) 
        FROM Squelette AS T3 
        WHERE T3.alpha = Squelette.alpha AND T3.beta = Squelette.beta AND T3.gamma = Squelette.gamma 
            AND T3.beta>0 AND gamma>0 AND T3.methodeHttp='GET' AND site='API')
        , '')
) AS URL,
methodeHttp, titre, paramAutorise, controleur, methodeControleur
FROM Squelette
WHERE site='API'
ORDER BY alpha,beta,gamma;
