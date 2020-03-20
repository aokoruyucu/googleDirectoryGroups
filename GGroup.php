<?php
require '../drive/vendor/autoload.php';


/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class GGroups{
    public $client;
    public $service;

    public function __construct(){
        
        $this->client = new Google_Client();
        $this->client->setApplicationName('G Suite Directory API PHP Quickstart');
        $this->client->setScopes(Google_Service_Directory::ADMIN_DIRECTORY_USER_READONLY);

        $this->client->setAuthConfig('credentials.json');
        $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');
            $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        if ($this->client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($this->client->getRefreshToken()) {
            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $this->client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
            $this->client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0770, true);
        }
        
        file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
       }

        $this->service = new Google_Service_Directory($this->client);
        

    }


    public function createGroup($name){
        $opt_data = [
        'email' => $name.'@sabanciuniv.edu',
        'name' => $name
        ];
        try{
            $obj = new Google_Service_Directory_Group($opt_data);
            $results = $this->service->groups->insert($obj);
            $this->setGroupSettings($name);
            $arr = ['response'=>"success",'name'=>$name,'fonksiyon'=>'creategroup'];
            return $arr;
        }catch(Exception $e){
            $arr = ['response'=>"error",'message'=>$e->getMessage(),'name'=>$name,'fonksiyon'=>'creategroup'];
            return $arr;
        }
        
    }

    public function setGroupSettings($name){
        $groupssettingsService = new Google_Service_Groupssettings($this->client);
        $groupssettingsService = new Google_Service_Groupssettings($this->client);
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
        $groups = $groupssettingsService->groups->update($name.'@sabanciuniv.edu',$grp);
    }

    public function addMember($name,$email,$role="MEMBER"){
        $options = [
            'email' => $email,
            'role' => $role,
            'delivery_settings' => 'NONE'
        ];
        try{
            $members = new Google_Service_Directory_Member($options);
            $results = $this->service->members->insert($name.'@sabanciuniv.edu',$members);
            $arr = ['response'=>"success",'email'=>$email,'rol'=>$role,'name'=>$name,'fonksiyon'=>'addmember'];
            return $arr;
        }catch(Exception $e){
            $arr = ['response'=>"error",'message'=>$e->getMessage(),'email'=>$email,'rol'=>$role,'name'=>$name,'fonksiyon'=>'addmember'];
            return $arr;
        }
        
    }


}
