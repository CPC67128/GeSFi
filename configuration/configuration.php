<?php
/* ================================
 * BudgetFox configuration.php file
 * ================================
 */ 

/* Database Connection Information:
 * --------------------------------
 * - DB_HOST: MySQL Database server name or IP
 * - DB_NAME: Database name on the server
 * - DB_USER: Database user
 * - DB_PASSWORD: User's password
 * - DB_TABLE_PREFIX: BudgetFox database tables prefix (for database hosting several web applications) 
 */

$DB_HOST = "localhost";
$DB_NAME = "gesfi";
$DB_USER = "root";
$DB_PASSWORD = "";

$DB_TABLE_PREFIX = "gf_";

/* Emailing Configuration:
 * -----------------------
 * - EMAIL_ACTIVE: true or false, indicates if the application is allowed to send emails
 * - EMAIL_FROM: emails sender address
 * - EMAIL_ADMINISTRATION: administrator's email 
 */

$EMAIL_ACTIVE = false;

$EMAIL_FROM = "do-not-respond@nowhere.nowhere";
$EMAIL_ADMINISTRATION = "contact@nowhere.nowhere";

/* Global Security Configuration:
 * ------------------------------
 * - READ_ONLY: true or false, indicates if the application can insert/update/delete data in the database (this is mainly used for the demonstration mode)
 * - SECURITY_SINGLE_USER_MODE: true or false
 * If set to true, the following parameter must be set
 * - SECURITY_SINGLE_USER_MODE_USER_ID: user id the application should automatically connect 
 */

$READ_ONLY = false;

$SECURITY_SINGLE_USER_MODE = false;
$SECURITY_SINGLE_USER_MODE_USER_ID = '4768b151-bd52-11e2-8d63-5c260a87ddbb';
