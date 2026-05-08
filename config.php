<?php



// SETUP
// Database info
$dbhost = getenv("DB_HOST") ?: "localhost";
$dbuser = getenv("DB_USER") ?: "root";
$dbpass = getenv("DB_PASS") ?: "";
$dbname = getenv("DB_NAME") ?: "rddtsync";



// Base URL
// if on root, make it blank
// otherwise /foldername with no trailing slash
$baseurl = getenv("BASE_URL") !== false ? getenv("BASE_URL") : "/rsync";

// added for password reset emails
// your host no trailing slash
$basehost = getenv("BASE_HOST") ?: "http://localhost";


// API location
$apiloc = getenv("API_LOC") ?: "http://localhost/rsync/api/";


// Pretty URLs. Need server configured properly
$prettyurls = false;

// Debug logging. Set DEBUG=true in environment to enable
$debug = getenv('DEBUG') === 'true';


// For password reset emails
// using smtp servers
$smtpserver = getenv("SMTP_SERVER") ?: "smtp.example.invalid";
$smtpauth_env = getenv("SMTP_AUTH");
$smtpauth = $smtpauth_env === false ? true : filter_var($smtpauth_env, FILTER_VALIDATE_BOOLEAN);
$smtpuser   = getenv("SMTP_USER") ?: "noreply@example.invalid";
$smtppass   = getenv("SMTP_PASS") ?: "change-me";
$smtpenc    = getenv("SMTP_ENC") ?: "ssl";
$smtpport_env = getenv("SMTP_PORT");
$smtpport   = $smtpport_env === false ? 465 : (int)$smtpport_env;

$fromemail = getenv("FROM_EMAIL") ?: "noreply@example.invalid";





$mysql = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if($mysql->connect_errno) {
	echo "database connection failure <!-- ".$mysql->connect_error." -->";
	die;
}

