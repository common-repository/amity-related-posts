<?php 

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit(); }

//All options
$arp_optNames = array('arp_styles', 'arp_basics');

foreach ($arp_optNames as $arp_opt) {
	delete_option($arp_opt);
}

 