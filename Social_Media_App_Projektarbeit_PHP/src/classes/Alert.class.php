<?php
    class Alert {
        /**
         * prints Alert
         * 
         * @param string $msg alert message
         * @param bool $success green if true else red 
         */
        public static function echoAlert($msg, $success) {
            if ($success) {
                echo "<div class=\"alert alert-success alert-dismissible alert-fixed\">";
            } else {
                echo "<div class=\"alert alert-danger alert-dismissible alert-fixed\">";
            }
            echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
            echo $msg;
            echo "</div>";
        }

        /**
         * prints inline alert
         * @param string $msg inline alert message
         */
        public static function inlineAlert($msg) {
            echo '<span class="error mx-2">' . $msg . '</span>';
        }
    }
?>