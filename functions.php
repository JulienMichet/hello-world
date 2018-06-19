<?php

/* * **************
 * Initialisation
 */

/*
 * Retirer/remplacer/ajouter des JS
 */
//update_option ( 'siteurl', 'https://sem-link.eu' );

//update_option ( 'home', 'https://sem-link.eu' );

function semlink_script_fix()
{
  wp_dequeue_script('twentyfifteen-script');
  wp_enqueue_script('semlink-script', get_stylesheet_directory_uri() . '/js/functions.js', array('jquery'), 20170427, true);
}

add_action('wp_enqueue_scripts', 'semlink_script_fix');

/**
 * Récupérer $_GET['site'] avec réécriture d'URL /consultation/{id}
 * 
 * @global type $wp_rewrite
 */
function semlink_add_rewrite()
{
  global $wp_rewrite;
//  add_rewrite_tag('%illustration%', '([^&]+)');
  add_rewrite_tag('%site%', '([^&]+)');
  add_rewrite_tag('%typecons%', '([^&]+)');
  $wp_rewrite->add_rule('consultation/([^/]+)/([^/]+)', 'index.php?pagename=consultation&site=$matches[1]&typecons=$matches[2]', 'top');
  $wp_rewrite->add_rule('illustration/([^/]+)', 'index.php?pagename=illustration&site=$matches[1]', 'top');

  $wp_rewrite->flush_rules();
}

add_action('init', 'semlink_add_rewrite');

/**
 * Récupérer des données en fonction de l'utilisateur et les stocker dans un tableau $smlk pour la page consultation
 * 
 * @global type 
 */
