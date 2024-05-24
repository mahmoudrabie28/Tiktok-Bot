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

if(isset($_GET['username']) && isset($_GET['case']))
{
	if($_GET['case'] == "shares")
	{
		?>

		<div class="container">
            <h1 class="header-tiktok"><i class="fa-brands fa-tiktok"></i> TikTok Shares Automation</h1>
            <div class="row" id="video-link-row">
                <div class="row col-8 mx-auto">
                	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="mx-auto w-100">
                		<div class="form-group video-link-formgroup">
	                      <h5 for="video-link" class="mx-auto text-center">Please insert the tiktok video link</h5>
	                      <input type="text" class="form-control mb-1 text-center" id="video-link" name="video_link" placeholder="Please Insert Video link">
	                    </div>
	                    <div class="form-group video-link-formgroup col-6">
	                      <h6 for="round-number" class="mx-auto text-center">Round Number:</h6>
	                      <input type="number" inputmode="numeric" pattern="[0-9\s]" class="form-control mb-1 text-center" id="round-number" name="round_number" placeholder="Please Insert Round Number Only Integer Number">
	                    </div>
	                    <button id="video-link-button" type="submit" class="btn btn-primary mx-auto col-12">Submit</button>
                	</form>
                    
                </div>
            </div>

        </div>  

		<?php
	}
	else
	{
		// Error we can not find this page 
	}
}

