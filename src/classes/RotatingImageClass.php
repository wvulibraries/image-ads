<?php 
    class RotatingImage {
     
        private $db; 
        private $engine; 
        private $localvars; 
        
         public function __construct() {
            $this->engine    = EngineAPI::singleton();
		    $this->localvars = localvars::getInstance();
		    $this->db        = db::get($this->localvars->get('dbConnectionName'));
        }
    }
?>
