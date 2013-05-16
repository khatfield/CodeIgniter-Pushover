# CodeIgniter Pushover Library
by [Keith Hatfield](http://keithscode.com)

This library facilitates the sending of Pushover notifications from
a CodeIgniter application. For more information on Pushover, visit
http://pushover.net

Please see the Limitations/Planned Upgrades section for information
on future improvements.

## Installation
Copy the files from the package to the corresponding folders in your 
application folder.

### Configuration
Configuration settings can be changed in the pushover.php file in the
config folder.
* pushover_token: The API Token/Key given by pushover after registering 
  your application
* If set to true, will log the response data from Pushover after a message
  is sent
* The model used for logging responses, if the above option is set to true

## Usage
Pushover requires that the user and message be set to send a message, though
there are a number of extra options that can be added. See the 
[Pushover API Docs](https://pushover.net/api) for more information on the
options available. 

### Loading the Library
Once you have your configuration set, the library can be loaded from the 
controller like any other CodeIgniter library

    $this->load->library('pushover');

### Sending a Message (option 1)

    $user     = 'xxxxx'; //user's pushover key
    $message  = 'Hello, User!';
    $title    = 'My Test Message';
    $options  = array('sound' => 'cash_register');
    $response = $this->pushover->sendMessage($user, $message, $title, $options);
    
### Sending a Message (option 2)

    $this->pushover->setUser('xxxxxxx');
    $this->pushover->setMessage('Hello, User!');
    $this->pushover->setTitle('My Test Message');
    //library supports chaining
    $this->pushover->setOption('sound', 'pianobar')->setOption('priority', -1);
    $response = $this->pushover->sendMessage();
    
### Error Handling
The library will return boolean false if the cURL request fails. The error data will
be available via `$this->pushover->getError();`

If the cURL request is successful, the response data from Pushover is returned. It is
possible the the cURL request will succeed, but the request fail at Pushover. The
returned response from Pushover will need to be examined to determine the status
of the request at Pushover. (More robust error handling is planned)

## Limitations/Planned Upgrades
* There is currently not support in the library for receiving the Pushover callbacks.
  Callback handling will be added in the not-too-distant future.
* As noted above, the error handling will be updated to check the Pushover response
  and determine success or failure from that.

## Extra Information
The file at `/sql/pushover_logs.sql` contains the MySQL table structure for logging
Pushover responses.