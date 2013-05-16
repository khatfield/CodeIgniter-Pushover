<?php

/*
|--------------------------------------------------------------------------
| Pushover Token
|--------------------------------------------------------------------------
|
| The API Token/Key given by pushover after registering your application
|
*/
$config['pushover_token'] = 'XXXXXXXXXX';

/*
|--------------------------------------------------------------------------
| Response Logging
|--------------------------------------------------------------------------
|
| If set to true, will log the response data from Pushover after a message 
| is sent
| 
| Before setting this value to true, you will need to:
|  - Create the logging table in your database (sql provided in /sql/pushover_logs.sql)
|  - Add the Pushover_log model to your models folder
*/
$config['enable_logging'] = false;

/*
|--------------------------------------------------------------------------
| Logging Model
|--------------------------------------------------------------------------
|
| The model used for logging responses, if the above option is set to true
|
| If you change from the default value below, you will need to update the
| included model and sql for creating the table
*/
$config['logging_model'] = 'Pushover_log';
