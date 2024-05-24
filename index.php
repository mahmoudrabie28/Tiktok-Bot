<?php
include("includes/dest.php"); // For Website Desitantions Paths
include("includes/functions.php");
include($applib."simplehtmldom_1_9_1/simple_html_dom.php"); // simple html dom
include("includes/theme/header.php"); // Header

?>


        <div class="container">
            <h1 class="header-tiktok"><i class="fa-brands fa-tiktok"></i> TikTok Automation</h1>
            <div class="row" id="profile-link-row">
                <div class="row col-md-6 mx-auto">
                    <div class="form-group profile-link-formgroup">
                      <label for="profile-link" class="mx-auto">Please insert the tiktok profile link</label>
                      <input type="text" class="form-control mb-1 text-center" id="profile-link" name="profile-link-input">
                    </div>
                    <button id="profile-link-button" class="btn btn-primary mx-auto col-12">Submit</button>
                </div>
            </div>
            <div>
            <div class="row col-md-3 mx-auto mt-4" id="loadingDiv">
                <img src="includes/theme/images/spinner.gif">
            </div>
              <div id="profile-link-response" class="mx-auto">
                
              </div>  
            </div>

        </div>  



<?php

include("includes/theme/footer.php"); // Footer

?>