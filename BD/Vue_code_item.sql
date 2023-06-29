-- génère le code html qui agrège image+texte pour tous les noeud de l'arborescence
DROP VIEW IF EXISTS Vue_code_item;
CREATE VIEW Vue_code_item AS
SELECT
	alpha,
	beta,
	gamma,
	(SELECT URL FROM Vue_URLvalides WHERE niveau1 = alpha AND niveau2 = beta AND niveau3 = gamma) AS URL,
	imageMenu AS image,
	titre AS texte,
	CONCAT(
		'<a href=',(SELECT URL),'>',
		IF(imageMenu = '','',CONCAT('<img src=/images/',imageMenu,' alt="',titre,'">')), #-- code de l'image si elle est définie
		titre,'</a>'
	) AS code
FROM Squelette
WHERE methodeHttp = 'GET'
ORDER BY alpha ASC, beta ASC, gamma ASC
