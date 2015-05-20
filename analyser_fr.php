<?php
//Fonction d'analyse du contenu d'une page pour en extraire la description, le titre et l'image
function analyse_source_page($source)
{
	//Préparation de l'analyse
	$titre_page = null;
	$description_page = null;
	$image_page = null;
		

	//Analyse des données
	preg_match_all('#<meta (.*?)>#is', $source, $resultat,PREG_PATTERN_ORDER);
	
	//Traitement
	$liste_types = array(
		array('titre' => 'titre_page', 'masque' => '<property="og:title">', 'debut' => 'content="', 'fin' => '"'),
		array('titre' => 'titre_page', 'masque' => "<property='og:title'>", 'debut' => "content='", 'fin' => "'"),
		array('titre' => 'description_page', 'masque' => "<property='og:description'>", 'debut' => "content='", 'fin' => "'"),
		array('titre' => 'description_page', 'masque' => '<property="og:description">', 'debut' => 'content="', 'fin' => '"'),
		array('titre' => 'image_page', 'masque' => '<property="og:image">', 'debut' => 'content="', 'fin' => '"'),
		array('titre' => 'image_page', 'masque' => "<property='og:image'>", 'debut' => "content='", 'fin' => "'"),
	);
	
	//Parcours de la liste
	foreach($resultat[1] as $traiter)
	{
		//Parcours des types de traitement
		foreach($liste_types as $infos_traitement)
		{
			//Pré-traitement			
			$traiter = str_replace(' =', '=', $traiter);
			
			//Vérification de la présence des informations
			if(preg_match($infos_traitement['masque'], $traiter))
			{
				$debut = $infos_traitement['debut'];
				$fin = $infos_traitement['fin'];
				$titre = $infos_traitement['titre'];
				$contenu = strstr($traiter, $debut);
				$contenu = str_replace($debut, '', $contenu);
				$contenu = strstr($contenu, $fin, true);
				${$titre} = $contenu;
			}
		}
	}
	
	//Renvoi du résultat
	return array('titre' => $titre_page, 'description' => $description_page, 'image' => $image_page);
}

$source = file_get_contents("http://www.01net.com/editorial/655088/decouvrez-fove-le-premier-casque-de-realite-virtuelle-pilote-avec-les-yeux/");
$infos_page = analyse_source_page($source);

echo "<pre>";
echo "<h1>".$infos_page['titre']."</h1>\n";
echo "<i>".$infos_page['description']."</i> \n";
echo "<img src='".$infos_page['image']."' /> \n";
