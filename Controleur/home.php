<?php	// exemple de contreur
$this->setTitle("Titre affiché en haut du navigateur");
$this->setHeaderText("<p>En-tête</p>");
$this->setLogo("logo.png");
$this->setFooter("<p>pied de page</p>");
$this->setView("doctype.html");
$this->setCSS("index");

ob_start();	// début du code <section>
?>
<h1>PEUNC fonctionne!</h1>
<?php
$this->setSection(ob_get_contents());
ob_end_clean();
