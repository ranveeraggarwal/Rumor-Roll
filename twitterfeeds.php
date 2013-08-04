<!DOCTYPE html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
  
      Rumor Roll!-Search Results
  
    </title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.css" rel="stylesheet">

    <!-- Documentation extras -->
    <link href="./css/main.css" rel="stylesheet">
    <link href="./css/pyg.css" rel="stylesheet">

  </head>
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
  <div class="container">
    <a href="./index.php" class="navbar-brand">Rumor Roll!</a>
    <div class="nav-collapse collapse bs-navbar-collapse">
      <ul class="nav navbar-nav">
        <li> <a href="#"> Rumors from all over the web... And beyond...</a> </li>
      </ul>
    </div>
  </div>
</div>
  
  <div class="bs-docs-home">
  <div class="bs-jumbotron">
  <div class="container">
    <br/>
    <fieldset>
          <?php echo '<form action="parsor.php">
            <div class="form-group">
              <input type="text" class="form-control" id="SearchBar" name="SearchBar" placeholder="Looking for something else?">
            </div>
            <button type="submit" class="btn btn-default">Search</button>
          </form>
        </fieldset>';?>
        <br/>
</div>
</div>
</div>
    <div class="bs-header">
      <div class="container">
       <?php $search=$_GET["SearchBar"];
       $HeadingPr='Search Results For '.'"'.$search.'"';
       echo '<h1>'.$HeadingPr.'</h1>' ?>
    </div>
    </div>
    <div class="navbar navbar-inverse bs-docs-nav">
  <div class="container">
    <div class="nav-collapse collapse bs-navbar-collapse">
      <ul class="nav navbar-nav">
        <?php echo '<li> <a href="parsor.php?SearchBar='.$search.'"> <strong>Web Search</strong> </a> </li>' ?>
        <?php echo '<li class="active"> <a href="twitterfeeds.php?SearchBar='.$search.'"><strong> Twitter Feed</strong> </a> </li>' ?>
        <?php echo '<li> <a href="vidfeed.php?SearchBar='.$search.'"> <strong>Video Rumors <strong></a> </li>' ?>
      </ul>
    </div>
  </div>
</div>

<?php

/**
 *  Usage:
 *  Send the url you want to access url encoded in the url paramater, for example (This is with JS): 
 *  /twitter-proxy.php?url='+encodeURIComponent('statuses/user_timeline.json?screen_name=MikeRogers0&count=2')
*/

// The tokens, keys and secrets from the app you created at https://dev.twitter.com/apps
$config = array(
	'oauth_access_token' => '550659862-TgeBpjQouXgqhFIjh3QjJyfd9XkvpVdqS0YnzZXa',
	'oauth_access_token_secret' => '74SeqmiwewUrCniKzNKmahsHBr0JLadrEbYvu48B0',
	'consumer_key' => '07EHX7OoJMOfXW7LLaJXCA',
	'consumer_secret' => 'UqLkVM4UDD8dSpXVuftni0Xd2RUqrAUZlUfBQxjKFqs',
	'use_whitelist' => false, // If you want to only allow some requests to use this script.
	'base_url' => 'http://api.twitter.com/1.1/'
);

// Only allow certain requests to twitter. Stop randoms using your server as a proxy.
$whitelist = array(
	'statuses/user_timeline.json?screen_name=SachinTendulkar0&count=10&include_rts=false&exclude_replies=true'=>true
);

//$search = $_GET("SearchBar");
$search1 = $_GET["SearchBar"];
$search = preg_replace('/\s+/', '&' , $search1);
$url = 'search/tweets.json?q='.$search.'&count=100&result_type=popular&lang=en';

/*
* Ok, no more config should really be needed. Yay!
*/

// We'll get the URL from $_GET[]. Make sure the url is url encoded, for example encodeURIComponent('statuses/user_timeline.json?screen_name=MikeRogers0&count=10&include_rts=false&exclude_replies=true')
//if(!isset($_GET['url'])){
//	die('No URL set');
//}

//$url = $_GET['url'];


if($config['use_whitelist'] && !isset($whitelist[$url])){
	die('URL is not authorised');
}

// Figure out the URL parmaters
$url_parts = parse_url($url);
parse_str($url_parts['query'], $url_arguments);

$full_url = $config['base_url'].$url; // Url with the query on it.
$base_url = $config['base_url'].$url_parts['path']; // Url without the query.

/**
* Code below from http://stackoverflow.com/questions/12916539/simplest-php-example-retrieving-user-timeline-with-twitter-api-version-1-1 by Rivers 
* with a few modfications by Mike Rogers to support variables in the URL nicely
*/

function buildBaseString($baseURI, $method, $params) {
	$r = array();
	ksort($params);
	foreach($params as $key=>$value){
	$r[] = "$key=" . rawurlencode($value);
	}
	return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth) {
	$r = 'Authorization: OAuth ';
	$values = array();
	foreach($oauth as $key=>$value)
	$values[] = "$key=\"" . rawurlencode($value) . "\"";
	$r .= implode(', ', $values);
	return $r;
}

// Set up the oauth Authorization array
$oauth = array(
	'oauth_consumer_key' => $config['consumer_key'],
	'oauth_nonce' => time(),
	'oauth_signature_method' => 'HMAC-SHA1',
	'oauth_token' => $config['oauth_access_token'],
	'oauth_timestamp' => time(),
	'oauth_version' => '1.0'
);
	
$base_info = buildBaseString($base_url, 'GET', array_merge($oauth, $url_arguments));
$composite_key = rawurlencode($config['consumer_secret']) . '&' . rawurlencode($config['oauth_access_token_secret']);
$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
$oauth['oauth_signature'] = $oauth_signature;

// Make Requests
$header = array(
	buildAuthorizationHeader($oauth), 
	'Expect:'
);
$loginpass='120050034:mood+indigo2012';
$options = array(
	CURLOPT_HTTPHEADER => $header,
	//CURLOPT_POSTFIELDS => $postfields,
	CURLOPT_HEADER => false,
	CURLOPT_URL => $full_url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_PROXY => 'netmon.iitb.ac.in',
	CURLOPT_PROXYUSERPWD => $loginpass,
	CURLOPT_PROXYPORT => 80
);

$feed = curl_init();
curl_setopt_array($feed, $options);
$result = curl_exec($feed);
$info = curl_getinfo($feed);
curl_close($feed);

// Send suitable headers to the end user.
/*if(isset($info['content_type']) && isset($info['size_download'])){
	header('Content-Type: '.$info['content_type']);
	header('Content-Length: '.$info['size_download']);

}*/
$json_decoded = json_decode($result,true);
echo '<div class="container">';
echo '<div class="bs-social">';
for($i=0;$i<count($json_decoded["statuses"]);$i++){
echo '<div class="well">'.$json_decoded["statuses"][$i]["text"].'</br>'.$json_decoded["statuses"][$i]["created_at"].'</br>'.$json_decoded["statuses"][$i]["user"]["name"].'</br>'.$json_decoded["statuses"][$i]["user"]["description"].'</br>'."No of followers : ".$json_decoded["statuses"][$i]["user"]["followers_count"].'</br>'.'</div>';

}


?>
  </body>
</html>