if(isset($_POST['video_link']) && isset($_POST['round_number']))
{

	$video_link   = $_POST['video_link'];
	

	if(empty($_POST['round_number']) OR $_POST['round_number'] == 0)
	{
		 ?>

		 <div class="alert alert-danger mx-auto text-center fit-content"><strong>Rounds number can not be empty or equal to zero</strong></div>

		 <?php

		 die();
	}
	else
	{
		if(checkInt($_POST['round_number']) == "integer")
		{	
			if($_POST['round_number'] > 100)
			{
				 ?>

				 <div class="alert alert-danger mx-auto text-center fit-content mt-2"><strong>Please insert Rounds number less than 100 rounds</strong></div>

				 <?php

				 die();
			}
			else
			{
				$round_number = $_POST['round_number'];
			}
		
		}
		else
		{
			?>

			 <div class="alert alert-danger mx-auto text-center fit-content"><strong>Please insert Rounds number as an integer number</strong></div>

			 <?php

			 die();
		}
	}


		           //==== Start Firefox Instance Classes ====//

	// Create an instance of FirefoxOptions:
	$firefoxOptions = new FirefoxOptions();

	// Create an instance of FirefoxProfile:
	$profile = new FirefoxProfile();

	            //==== End Firefox Instance Classes ====//

	// This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/   http://localhost:4444/wd/hub
	$host = 'http://localhost:4444/wd/hub';

	            //==== Start Firefox Arguments ====// 

	// Add Firefox Extension
	$profile->addExtension('browser-extensions/adBlockPlus.xpi'); // Adv-Block Extension
	$firefoxOptions->setProfile($profile);

	            //==== End Firefox Arguments ====//


	            //==== Start Connection Prop ====//

	$capabilities = DesiredCapabilities::firefox(); //You can change the browser by changing firefox() to chrome()
	$capabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions); // Important to make firefox Option works

	            //==== End Connection Prop ====//

	            //==== Start Session Creation ====//

	$driver = RemoteWebDriver::create($host, $capabilities);

	            //==== End Session Creation ====//

	//==== Start Automation ====//

	// 1- navigate to Selenium page on Zefoy
	$driver->get('https://www.zefoy.com');
	// 2- Wait 40 Seconds Until the user puts the captcha 
	sleep(40);

	// 3- get share button location 
	$share_button = $driver->wait(60,500)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('t-shares-button')));

	// 4- click on share button location
	$share_button->click(); 

	// 5- get search share input location by using 'ruto xpath find chrome extension'
	$get_search_input = $driver->findElement(WebDriverBy::cssSelector('html>body>div:nth-of-type(11)>div>form>div>input')); 

	// 6- insert letter by letter in share search input video link
	$get_search_input->sendKeys($video_link);

	// 6.1- get rest 5 seconds to ensure that everything is right
	sleep(5);

	// 7- find and click on search button
	$driver->findElement(WebDriverBy::cssSelector('html>body>div:nth-of-type(11)>div>form>div>div>button'))->click();

	$predefined_round = 0;
	$user_round = $_POST['round_number'];



	while ($predefined_round < $user_round)
	{
		// if search button is existed

		if(count($driver->findElements(WebDriverBy::cssSelector('html>body>div:nth-of-type(11)>div>form>div>div>button'))) > 0 ) // means it is existed
		{
		    sleep(5); // sleep 5 seconds to ensure everything is right

		    // check if the search button is enabled 
		    if($driver->findElement(WebDriverBy::cssSelector('html>body>div:nth-of-type(11)>div>form>div>div>button'))->isEnabled())
		    {
		        // click on search button
		        $driver->findElement(WebDriverBy::cssSelector('html>body>div:nth-of-type(11)>div>form>div>div>button'))->click();

		        // sleep for 5 seconds
		        sleep(5);

		        // search for the the three previous cases
		        if(count($driver->findElements(WebDriverBy::className('shares-countdown'))) > 0) // the timer is here
		        {

		            // search about word ready and we will try searching period about 3 minutes
					$driver->wait(180, 500)->until(WebDriverExpectedCondition::elementTextContains(WebDriverBy::className('shares-countdown'), 'READY'));
    
		            // put the value inside a variable
		            $get_ready_value = $driver->findElement(WebDriverBy::className('shares-countdown'))->getText();

		            // pre-defined value 
		            $pre_defined_value = "Next Submit: READY....!";

		            // comapre the variable with the value 
		            if(str_contains($pre_defined_value, $get_ready_value))
		            {

		               // sleep for 5 seconds
		               sleep(5);

		                // check if the search button is enabled or disabled again like previous step 
		                if($driver->findElement(WebDriverBy::cssSelector('html>body>div:nth-of-type(11)>div>form>div>div>button'))->isEnabled())
		                {
		                    // click on search button
		                    $driver->findElement(WebDriverBy::cssSelector('html>body>div:nth-of-type(11)>div>form>div>div>button'))->click();

		                    // sleep for 5 seconds
		                    sleep(5);

		                    // check if the shares increase button is existed or not  
		                    if(count($driver->findElements(WebDriverBy::cssSelector('div#c2VuZC9mb2xsb3dlcnNfdGlrdG9s>div>div>form>button'))) > 0)
		                    {
		                        // click on shares increase button
		                        $driver->findElement(WebDriverBy::cssSelector('div#c2VuZC9mb2xsb3dlcnNfdGlrdG9s>div>div>form>button'))->click();
		                        $round_number_display = $predefined_round+1;
		                        ?>

		                            <div class="alert alert-success text-center mx-auto fit-content">
		                                Shares have been sent successfully => Round (<?php echo $round_number_display; ?>).
		                            </div>

		                        <?php

		                        $predefined_round++; // increase round by one
		                        sleep(5); // wait 5 seconds before the next round


		                    }
		                    else // we did not find the increase button so it considers an error and die
		                    {

		                        ?>

		                            <div class="alert alert-danger text-center mx-auto fit-content">
		                                 <strong>There's an Error in the process. [CODE: 06]</strong>
		                            </div>

		                        <?php

		                        die();
		                    }

		                }
		                else
		                {
		                    ?>

		                        <div class="alert alert-danger text-center mx-auto fit-content">
		                             <strong>Search button is not enabled yet. [CODE: 05]</strong>
		                        </div>

		                    <?php

		                        die();
		                }

		            }
		            else
		            {
		                 ?>

		                    <div class="alert alert-danger text-center mx-auto fit-content">
		                         <strong>There's an Error in the process. [CODE: 04]</strong>
		                    </div>

		                <?php

		                die();
		            }




		        } // we find the share increase button directly
		        elseif (count($driver->findElements(WebDriverBy::cssSelector('div#c2VuZC9mb2xsb3dlcnNfdGlrdG9s>div>div>form>button'))) > 0)
		        {
		        	// sleep for 5 seconds
		            sleep(5);

		            // click on the increase button
		            $driver->findElement(WebDriverBy::cssSelector('div#c2VuZC9mb2xsb3dlcnNfdGlrdG9s>div>div>form>button'))->click();

		            $round_number_display = $predefined_round+1;
		            ?>

		                <div class="alert alert-success text-center mx-auto fit-content">
		                    Shares have been sent successfully => Round (<?php echo $round_number_display; ?>).
		                </div>

		            <?php

		            $predefined_round++; // increase round by one
		            sleep(5); // wait 5 seconds before the next round

		        }
		        else
		        {
		             ?>

		                <div class="alert alert-danger text-center mx-auto fit-content">
		                     <strong>There's an Error in the process. [CODE: 03]</strong>
		                </div>

		            <?php

		            die();
		        }
		    }
		    else // it means not enabled yet
		    {
		        ?>

		            <div class="alert alert-danger text-center mx-auto fit-content">
		                 <strong>Search Button Is Not Enabled Yet. [CODE: 02]</strong>
		            </div>

		        <?php

		        die();
		    }
		}
		else // search button is not existed 
		{
		    ?>

		        <div class="alert alert-danger text-center mx-auto fit-content">
		             <strong>Search Button Is Not Existed. [CODE: 01]</strong>
		        </div>

		    <?php

		    die();
		}
	}

	
	
	
}

 

?>


  
<?php

include("includes/theme/footer.php"); // Footer
?>

