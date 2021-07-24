<?php
function getAllMccUser($docType,$clId,$compArr,$type)
{   
	global $adb;
	$dyArr = array("center_section_id"=>$docType,"airlinesId"=>$clId);
	$notesuseArr = array();
	$tempUsersArr = array();	
	if(count($compArr)>0){
	    $sql="SELECT userid,componentId FROM thirdparty_aircraft_access as a left join tbl_managedby as b on a.subclientid =b.clientid and a.componentId=b.tailid join fd_users as c on b.userid=c.id where a.componentId in (".implode(",",$compArr).") ";
	    $sql.=" and a.typeflg = ? and a.access_type=4 and b.comp_type =99 and c.inuserlist='Yes' group by userid,componentId";
	
	    $adb->query($sql,array('typeflg'=>$type));
	    while($adb->next_record()){
	        if($adb->f("userid")!=""){
	            $notesuseArr[$adb->f("componentId")][$adb->f("userid")] = $adb->f("userid");
	            $tempUsersArr[] = $adb->f("userid");
	        }
	    }
	     
	    $sql="SELECT subclientid,componentId FROM thirdparty_aircraft_access as a left join tbl_managedby as b on a.subclientid =b.clientid and a.componentId=b.tailid where a.componentId in (".implode(",",$compArr).")  ";
	    $sql.=" and a.typeflg = ? and a.access_type=4 and b.comp_type =99 group by subclientid,componentId ";
	    $adb->query($sql,array('typeflg'=>$type));
	    while($adb->next_record()){
	        if($adb->f("subclientid")!=""){
	            $notesuseArr[$adb->f("componentId")][$adb->f("subclientid")] = $adb->f("subclientid");
	            $tempUsersArr[] = $adb->f("subclientid");
	        }
	    }
	}
	
	$usersArr = array();
	if(count($tempUsersArr)>0){
	    $query=" SELECT id,level,dflag,CONCAT(fname,' ',lname) AS full_name,contact_name,id FROM fd_users WHERE id in (".implode(",",$tempUsersArr).") ";
	    $adb->query($query);
	    while($adb->next_record()){
	        if($adb->f("dflag") != 1) {
	            if($adb->f('level')==1 || $adb->f('level')==3){
	                $usersArr[$adb->f("id")]['name'] = $adb->f("full_name");
	                $usersArr[$adb->f("id")]['level'] = $adb->f("level");
	            } else if($adb->f('level')==5){
	                $usersArr[$adb->f("id")]['name'] = $adb->f("contact_name");
	                $usersArr[$adb->f("id")]['level'] = $adb->f("level");
	            }
	
	        }
	    }
	}
	
	$finalArr = array();
	foreach($notesuseArr as $compkey=>$compval){
	    foreach($compval as $uid=>$uval){
	        if($usersArr[$uval] && $usersArr[$uval]['level']==1){
	            $finalArr[1]["_".$uid] = $usersArr[$uval]['name'];
	        } else if($usersArr[$uval] && $usersArr[$uval]['level']==3){
	            $finalArr[0]["_".$uid] = $usersArr[$uval]['name'];
	        } else {
	            if($usersArr[$uval]){
	                $finalArr[2]["_".$uid] = $usersArr[$uval]['name'];
	            }
	        }
	
	    }
	}
	foreach($finalArr as $fkey=>$fval){
	    foreach($fval as $infKey=>$inFval){
	        asort($finalArr[$fkey][$infKey]);
	    }
	}
	return $finalArr;
	
	
}
?>