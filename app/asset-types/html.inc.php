<?php

Class Html extends Asset {

  static $identifiers = array('html', 'htm', 'php');

  function __construct($file_path) {
    # create and store data required for this asset
    parent::__construct($file_path);
    # create and store additional data required for this asset
    $this->set_extended_data($file_path);
  }

  function set_extended_data($file_path) {
    if(is_readable($file_path)) {
      ob_start();
      include $file_path;
      global $current_page_template_file;

      if(preg_match('/\.(xml|rss|rdf|atom)$/', $current_page_template_file)) {
        $content = PageData::html_to_xhtml(ob_get_contents());
      } else {
        $content = ob_get_contents();
      }

      $file_path = preg_replace('/\/[^\/]+\.html?$/', '', $file_path);

      $relative_path = preg_replace('/^\.\//', Helpers::relative_root_path(), $file_path);

      $content = preg_replace('/\@path\/?/', $relative_path.'/', $content);

      $this->data['@content'] = $content;
      ob_end_clean();
    } else {
      $this->data['@content'] = '';
    }
  }

}

?>