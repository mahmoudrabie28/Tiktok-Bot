<?php

// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.

namespace Facebook\WebDriver;

use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Firefox\FirefoxProfile;

require_once('vendor/autoload.php');

// This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
$host = 'http://localhost:4444/wd/hub';
// for FireFox Options
$firefoxOptions = new FirefoxOptions();
// for FireFox Profile
$profile = new FirefoxProfile();

// Load extension from extensions file
$profile->addExtension('extensions/uBlock0@raymondhill.net.xpi');

$firefoxOptions->setProfile($profile);


$capabilities = DesiredCapabilities::firefox();
$capabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions); // This is must be added to run firefoxOptions

$driver = RemoteWebDriver::create($host, $capabilities);

$driver->get("https://zefoy.com");



?>
