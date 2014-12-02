<?php

class unlink_directory {

    function remove_directory($directory) {
        foreach (glob("{$directory}/*") as $file) {
            if ( is_dir( $file ) ) { 
                $this->remove_directory($file);
    		} else {
    			unlink($file);
    		}
        }
        rmdir($directory);
    }

}

?>
