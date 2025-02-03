<?php
if (function_exists('tmpfile')) {
    echo 'xxxxtmpfile() function is available.';
} else {
    echo 'yyyytmpfile() function is not available.';
}
?>