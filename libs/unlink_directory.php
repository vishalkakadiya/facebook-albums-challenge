<?php

class unlink_directory {

    function remove_directory($directory) {
        foreach (glob("{$directory}/*") as $file) {
            if ( is_dir( $file ) ) { 
                remove_directory($file);
        		} else {
        			unlink($file);
        		}
        }
        rmdir($directory);
    }

}

?>
