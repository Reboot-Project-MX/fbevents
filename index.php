<?php

/*
  Plugin Name: Fb Events
  Description: Tareas 
  Author: Reboot Project
  Version: 0.1.1
  Author URI: https://www.rebootproject.mx
 */
include_once "FacebookEvents.php";

// use FacebookEvents;

/**
 * actions
 * @version 0.1
 * Verifica si existe la tarea en la cola de cronjobs de wp o la agrega cada hora
 */
add_action('woocommerce_thankyou', array('FacebookEvents', 'sendPurchase'));
add_action('woocommerce_after_single_product', array('FacebookEvents', 'sendContentView'));
add_action('the_post', array('FacebookEvents', 'sendPageView'));
