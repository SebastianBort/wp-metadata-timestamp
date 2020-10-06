<?php  
/*
Plugin Name: Znacznik czasowy dla metadanych oraz opcji 
Version: 1.0.0
Description: Zapisuje w bazie datę ostatniej edycji metadanych takich jak własne pola oraz ustawień Wordpress, zapis daty odbywa się na warstwie silnika bazodanowego (MySQL).
Author: Sebatian Bort
*/

class WP_Metadata_Timestamp {          
      
      const table_col = 'last_update';
      private $tables = [
          'postmeta',
          'termmeta',
          'usermeta',
          'options'    
      ];
      
      public function __construct() {
          register_activation_hook(__FILE__, [$this, 'add_timestamp_cols']);
          register_deactivation_hook(__FILE__, [$this, 'remove_timestamp_cols']);
      }
      
      public function add_timestamp_cols() {
          
          global $wpdb;
          foreach($this->tables AS $table) {
              
              $sql = sprintf(
                    'ALTER TABLE `%s%s` ADD `%s` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;',
                    $wpdb->prefix, $table, self::table_col
              );
              $wpdb->query($sql);          
          } 
      }
      
      public function remove_timestamp_cols() {
          
          global $wpdb;
          foreach($this->tables AS $table) {
              
              $sql = sprintf(
                    'ALTER TABLE `%s%s` DROP `%s`',
                    $wpdb->prefix, $table, self::table_col
              );  
              $wpdb->query($sql);          
          } 
      }
} 

new WP_Metadata_Timestamp();
  
?>