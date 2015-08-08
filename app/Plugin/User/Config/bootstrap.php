<?php

//define in the rights
define('USER_FUNC_ACTIVE', 0);
define('USER_FUNC_DISABLE', 1);
include_once(__DIR__ . '/rights.php');
Configure::write('User.UserRight', $rights);

include_once(__DIR__ . '/models.php');
Configure::write('User.Models', $models);

//admin status
define('USER_ADMIN_ACTIVE', 0);
define('USER_ADMIN_DISABLE', 1);
Configure::write('User.AdminStatus', array(USER_ADMIN_ACTIVE => __('Active'), USER_ADMIN_DISABLE => __('Disable')));

//user status
define('USER_ACTIVE', 0);
define('USER_INACTIVE', -1);
define('USER_DISABLE', 1);
Configure::write('User.UserStatus', array(USER_ACTIVE => __('Active'), USER_DISABLE => __('Disable')));

//define data access
define('USER_DATA_INCLUDE', 0);
define('USER_DATA_EXCLUDE', 1);
Configure::write('User.UserData', array(USER_DATA_INCLUDE => __('Include'), USER_DATA_EXCLUDE => __('Exclude')));

//
define('USER_MIN_PASSWORD_LENGTH', 6);

//define default role
define('USER_ROLE_ANONYM', 1);
define('USER_ROLE_REGISTER_DEFAUT', 2);

define('USER_AUTO_ACTIVE', 1);
define('USER_TOKEN_EXPIRE', 180);

//Login with Open Auth config
// http://dev.seamiq.com 
define("FACEBOOK_LOGIN_APPID", "1393061170976671");
define("FACEBOOK_LOGIN_SECRET", "917b58e15a9a21dfc112815c8cf7b261");
define("FACEBOOK_LOGIN_REDIRECT_URI", "http://dev.seamiq.com/oauth/fbLogin");
define("FACEBOOK_LOGIN_PROVIDER", "facebook");

define("GOOGLE_LOGIN_APPID", "354352878432-rq60nhkiso1dhvniq5090ecaeoc5l645.apps.googleusercontent.com");
define("GOOGLE_LOGIN_SECRET", "62Pk9aRgK3yl5FvsPn244FS6");
define("GOOGLE_LOGIN_REDIRECT_URI", "http://dev.seamiq.com/oauth/googleLogin");
define("GOOGLE_LOGIN_PROVIDER", "google");

define('TWITTER_API_KEY', 'DxOsRQLj84S4lPjGYemeMkdjB');
define('TWITTER_API_SECRET', 'eYfbQeMM0BJSNMJmOKFZQQJKnXRxhu9TivKUdPSlsjIsg6VPLd');
define('TWITTER_OAUTH_CALLBACK', 'http://dev.seamiq.com/oauth/twitterLogin');
define("TWITTER_LOGIN_PROVIDER", "twitter");

//http://it.seamiq.com
/*
define("FACEBOOK_LOGIN_APPID", "270345876504982");
define("FACEBOOK_LOGIN_SECRET", "fe4fc203202d44d1cef05d3fb13a8ac0");
define("FACEBOOK_LOGIN_REDIRECT_URI", "http://it.seamiq.com/oauth/fbLogin");
define("FACEBOOK_LOGIN_PROVIDER", "facebook");

define("GOOGLE_LOGIN_APPID", "354352878432-r1pgfij14r8fu9bjdmbvg7e69lte7cre.apps.googleusercontent.com");
define("GOOGLE_LOGIN_SECRET", "0eojhqfjfznHbuftp-HV8wBw");
define("GOOGLE_LOGIN_REDIRECT_URI", "http://it.seamiq.com/oauth/googleLogin");
define("GOOGLE_LOGIN_PROVIDER", "google");

define('TWITTER_API_KEY', 'fNN7HxBZaX5C0bD3VR7fb3mxs');
define('TWITTER_API_SECRET', 'sL3bkLvrDTPvN9tL8JfEgnjeIsNObFTALzqbKvx5VkBYukZELg');
define('TWITTER_OAUTH_CALLBACK', 'http://it.seamiq.com/oauth/twitterLogin');
define("TWITTER_LOGIN_PROVIDER", "twitter");
 */