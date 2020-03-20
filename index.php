<?php
    require_once('list.php');
    require_once('GGroup.php');
    $gGroup = new GGroups();
   
    print date('H:i:s').'<br/>';
    $time = date("Ymd-His");
    if(!isset($_GET['offset']) || empty($_GET['limit'])){
        die("Offset ve limit alanları boş geçilemez!");
    }
    $offset = $_GET['offset'];
    $limit = $_GET['limit'];
    $file = fopen("log/${time}_${offset}_${limit}.txt",'w');
    
    print count($list).'<br />';

    //print '<pre>'.print_r(array_keys($list),true).'</pre>'; die();

    $arr = array_slice($list,$offset,$limit);
    
    print '<pre>'.print_r(array_keys($arr),true).'</pre>';

    foreach ($arr as $key => $value) {
        $groupNameInst = $key."_inst";
        $groupNameStu = $key."_stu";
        $groupOwner = "onlinecourses@sabanciuniv.edu";
        /*
        echo "INSTRUCTOR GRUBU OLUŞTUR -> ".$groupNameInst."<br/><br/>";
        $resCreate = $gGroup->createGroup($groupNameInst);
        fwrite($file,json_encode($resCreate).",\n");
        echo "ADDMEMBER OWNER -> ".$groupOwner." EKLE <br/><br/>";
        $resAddMember = $gGroup->addMember($groupNameInst,$groupOwner,'OWNER');
        fwrite($file,json_encode($resAddMember).",\n");
        
        $instructors = $value['instructor'];
        
        foreach ($instructors as $email) {
            echo "ADDMEMBER MEMBER INSTRUCTOR ".$email."<br/><br/>";
            $resAddMemberInst = $gGroup->addMember($groupNameInst,$email);
            fwrite($file,json_encode($resAddMemberInst).",\n");
        }
        
        
        echo "STUDENT GRUBU OLUŞTUR -> ".$groupNameStu."<br/><br/>";
        
        $resCreateSG = $gGroup->createGroup($groupNameStu);
        fwrite($file,json_encode($resCreateSG).",\n");
        echo "ADDMEMBER OWNER -> ".$groupOwner." EKLE <br/><br/>";
        $addMemberOwner = $gGroup->addMember($groupNameStu,$groupOwner,'OWNER');
        fwrite($file, json_encode($addMemberOwner).",\n");
        */
        $students = $value['students'];
        foreach ($students as $email) {
            echo "ADDMEMBER MEMBER STUDENT ".$email."<br/><br/>";
            $addMemberStu = $gGroup->addMember($groupNameStu,$email);
            fwrite($file, json_encode($addMemberStu).",\n");
        }
        
        fwrite($file,"\n");
        echo "<BR/>";
        

    }
    fclose($file);
    print date('H:i:s').'<br/>';

?>