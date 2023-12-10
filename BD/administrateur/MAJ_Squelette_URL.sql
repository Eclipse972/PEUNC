-- Maj URL dans la table Squelette
UPDATE Squelette
INNER JOIN Vue_URLvalides ON alpha = niveau1 AND beta = niveau2 AND gamma = niveau3
SET Squelette.URL = Vue_URLvalides.URL
-- Il faudra mettre dans cette requête dans un trigger pour que la MAJ soit automatique à chaque ajout ou modification