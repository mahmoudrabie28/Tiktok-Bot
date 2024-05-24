<?php

// function to check if the inserted input is letter or numbers
function PregMatchLettersAndNumbers($inserted_word)
{
    if(!empty($inserted_word))
    {
    	$check_word;
        if(preg_match('/^[a-zA-Z0-9]+/', $inserted_word))
        {
            $check_word = 1;
        }
        else
        {
           $check_word = 0;
        }

        return $check_word;
    }
}

function CheckURL($link)
{
	$check_link;
	if(filter_var($link, FILTER_VALIDATE_URL))
	{
		$check_link = 1;
	}
	else
	{
		$check_link = 2;
	}

	return $check_link;

}

function explodeTikTokProfileURL($profile_link)
{

	$explode_profile_link = explode("@",$profile_link);
	$username_check_tail  = $explode_profile_link[1];

	if(strpos($username_check_tail, '?'))
	{
		$profile_username_explode = explode("?", $username_check_tail);

		$profile_username = $profile_username_explode[0];

	}
	else
	{
		$profile_username = $username_check_tail;
	}

	return $profile_username;
}

// Deprecated function
function checkRegisteredUserName($tiktok_username)
{
	$html = file_get_html('https://countik.com/tiktok-analytics/user/@'.$tiktok_username);
	// find tiktok profile username in countik website

	$profile_username_check = $html->find('div.username h2', 0)->innertext();
	    return $profile_username_check;
}

// Deprecated function
function getTiktokProfileImage($tiktok_username)
{
	$html = file_get_html('https://countik.com/tiktok-analytics/user/@'.$tiktok_username);
	// find tiktok profile image in countik website by using profile username

	$profile_image    = $html->find('div.pic img', 0);
    $profile_img_link = $profile_image->src;
    return $profile_img_link;
}

// it is only used to get number of videos from another site 
function getTiktokProfileInfo($tiktok_username)
{
	$main_info_array = array();
	$html = file_get_html('https://countik.com/tiktok-analytics/user/@'.$tiktok_username);
	foreach($html->find('div.user-stats .wrapp .block p') as $result)
	{
	    $result_value = $result->innertext;
	    $main_info_array[] = $result_value;
	}

	return $main_info_array;
}

function checkInt($inserted_value)
{
    if(preg_match('/^[1-9][0-9]*$/', $inserted_value))
    {
        $return_value = "integer";
    }
    else
    {
        $return_value = "not integer";
    }

    return $return_value;
}

function systemMsg($class, $msg)
{

	echo "<div class=".$class.">".$msg."</div>";
}

?>