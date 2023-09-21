DROP VIEW IF EXISTS Vue_route;
CREATE VIEW Vue_route AS
SELECT alpha, beta, gamma,
/**
 * Construction de l'URL
 * Cette requete ne tient compte que des noeuds avec une methodeHttp=GET ppour calculer l'URL.
 * L'URL d'un neud POST sera quant même trouvé s'il existe un noeud GET avec le même triplet alpha-beta-gamma.
 * Exemple:
 * le formulaire de contact est le noeud 5-1-0-GET. Le traitement de ce formulaire qui est 
 * le noeud 5-1-0-POST sera quant même trouvé.
 **/
CONCAT('/',
    -- niveau alpha
	(SELECT ptiNom 
        FROM Squelette AS T1 
        WHERE T1.alpha = Squelette.alpha AND T1.beta=0 AND T1.gamma=0 
            AND T1.methodeHttp='GET' AND T1.site='site'),
    -- niveau beta
	IFNULL((SELECT CONCAT('/',ptiNom) 
            FROM Squelette AS T2 
            WHERE T2.alpha = Squelette.alpha AND T2.beta = Squelette.beta 
                AND T2.beta > 0 AND T2.gamma = 0 AND T2.methodeHttp = 'GET' AND T2.site='site')
        ,''),
    -- niveau gamma
	IFNULL((SELECT CONCAT('/',ptiNom) 
        FROM Squelette AS T3 
        WHERE T3.alpha = Squelette.alpha AND T3.beta = Squelette.beta AND T3.gamma = Squelette.gamma 
            AND T3.beta>0 AND gamma>0 AND T3.methodeHttp='GET' AND T3.site='site')
        , '')
) AS URL,
methodeHttp, titre, paramAutorise, controleur, methodeControleur
FROM Squelette
WHERE site='site'
ORDER BY alpha,beta,gamma;
