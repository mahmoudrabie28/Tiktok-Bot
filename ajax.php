<?php
 //----- Important to start Selenium -------//

namespace Facebook\WebDriver;
use Facebook\WebDriver\firefox\FirefoxOptions;
use Facebook\WebDriver\firefox\FirefoxProfile;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
require_once('webdriver/vendor/autoload.php');


 //----- Included functional php files -------//

include("includes/dest.php"); // For Website Desitantions Paths
include("includes/functions.php");
include($applib."simplehtmldom_1_9_1/simple_html_dom.php"); // simple html dom
include("includes/theme/header.php"); // Header


// Ajax for Profile Link
	if(isset($_POST['profileLink']))
    {
        if(!empty($_POST['profileLink']))
        {
            if(CheckURL($_POST['profileLink']) == 1) // validate it is url or not
            {

                // Tiktok Profile User name 
                $profile_username = explodeTikTokProfileURL($_POST['profileLink']);
                
                      //------------------------------------------------------//

                          //------------Selenium Get Data-----------//

                      //-----------------------------------------------------//

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
                        //$profile->addExtension('browser-extensions/Ublock.xpi'); // Adv-Block Extension
                        //$firefoxOptions->setProfile($profile);
                        $firefoxOptions->addArguments(['-headless']);
                                    //==== End Firefox Arguments ====//


                                    //==== Start Connection Prop ====//

                        $capabilities = DesiredCapabilities::firefox(); //You can change the browser by changing firefox() to chrome()
                        $capabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions); // Important to make firefox Option works

                                    //==== End Connection Prop ====//

                                    //==== Start Session Creation ====//

                        $driver = RemoteWebDriver::create($host, $capabilities);

                                    //==== End Session Creation ====//

                        //==== Start Automation ====//

                        // 1- navigate to Selenium page on tiktok page
                        $driver->get('https://www.tiktok.com/@'.$profile_username);

                        // 2- sleep(5)
                        sleep(5);

                        // search about close icon 
                        if(count($driver->findElements(WebDriverBy::id('verify-bar-close'))) > 0 ) // means it is existed
                        {
                          // click on captcha close icon
                          $driver->findElement(WebDriverBy::id('verify-bar-close'))->click();

                          sleep(3); // wait 3 seconds to ensure everything is right

                          // check if 'continue as guest appears' 
                          if(count($driver->findElements(WebDriverBy::xpath("//div[text()='Continue as guest']"))) > 0 )
                          { 
                            // close it by clicking on button 'continue as guest'
                            $driver->findElement(WebDriverBy::xpath("//div[text()='Continue as guest']"))->click();
                          }

                          $profile_main_info = array(); 

                          if(count($driver->findElements(WebDriverBy::xpath("//strong[@title='Following']"))) > 0 ) 
                          {
                            $profile_main_info[]      = $driver->findElement(WebDriverBy::xpath("//strong[@title='Following']"))->getText();
                            $profile_main_info[]      = $driver->findElement(WebDriverBy::xpath("//strong[@title='Followers']"))->getText();
                            $profile_main_info[]      = $driver->findElement(WebDriverBy::xpath("//strong[@title='Likes']"))->getText();
                            $profile_main_info[]      = $driver->findElement(WebDriverBy::className('css-1zpj2q-ImgAvatar'))->getAttribute('src');
                            $profile_main_info[]      = $driver->findElement(WebDriverBy::xpath("//h2[@data-e2e='user-bio']"))->getText();
                            $profile_main_info[]      = $driver->findElement(WebDriverBy::xpath("//h2[@data-e2e='user-subtitle']"))->getText();

                            $profile_following = $profile_main_info[0];
                            $profile_followers = $profile_main_info[1];
                            $profile_likes     = $profile_main_info[2];
                            $profile_image     = $profile_main_info[3];
                            $profile_bio       = $profile_main_info[4];
                            $profile_user      = $profile_main_info[5];
                          }


                        }

                        // check if username is incorrect or right

                        if(empty($profile_main_info))
                        {
                          ?>

                              <div class="alert alert-danger text-center mx-auto fit-content">
                                 <strong>Please insert correct profile link</strong>
                              </div>

                          <?php

                          die(); // to prevent errors appears due to below undefined variables
                        }
                    

            ?>

                          <div class="row">
                              <div class="card mx-auto">
                                <img class="card-img-top tiktok-profile-image mx-auto" src="<?php echo $profile_image; ?>" alt="Card image">
                                <div class="card-body mx-auto">
                                  <span class="badge badge-info d-block h5"><?php echo $profile_bio; ?></span>
                                  <a href="https://www.tiktok.com/@<?php echo $profile_user ;?>" class="btn btn-secondary btn-sm btn-block mx-auto">See Profile</a>
                                </div>
                              </div>
                          </div>
                          <!-- Profile Main Info -->
                          <div class="row mt-2">
                            <!-- TikTok user followers -->
                            <div class="col-xl-3 col-sm-6 col-12"> 
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">

                                        <i class="fa-solid fa-users float-left font-large-2 h1"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        <h3><?php echo $profile_followers; ?></h3>
                                        <span>Total Followers</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- TikTok user total likes -->
                            <div class="col-xl-3 col-sm-6 col-12">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-heart float-left font-large-2 h1 text-danger"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        <h3><?php echo $profile_likes; ?></h3>
                                        <span>Total Likes</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- TikTok user total videos -->
                            <div class="col-xl-3 col-sm-6 col-12">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-video float-left font-large-2 h1"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        <h3><?php echo getTiktokProfileInfo($profile_username)[2]; ?></h3>
                                        <span>Total Videos</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- TikTok user following -->
                            <div class="col-xl-3 col-sm-6 col-12">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="icon-pointer danger font-large-2 float-left"></i>
                                        <i class="fa-solid fa-people-group float-left font-large-2 h1 text-primary"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        <h3><?php echo $profile_following; ?></h3>
                                        <span>Following</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                           <!-- TikTok Auto Panel -->
                          <div class="row mt-2">
                            <!-- TikTok followers -->
                            <div class="col-xl-3 col-sm-6 col-12">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-user-plus float-left font-large-2 text-info auto-icons"></i>

                                      </div>
                                      <div class="media-body text-right">
                                        Auto Profile Followers
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <!-- TikTok likes -->
                            <div class="col-xl-3 col-sm-6 col-12">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-heart-circle-plus float-left font-large-2 text-danger auto-icons"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        Auto Video Likes
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <!-- TikTok Comments Hearts -->
                            <div class="col-xl-3 col-sm-6 col-12">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-comment-heart float-left font-large-2 text-danger auto-icons"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        Auto Comment Likes
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- TikTok Views -->
                            <div class="col-xl-3 col-sm-6 col-12">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-eye float-left font-large-2 text-info auto-icons"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        <!--Auto Video Views-->
                                        
                                        <a href="views.php?username=<?php echo $profile_username; ?>&case=views" target="_blank" class="btn btn-success btn-sm">Auto Video Views</a>
                                      
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- TikTok Shares -->
                             <div class="col-xl-3 col-sm-6 col-12 mt-2">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-share float-left font-large-2 text-primary auto-icons"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        <!--Auto Video Shares -->
                                        
                                        <a href="shares.php?username=<?php echo $profile_username; ?>&case=shares" target="_blank" class="btn btn-success btn-sm">Auto Video Shares</a>
                                     
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- TikTok Favorites -->
                             <div class="col-xl-3 col-sm-6 col-12 mt-2">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-bookmark float-left font-large-2 auto-icons text-warning"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        <a href="favorites.php?username=<?php echo $profile_username; ?>&case=favorites" target="_blank" class="btn btn-success btn-sm">Auto Video Favorites</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- TikTok Live Stream [VS+LIKES] -->
                             <div class="col-xl-3 col-sm-6 col-12 mt-2">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-signal-bars float-left font-large-2 auto-icons text-secondary"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        Live Stream [VS+LIKES]
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- TikTok Profile Report -->
                             <div class="col-xl-3 col-sm-6 col-12 mt-2">
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body">
                                    <div class="media d-flex">
                                      <div class="align-self-center">
                                        <i class="fa-solid fa-flag float-left font-large-2 auto-icons text-dark"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        Profile Mass Report
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

            <?php
            }
            else
            {
                // insert that profile link is not url
            }

        }
        else
        {
            // insert that ptofile link input can not be empty
        }
    }


include("includes/theme/footer.php"); // Footer

?>