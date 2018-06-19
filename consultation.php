<?php
/*
  Template Name: Consultation

  NANAdldbj
  
 */
// récupération des paramétres de page
$id_site = ($wp_query->query_vars['site']) ? $wp_query->query_vars['site'] : 0;
$id_type = ($wp_query->query_vars['typecons']) ? $wp_query->query_vars['typecons'] : 0;
$id_page = "consultation" ;

// Traite les données POST pour éviter un double POST
// Obligatoire avant tout code HTML
echo do_shortcode('[eviter_double_POST site=' . $id_site . ' type=' . $id_type . ' page=' . $id_page . ']');

// Récupère l'entéte de la page du thème (fonction wordpress)
get_header();

?>

<section id="smlk-dashboard">
    <header>
            <!--center><img src="/wp-content/themes/twentyfifteen-child/images/logos/logo_5.jpg" border="0" width="232" height="60"></center-->
		<!--div id="smlk-image" style="background-image: url(/illustration/<?php //echo $id_site; ?>/);"-->
		<div id="smlk-image" style="background-image: url(/wp-content/themes/twentyfifteen-child/images/photos_site/photo_site_<?php echo $id_site; ?>.png);">

			<!--img src="/wp-content/themes/twentyfifteen-child/images/photos_site/photo_site_3.png"-->

        </div>
        <div id="smlk-address">

            <?php echo do_shortcode('[infos_entete site=' . $id_site . ']'); ?>

        </div>
        <div id="smlk-settings">
          <?php echo do_shortcode('[parametres_site site=' . $id_site . ']'); ?>
        </div>
    </header>

    <div id="types_mesures">

        <?php
        echo do_shortcode('[types_mesures site=' . $id_site . ']');
        ?>

    </div>

    <aside>
        <div id="smlk-date" class="smlk-env-info"><span class="smlk-dom"><?php echo date_i18n('d'); ?></span> <span class="smlk-dow"><?php echo date_i18n('l'); ?></span> <span class="smlk-month"><?php echo date_i18n('F'); ?></span></div>
        <div id="smlk-temp-ext" class="smlk-env-info"><span class="smlk-temp"><?php echo do_shortcode('[text_site  site=' . $id_site . ']'); ?></span> <span class="smlk-unit">°C</span> <span class="smlk-desc">Température<br>extérieure site</span></div>
        <div id="smlk-temp-int" class="smlk-env-info"><span class="smlk-temp"><?php echo do_shortcode('[tamb_site  site=' . $id_site . ']'); ?></span> <span class="smlk-unit">°C</span> <span class="smlk-desc">Température<br>intérieure site</span></div>
    </aside>
</section>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <article id="smlk-graphique" class="graphique">

            <header class="graphique-header">
                <?php
                echo do_shortcode('[consult_header site=' . $id_site . ' type=' . $id_type . ']');
                ?>
            </header><!-- .graphique-header -->

            <div class="graphique-content">

                <?php
                if ($id_type === 'MesuresInstant')
                {
                  echo do_shortcode('[mesures_instant site=' . $id_site . ']');
                }
                elseif ($id_type === 'Etats')
                {
                  echo do_shortcode('[etats site=' . $id_site . ']');
                }
                elseif ($id_type === 'Alarmes')
                {
                  echo do_shortcode('[alarmes site=' . $id_site . ']');
                }
                elseif ($id_type === 'Schema')
                {
                  echo do_shortcode('[schema site=' . $id_site . ']');
                }
                elseif ($id_type === 'Commande')
                {
                  echo do_shortcode('[commande site=' . $id_site . ']');
                }
                elseif ($id_type === 'Parametres')
                {
                  echo do_shortcode('[parametres_modif site=' . $id_site . ']');
				  if  ($smlk['grade']->grade_id <= 3) {
					echo do_shortcode('[ninja_form id=10]');
				  }
                }
				elseif ($id_type === 'ZonesModifs')
                {
                  echo do_shortcode('[zones_modifs site=' . $id_site . ']');
                }
                elseif ($id_type === 'StationsModifs')
                {
                  echo do_shortcode('[stations_modifs site=' . $id_site . ']');
                }
                elseif ($id_type === 'SondesModifs')
                {
                  echo do_shortcode('[sondes_modifs site=' . $id_site . ']');
                }
                elseif ($id_type === 'AlarmesModifs')
                {
                  echo do_shortcode('[alarmes_modifs site=' . $id_site . ']');
                }
                elseif ($id_type === 'AstreintesModifs')
                {
                  echo do_shortcode('[astreintes_modifs site=' . $id_site . ']');
                }
				elseif ($id_type === 'RapportEdition')
                {
                  echo do_shortcode('[rapport_edition site=' . $id_site . ']');
                }
                elseif ($id_type === 'P1')
                {
                  echo do_shortcode('[amcharts id="' . $id_type . '"]');
				  echo do_shortcode('[affichage_P1="' . $id_type . '"]');
                }
				else
                {
                  $id_zone = (isset($smlk['id_zone'])) ? $smlk['id_zone'] : 0;
                  $id_entree = (isset($smlk['id_entree'])) ? $smlk['id_entree'] : 0;
                  $granularity = (isset($smlk['granularity'])) ? $smlk['granularity'] : 0;

//                    echo do_shortcode('[consult_chart
//                      site=' . $id_site . '
//                      type=' . $id_type . '
//                      zone=' . $id_zone . '
//                      sonde=' . $id_entree . '
//                      granularity=' . $granularity . ']');

                  echo do_shortcode('[amcharts id="' . $id_type . '"]');
                }
                ?>

            </div><!-- .graphique-content -->

            <footer class="graphique-footer">
            </footer><!-- .graphique-footer -->

        </article><!-- #graphique -->

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
