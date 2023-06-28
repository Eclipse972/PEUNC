-- Crée ou MAJ du champ URL de la table Squelette
-- il ne semble possible de créerun trigger mais il semble inactif et pas possible de voir où il est enregistré
DROP TRIGGER Squelette_URL; interdit chez free
DELIMITER $$
CREATE TRIGGER Squelette_URL AFTER UPDATE ON Squelette
FOR EACH ROW
BEGIN
    SET NEW.URL = ( SELECT URL
                    FROM Vue_URLvalides
                    WHERE niveau1 = alpha AND niveau2 = beta AND niveau3 = gamma);
END
DELIMITER ;
