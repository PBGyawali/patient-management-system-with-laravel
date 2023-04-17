<?php
	 // define global constants
    define("BASE_URL", env('APP_URL').'/'); //defines the base url to start from in the server
    define("LOGIN_URL", BASE_URL."login"); //defines the base url to start from in the server
    define("DASHBOARD_URL",  BASE_URL."dashboard");
    define("ASSETS_URL", BASE_URL."public/");
    define("CSS_URL", ASSETS_URL."css/");
    define("JS_URL", ASSETS_URL."js/");
    define("IMAGES_URL",config('app.storage_url'));
    define("FONTS_URL", ASSETS_URL."font/");

    define ("BASE_PATH", realpath(dirname(__FILE__)).'/'); //starts the path from where the config.php file is located
    define("ASSETS_DIR", BASE_PATH."public");
    define("IMAGES_DIR", ASSETS_DIR."images/");
    define("FONTS_DIR", ASSETS_DIR."font/");
    define("ALLOWED_IMAGES", array("jpg", "jpeg", "png"));
    define("SITE_NAME", env('APP_NAME'));

?>
