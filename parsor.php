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
        <li class="active"> <a href="#"> <strong>Web Search</strong> </a> </li>
        <?php echo '<li> <a href="twitterfeeds.php?SearchBar='.$search.'"><strong> Twitter Feed</strong> </a> </li>' ?>
        <?php echo '<li> <a href="vidfeed.php?SearchBar='.$search.'"> <strong>Video Rumors <strong></a> </li>' ?>
      </ul>
    </div>
  </div>
</div>
  </body>
</html>





<?php
$search=$_GET["SearchBar"];
  // define the URL to load
  $url = 'http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20boss.search%20where%20q%3D%22'.$search.'%20rumors%22%20and%20ck%3D%22dj0yJmk9YWF3ODdGNWZPYjg2JmQ9WVdrOWVsWlZNRk5KTldFbWNHbzlNVEEyTURFNU1qWXkmcz1jb25zdW1lcnNlY3JldCZ4PTUz%22%20and%20secret%3D%22a3d93853ba3bad8a99a175e8ffa90a702cd08cfa%22%3B&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';
  $proxy="netmon.iitb.ac.in";
  $port="80";
  $loginpass='120050045:shakti,nath';
	// start cURL
  $ch = curl_init();
 curl_setopt($ch,CURLOPT_PROXYPORT,$port);
 curl_setopt($ch, CURLOPT_PROXY, $proxy);
 curl_setopt($ch,CURLOPT_PROXYUSERPWD,$loginpass);
  // tell cURL what the URL is
  curl_setopt($ch, CURLOPT_URL, $url); 
  // tell cURL that you want the data back from that URL
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  // run cURL
  $output = curl_exec($ch); 
  // end the cURL call (this also cleans up memory so it is 
  // important)
  curl_close($ch);
  // display the output
//  echo $output;
$query = new SimpleXMLElement($output);
echo '<div class="container">';
echo '<div class="bs-social">';
for ($i=0;$i<50;$i++){
echo '<p><a href="'.$query->results->bossresponse->web->results->result[$i]->url.'class="bs-social"><strong>'.$query->results->bossresponse->web->results->result[$i]->title.'</strong></a><br/><h3>'.$query->results->bossresponse->web->results->result[$i]->url.'</h3>'.$query->results->bossresponse->web->results->result[$i]->abstract.'<hr/></p>';
}
echo '</div>
      </div>';
?>

