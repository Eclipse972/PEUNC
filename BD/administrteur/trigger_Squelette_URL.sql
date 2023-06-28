-- Crée ou MAJ du champ URL de la table Squelette
-- DROP TRIGGER Squelette_URL; interdit chez free
CREATE TRIGGER Squelette_URL AFTER UPDATE ON Squelette
FOR EACH ROW
SET URL =(  SELECT Vue_URLvalides.URL
            FROM Vue_URLvalides
            WHERE niveau1 = alpha AND niveau2 = beta AND niveau3 = gamma
        );
