<?php
/**
 * File for upgrade directives 
 */

/*
 * Call all code in plugin "install" function
 */

$plugin_name = 'seo-pressor/seo-pressor.php';
deactivate_plugins($plugin_name);
activate_plugin($plugin_name);