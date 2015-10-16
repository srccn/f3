<?php
$f3 = require('../fatfree-master/lib/base.php');
$f3->config('config/config.ini');
$f3->config('config/route.ini');
$f3->set('ONERROR',
		function($f3) {
			// custom error handler code goes here
			// use this if you want to display errors in a
			// format consistent with your site's theme
			echo $f3->get('ERROR.text');
		}
		);
$f3->run();
