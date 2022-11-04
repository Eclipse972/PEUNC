-- recherche des noeuds beta orphelins
DROP VIEW IF EXISTS Vue_betaOrphelins;
CREATE VIEW Vue_betaOrphelins AS
SELECT Fils.ID, Fils.ptiNom
FROM Squelette AS Fils
WHERE Fils.beta > 0 AND Fils.gamma = 0 -- Fils est un noeud beta
AND NOT EXISTS (
	SELECT *
	FROM Squelette AS Pere
	WHERE Pere.beta = 0 AND Pere.gamma = 0	-- Père est un noeud alpha
		AND Pere.alpha = Fils.alpha			-- lien de parenté
)
ORDER BY Fils.ID;