function semlink_init_consultation()
{
  $smlk = array();

  global $wpdb, $smlk;

  $smlk['user'] = wp_get_current_user();

  // récuperer le grade de l'utilisateur
  $sql = "SELECT grade_id, mail FROM individu WHERE mail = '" . $smlk['user']->data->user_email . "'";
  $smlk['grade'] = $wpdb->get_row($sql);
  
  //récupérer les informations de l'entité
  $sql = "SELECT e.*
    FROM entite e
    JOIN individu i ON i.entite_id = e.id
    WHERE i.mail = '" . $smlk['user']->data->user_email . "'
    LIMIT 1";
  $smlk['entite'] = $wpdb->get_row($sql);

  // récupérer les informations du site
  /*$sql = "SELECT s.*
    FROM `site` s
    JOIN entite_site es ON es.site_id = s.id
    JOIN entite e ON e.id = es.entite_id
    JOIN individu i ON i.entite_id = e.id
    WHERE s.deleted_at is null AND i.mail = '" . $smlk['user']->data->user_email . "'";*/
  $sql = "SELECT s.*
    FROM `site` s
    JOIN individu_site ins ON ins.site_id = s.id
    JOIN individu i ON i.id = ins.individu_id
    WHERE s.deleted_at is null AND i.mail = '" . $smlk['user']->data->user_email . "';";	
  $smlk['sites'] = $wpdb->get_results($sql);
	
   // si le grade est "Exploitant" ou supérieur
	if  ($smlk['grade']->grade_id <=3) {
		$smlk['types_mesures'] = array(
			'Tamb' => array('nom' => 'Température ambiante unitaire'),
			'TambZone' => array('nom' => 'Température ambiante zone'),
			'Compteurs' => array('nom' => 'Compteurs'),
			'RepartConso' => array('nom' => 'Répartition consommation'),
			//'DJU' => array('nom' => 'DJU'),
			//'Text' => array('nom' => 'Température extérieure'),
			'Tcompare' => array('nom' => 'Température comparaison'),
			
			'Schema' => array('nom' => 'Schéma de principe'),
			'Tsuperposition' => array('nom' => 'Toutes les températures'),
			'P1' => array('nom' => 'P1'),
			'Alarmes' => array('nom' => 'Liste des alarmes'),
			'Etats' => array('nom' => 'Visualisation des états'),
			
			
			'Commande' => array('nom' => 'Pilotage des equipements'),
			//'Planning' => array('nom' => 'Planning'),
			'Tfonc' => array('nom' => 'Température de fonctionnement'),
			'Impulsions' => array('nom' => 'Impulsions'),
			'MesuresInstant' => array('nom' => 'Visualisation des valeurs mesurées'),
			'Parametres' => array('nom' => 'Paramétrage'),
			
			//'SondesModifs' => array('nom' => 'Gestion des Sondes'),
			//'ZonesModifs' => array('nom' => 'Gestion des Zones'),
			//'StationsModifs' => array('nom' => 'Gestion des Stations'),
			//'AlarmesModifs' => array('nom' => 'Gestion des Alarmes'),
			//'RapportEdition' => array('nom' => 'Edition de rapport'),

			//'CompteurAnnuel' => array('nom' => 'Compteurs annuels'),
			//'CoeffZannuel' => array('nom' => 'Coefficient Z annuel'),
			//'DJUannuel' => array('nom' => 'DJU annuel'),
			//'Etiquettes' => array('nom' => 'Étiquettes énergie'),
		);
	}
	// si le grade est "Gestionnaire" 
	elseif  ($smlk['grade']->grade_id == 4) {
		$smlk['types_mesures'] = array(
			'Tamb' => array('nom' => 'Température ambiante unitaire'),
			'TambZone' => array('nom' => 'Température ambiante zone'),
			'Compteurs' => array('nom' => 'Compteurs'),
			'RepartConso' => array('nom' => 'Répartition consommation'),
			'Tcompare' => array('nom' => 'Température comparaison'),
			
			'Schema' => array('nom' => 'Schéma de principe'),
			'Tsuperposition' => array('nom' => 'Toutes les températures'),
			'P1' => array('nom' => 'P1'),
			'Alarmes' => array('nom' => 'Liste des alarmes'),
			'Etats' => array('nom' => 'Visualisation des états'),
		);
	}
	// si le grade est simple utilisateur
	else {
			$smlk['types_mesures'] = array(
			'Tamb' => array('nom' => 'Température ambiante unitaire'),
			'TambZone' => array('nom' => 'Température ambiante zone'),
			'Compteurs' => array('nom' => 'Compteurs'),
			'RepartConso' => array('nom' => 'Répartition consommation'),
			'Tcompare' => array('nom' => 'Température comparaison'),
		);
	}
}

add_action('init', 'semlink_init_consultation');

function remove_wp_logo() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}
add_action( 'wp_before_admin_bar_render', 'remove_wp_logo' );


/* * *******
 * Widgets
 */

require(get_stylesheet_directory() . '/includes/widgets.php');

/* * **********
 * Shortcodes affichage dans consultation
 */

require(get_stylesheet_directory() . '/includes/shortcodes.php');

/* * ************
 * Web services
 */

require(get_stylesheet_directory() . '/includes/web-services.php');


/* * ************
 * Utilisateurs Semlink (individus)
 */

require(get_stylesheet_directory() . '/includes/utilisateurs.php');

/* * **********
 * gestionPOST
 */

require(get_stylesheet_directory() . '/includes/gestionPOST.php');

/* * **********
 * Shortcodes Paramétres globaux (entités,utilisateurs, création de site)
 */

require(get_stylesheet_directory() . '/includes/shortcodes-parametres.php');

/* * **********
 * Shortcodes config du downlink pour l'envoi des paramétrages au SEMCONTROL 
 */

require(get_stylesheet_directory() . '/includes/shortcodes-configDownlink.php');


/************
 * INUTILISÉ
 */

/*
 * activation theme
 */

//add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
//
//function theme_enqueue_styles()
//{
//  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
//}

/**
 * Récupérer $_GET['site'] sans réécriture d'URL
 * 
 * @param array $qvars
 * @return string
 */
//function semlink_add_query_vars($qvars)
//{
//  $qvars[] = "site";
//  return $qvars;
//}
//
//add_filter('query_vars', 'add_query_vars');

