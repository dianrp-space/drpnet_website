<?php
echo "Current upload_max_filesize: " . ini_get('upload_max_filesize') . PHP_EOL;
echo "Current post_max_size: " . ini_get('post_max_size') . PHP_EOL;
echo "Upload temp dir: " . ini_get('upload_tmp_dir') . PHP_EOL;
echo "Max execution time: " . ini_get('max_execution_time') . PHP_EOL;
echo "PHP ini file location: " . php_ini_loaded_file() . PHP_EOL; 