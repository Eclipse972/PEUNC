DROP VIEW IF EXISTS Vue_route;
CREATE VIEW Vue_route AS
SELECT
N.site,
CONCAT('/',
    -- niveau alpha
	(SELECT ptiNom 
        FROM NoeudSquelette AS T1 
        WHERE T1.alpha = N.alpha AND T1.beta=0 AND T1.gamma=0 
            AND T1.site = N.site),
    -- niveau beta
	IFNULL((SELECT CONCAT('/',ptiNom) 
            FROM NoeudSquelette AS T2
            WHERE T2.alpha = N.alpha AND T2.beta = N.beta 
                AND T2.beta > 0 AND T2.gamma = 0 AND T2.site = N.site)
        ,''),
    -- niveau gamma
	IFNULL((SELECT CONCAT('/',ptiNom) 
        FROM NoeudSquelette AS T3 
        WHERE T3.alpha = N.alpha AND T3.beta = N.beta AND T3.gamma = N.gamma 
            AND T3.beta>0 AND T3.gamma>0 AND T3.site = N.site)
        , '')
) AS URL,
N.titre,
M.methodeHttp,
M.paramAutorise,
M.controleur,
M.methodeControleur,
-- utile si on veut connaitre la position pour mettre en surbrillance les items d'un menu
N.alpha,
N.beta,
N.gamma
FROM NoeudSquelette AS N
INNER JOIN MethodeSquelette AS M
ON M.noeudID = N.ID
ORDER BY N.site, N.alpha, N.beta, N.gamma