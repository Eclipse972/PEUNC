-- ptinom doit respecter le motif d'une URL
DROP VIEW IF EXISTS Vue_ptiNomInvalides;
CREATE VIEW Vue_ptiNomInvalides AS
SELECT ID, ptiNom
FROM Squelette
WHERE NOT(ptiNom REGEXP("^[a-zA-Z0-9][a-zA-Z_0-9_-]+$"))
-- Commence par une caractère non accentué ou un chiffre suivi de la même chose avec en plus les caractères - et _
ORDER BY ID;
