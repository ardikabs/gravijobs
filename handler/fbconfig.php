<?php
session_start();
// added in v4.0.0
include '../config/dbconfig.php';
require_once 'autoload.php';

require_once('Facebook/FacebookSession.php');
require_once('Facebook/FacebookRedirectLoginHelper.php');
require_once('Facebook/FacebookRequest.php');
require_once('Facebook/FacebookResponse.php');
require_once('Facebook/FacebookSDKException.php');
require_once('Facebook/FacebookRequestException.php');
require_once('Facebook/FacebookAuthorizationException.php');
require_once('Facebook/GraphObject.php');
require_once('Facebook/GraphUser.php');
require_once('Facebook/GraphSessionInfo.php');

require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// init app with app id and secret
FacebookSession::setDefaultApplication('1938011066444605','5e125a856ff4ae09cce5393e061d6db3');

// login helper with redirect_uri
    $helper = new FacebookRedirectLoginHelper('http://localhost:8080/gravijobs/handler/fbconfig.php' );
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}


// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me?fields=name,email' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
 	$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
  $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
  $femail = $graphObject->getProperty('email');    // To Get Facebook email ID

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM user WHERE provider_id=$fbid LIMIT 1";
  $qUser = $pdo->prepare($sql);
  $qUser->execute();
  $userRow = $qUser->fetch(PDO::FETCH_ASSOC);

  if($qUser->rowCount()>0){
    $sql = 'SELECT * FROM user_detail WHERE id_user=:id_user LIMIT 1';
    $q = $pdo->prepare($sql);
    $q->execute(array(':id_user'=>$userRow['id_user']));
    $userInfo = $q->fetch(PDO::FETCH_ASSOC);

    $_SESSION['user_session'] = $userRow['id_user'];
    $_SESSION['user_name'] = $userInfo['nama_lengkap'];

    header("Location: http://localhost:8080/gravijobs/manage");
  }

  else{
    $role = 2;
    $sql = 'INSERT INTO user (id_role, email, provider,provider_id,status,valid) VALUES(:role, :email,:provider,:provider_id,1,0)';
    $q = $pdo->prepare($sql);
    $q->execute(array(':role'=>$role, ':email'=>$femail,  ':provider'=>'facebook',':provider_id'=>$fbid));
    $id_user = $pdo->lastInsertId();

    $sql = "INSERT INTO user_detail (id_user,id_lembaga,nama_lengkap) VALUES($id_user,9999,'$fbfullname')";
    $q = $pdo->prepare($sql);
    $q->execute();

    header("Location: http://localhost:8080/gravijobs");


  }

  Database::disconnect();


} else {
  $loginUrl = $helper->getLoginUrl(array('scope' => 'email'));
 header("Location: ".$loginUrl);
}
?>