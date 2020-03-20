<?php
require '../drive/vendor/autoload.php';


/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('G Suite Directory API PHP Quickstart');
        $client->setScopes(Google_Service_Directory::ADMIN_DIRECTORY_USER_READONLY);

    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0770, true);
        }
        
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.

    $client = getClient();






$service = new Google_Service_Directory($client);


// Grup Ekleme

/*
$opt_data = [
        'email' => 'sucoursetest19032020@sabanciuniv.edu',
        'name' => 'sucoursetest19032020'
];

$obj = new Google_Service_Directory_Group($opt_data);
$results = $service->groups->insert($obj);
echo $results->id;
*/
$opt = [
"allowExternalMembers"=> true,
  "allowWebPosting"=> false,
  "enableCollaborativeInbox"=> "false",
  "kind"=> "groupsSettings#groups",
  "membersCanPostAsTheGroup"=> "false",
  "showInGroupDirectory"=> "false",
  "whoCanJoin"=> "INVITED_CAN_JOIN",
  "whoCanViewMembership"=> "ALL_MANAGERS_CAN_VIEW",
  "whoCanLeaveGroup"=> "NONE_CAN_LEAVE",
  "whoCanModerateMembers"=> "OWNERS_AND_MANAGERS",
  "includeInGlobalAddressList"=> "false",
  "whoCanPostAnnouncements"=> "ALL_MANAGERS_CAN_POST",
  "whoCanPostMessage"=> "ALL_MANAGERS_CAN_POST"
];
$groupssettingsService = new Google_Service_Groupssettings($client);
$grp = new Google_Service_Groupssettings_Groups();
  $grp->setAllowExternalMembers(true);
  $grp->setAllowWebPosting(false);
  $grp->setMembersCanPostAsTheGroup(false); 
  $grp->setShowInGroupDirectory(false); 
  $grp->setWhoCanJoin('INVITED_CAN_JOIN'); 
  $grp->setWhoCanViewMembership('ALL_MANAGERS_CAN_VIEW'); 
  $grp->setWhoCanLeaveGroup('NONE_CAN_LEAVE'); 
  
  /*$grp->setWhoCanModerateMembers('OWNERS_AND_MANAGERS'); */
  $grp->setIncludeInGlobalAddressList(false); 
  /*$grp->setWhoCanPostAnnouncements('ALL_MANAGERS_CAN_POST'); */
  $grp->setWhoCanPostMessage('NONE_CAN_POST');
  $grp->setArchiveOnly(true);

  /*$grp->setEnableCollaborativeInbox(false);*/
$groups = $groupssettingsService->groups->update('sucoursetest19032020@sabanciuniv.edu',$grp);
echo "<pre>";
print_r($groups);  
echo "</pre>";
/*
$options = [
    'email' => 'oguz.koruyucu@sabanciuniv.edu',
    'role' => 'MEMBER',
    'delivery_settings' => 'NONE'
];

$members = new Google_Service_Directory_Member($options);
$results = $service->members->insert('sucoursetest19032020@sabanciuniv.edu',$members);

echo "<pre>";
print_r($results);
echo "</pre>";
/*
$settings = new Google_Service_Groupssettings($opt);
$result = $service->groups->get('sucoursetest19032020@sabanciuniv.edu',$settings); 
echo "<pre>";
print_r($result);
echo "</pre>";

/*
if (count($results->getUsers()) == 0) {
  print "No users found.\n";
} else {
  print "Users:\n";
  foreach ($results->getUsers() as $user) {
    printf("%s (%s)\n", $user->getPrimaryEmail(),
        $user->getName()->getFullName());
  }
}