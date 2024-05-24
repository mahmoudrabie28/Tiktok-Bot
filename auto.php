<?php

// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.
namespace Facebook\WebDriver;
use Facebook\WebDriver\firefox\FirefoxOptions;
use Facebook\WebDriver\firefox\FirefoxProfile;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

include("includes/dest.php"); // For Website Desitantions Paths
include("includes/functions.php");
include("includes/theme/header.php"); // Header
require_once('webdriver/vendor/autoload.php');

            //==== Start Firefox Instance Classes ====//

// Create an instance of FirefoxOptions:
$firefoxOptions = new FirefoxOptions();

// Create an instance of FirefoxProfile:
$profile = new FirefoxProfile();

            //==== End Firefox Instance Classes ====//

// This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
$host = 'http://localhost:4444/wd/hub';

            //==== Start Firefox Arguments ====// 

// Add Firefox Extension
//$profile->addExtension('browser-extensions/Ublock.xpi'); // Adv-Block Extension
//$firefoxOptions->setProfile($profile);

            //==== End Firefox Arguments ====//


            //==== Start Connection Prop ====//

$capabilities = DesiredCapabilities::firefox(); //You can change the browser by changing firefox() to chrome()
$capabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions); // Important to make firefox Option works

            //==== End Connection Prop ====//

            //==== Start Session Creation ====//

$driver = RemoteWebDriver::create($host, $capabilities);

            //==== End Session Creation ====//

//==== Start Automation ====//

// navigate to Selenium page on Wikipedia
$driver->get('https://www.zefoy.com');

   



?>

        <div class="container">
            <h1 class="header-tiktok">TikTok Automotion</h1>
            <div class="row" id="zefoy-captcha-row">
                <div class="row col-md-4">
                    <div class="form-group">
                      <label for="zefoy-captcha">Please insert the word from the image inside the next input:</label>
                      <input type="text" class="form-control " id="zefoy-captcha" name="captcha-input">
                    </div>
                    <button id="zefoy-captcha-button" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <div class="row">
              <div id="zefoy-captcha-response">
                
              </div>  
            </div>
        </div>  

  
<?php
include("includes/theme/footer.php"); // Footer
?>
