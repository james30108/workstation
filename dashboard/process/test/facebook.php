
<!DOCTYPE html>
<html>
<head>
<title>Facebook Login JavaScript Example</title>
<meta charset="UTF-8">
</head>
<body>
<script>

  function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    console.log('statusChangeCallback');
    console.log(response);                   // The current login status of the person.
    if (response.status === 'connected') {   // Logged into your webpage and Facebook.
      testAPI();  
    } else {                                 // Not logged into your webpage or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log ' + 'into this webpage.';
    }
    //alert(test);
    return test;
  }

  function checkLoginState() {               // Called when a person is finished with the Login Button.
    FB.getLoginStatus(function(response) {   // See the onlogin handler
      statusChangeCallback(response);
      alert(response);
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '{265173512365961}',
      cookie     : true,                     // Enable cookies to allow the server to access the session.
      xfbml      : true,                     // Parse social plugins on this webpage.
      version    : '{v12.0}'           // Use this Graph API version for this call.
    });

    FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
      statusChangeCallback(response);        // Returns the login status.
    });
  };
 
  function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
    console.log('Welcome!  Fetching your information.... ');
    FB.api('me?fields=id,name,email,picture', function(response) {
      console.log('Successful login for: ' + response.name);
      //var a = JSON.stringify(response);
      //document.getElementById('status').innerHTML = a;
      //alert('index.php?id=' + response.id + '&name=' + response.name + '&picture=' + response.picture.data.url);
      //window.location = 'index.php?id=' + response.id + '&name=' + response.name + '&picture=' + response.picture.data.url;
      
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.email + " / " + response.picture.data.url + '!';
      
      return response.email;
    });
  }

</script>

<?php
  /*
  $url = 'https://graph.facebook.com/v12.0/me';
  
  $data = "fields=id%2Cname%2Cemail%2Cpicture&access_token=EAADxLIUCJ4kBAAhzsZAPMsyQburbkgHbFtp8L2ZBjpHlsp31FqcrQFgI9Ms6PRZAEPIy0OZAVW1OMbFCyMoAZBGmzCxEIl9DsGRN0ZBrJJmWkjeFRLoH7YjA2bFWCskPJ5ZCFEL3APO8ZB8E9dKZCKAYhG99ZA05ZAF1X6fSdkLZAr0RC8ZC3gVQs7uTCtZCsC2uBdUKCDIuHi3rywM8DsYMXIZCD1S8ekRZBWgrXxvAKWy2UKJRAvcs4peqyQpi";
  
  try{
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    $content = curl_exec( $ch );
    curl_close($ch);
    
    //print_r($content);
    $data = json_decode($content, true);
    //print_r($data);
    
    echo $data['id'] . "<br>";
    echo $data['name'] . "<br>";
    echo $data['email'] . "<br>";
    echo $data['picture']['data']['url'] . "<br>";
    
  }catch(Exception $ex){
    echo $ex;
  }
  */
?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0&appId=265173512365961&autoLogAppEvents=1" nonce="ppqWjnVt"></script>



<!-- The JS SDK Login Button -->
<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button>

<a href="#"  onclick="fb_login();">ทดสอบ</a>

<div id="status"></div>

<!-- Load the JS SDK asynchronously -->
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
</body>
</html>