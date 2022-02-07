<?php
$client_id=$_REQUEST['client_id'];
$notesUser=$db->getNotesUser($client_id);
$notes=$db->getNotesData($_REQUEST['main_id']);
if(count($notes['userid'])>0)
{
	$userNames= $db->getUserName($notes['userid']);
	
	foreach($userNames as $key=>$val)
	{
		$notesUser['username'][$key]=$val;
	}
	
}
$mainValArr['MainUserArr'] = $notesUser['MainUserArr'];
$mainValArr['username'] = $notesUser['username'];
$mainValArr['UserLevel'] = $notesUser['level'];
$mainValArr['NoteData'] = $notes;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo JS_PATH;?>freeze.js<?php echo QSTR; ?>"></script>
<script type="text/javascript" language="javascript">
Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}
var docObj=eval(<?php echo json_encode($mainValArr); ?>);
var res_users=docObj.NoteData['resp_user'];
var rec_id = <?php echo $_REQUEST['main_id'] ?>;
var type = <?php echo $_REQUEST['type']; ?>;
var UserID = '<?php echo $_SESSION["UserId"];?>';
var user_name ="<?php echo addslashes($_SESSION["User_FullName"]);?>";
var userTypeArr=new Array();
userTypeArr[3]="FLYdocs Users";
userTypeArr[1]="Main Client Users";
userTypeArr[5]="Clients Users";

var userComboArr=new Array();
userComboArr[3]="flydocs";
userComboArr[1]="airlines";
userComboArr[5]="client";

var flagArr=new Array();
flagArr[0]="internal";
flagArr[1]="client";
flagArr[2]="flydocs";

function getUserName(id)
{
	return docObj.username[id];
}

function createUserLov(flg)
{
	
	var table=''
	var MainUserArr=docObj.MainUserArr;
	
	
	for(userLevel in  userTypeArr)
	{
		if(userLevel=='unique' || userLevel=='indexOf')
			continue;
		 if(flg==0 && userLevel==5)
			continue;
		 if(flg==2 &&  (userLevel==1 || userLevel==5))
			continue;
		
		
		
		  table+='<select name="'+flagArr[flg]+'_'+userComboArr[userLevel]+'" id="'+flagArr[flg]+'_'+userComboArr[userLevel]+'" multiple="multiple" size="15" onChange="getRespUser('+flg+')" >';
		  table+=' <option value=""  disabled="disabled"  class="select1">Select '+userTypeArr[userLevel]+'</option>';
		
		if(MainUserArr && MainUserArr[userLevel])
		{
			
		  for(X in  MainUserArr[userLevel])
		  {
			if(X=='unique' || X=='indexOf')
			continue;
			
			  if(userLevel==1 || userLevel==5)
			  table+='<option disabled="disabled"><strong>'+X+'</strong></option>';
			  	if(userLevel!=1)
				{
				
						var userIds=MainUserArr[userLevel][X];
						
						var tempval=userIds.split(",");
						
						for(var Z in tempval)
						{
							if(Z=='unique' || Z=='indexOf')
							continue;
							
							var id=tempval[Z]
							var sel='';
							
							if(res_users && res_users[id] && res_users[id]!='')
								{
									//var tempid=res_users[id];
									
									//if(id==tempid)
									{
										var sel="selected='selected'"
									}
							}
							table+='<option '+sel+' value="'+id+'">'+getUserName(id)+'</option>';
						}
				}
				else
				{
				
					for(var Y in MainUserArr[userLevel][X])
					{
					
							if(Y!=0)
							table+='<option class="red_font" disabled="disabled">'+Y+'</option>';
							var userIds=MainUserArr[userLevel][X][Y];
						
							
							var tempval=userIds.split(",");
							for(var Z in tempval)
							{
								if(Z=='unique' || Z=='indexOf')
								continue;
								var id=tempval[Z];
								var sel='';
								if(res_users && res_users[id] && res_users[id]!='')
								{
									//var tempid=res_users[id];
									
									//if(id==tempid)
									{
										var sel="selected='selected'"
									}
								}
								table+='<option '+sel+' value="'+id+'">'+getUserName(id)+'</option>';
							}
						
						
					}
				}
		  }
		}
		  table+='</select>';
	}
	
		

	table+='<span  id="'+flagArr[flg]+'_responsible_div"> </span>';

	document.getElementById('USERLOV_div').innerHTML=table;
	
	return true;
	
}


$(document).ready(function()
{	

  try{
	 	createUserLov(0);
		 getRespUser(0);
		
	  //getRespUser(2);
		 		
	 }
	catch(e)
	{
		alert(e)
		ErrorMsg(e,'loadGrid on page load');
	}
	
});	
function SelectAllReceiver(note_type)
{
	
	
 if(document.getElementById("selectall"+flagArr[note_type]+"receiver").checked)
 {
 	
	if(document.getElementById("internal_flydocs"))
	{
		 var elm = document.getElementById("internal_flydocs");
		 for(i=1;i<elm.options.length;i++)
		 {
			  if(elm.options[i].disabled == false)
			  {
				elm.options[i].selected=true;
			  }
		 } 	
	}
	if(note_type==0)
	{
		if(document.getElementById(flagArr[note_type]+"_airlines"))
		{
			 var elm = document.getElementById(flagArr[note_type]+"_airlines");
			 for(i=1;i<elm.options.length;i++)
			 {
				  if(elm.options[i].disabled == false)
				  {
					elm.options[i].selected=true;
				  }
			 } 	
		}
		document.getElementById("selectallflydocsreceiver").disabled='disabled';
		document.getElementById("selectallflydocsreceiver").checked=false
	}
	
	
	/*if(document.getElementById(note_type+"_client"))
	{
		 var elm = document.getElementById(note_type+"_client");
		 for(i=1;i<elm.options.length;i++)
		 {
			  if(elm.options[i].disabled == false)
			  {
				elm.options[i].selected=true;
			  }
		 } 	
	}*/
 }
 else
 {
 	if(document.getElementById("internal_flydocs"))
	{
		 var elm = document.getElementById("internal_flydocs");
		 for(i=1;i<elm.options.length;i++)
		 {
			  if(elm.options[i].disabled == false)
			  {
				elm.options[i].selected=false;
			  }
		 } 	
	}
	if(document.getElementById(flagArr[note_type]+"_airlines"))
	{
		 var elm = document.getElementById(flagArr[note_type]+"_airlines");
		 for(i=1;i<elm.options.length;i++)
		 {
			  if(elm.options[i].disabled == false)
			  {
				elm.options[i].selected=false;
			  }
		 } 	
	}
	
	if(note_type==0)
	{
		document.getElementById("selectallflydocsreceiver").disabled='';
		document.getElementById("selectallflydocsreceiver").checked=false;
	}
	/*if(document.getElementById(note_type+"_client"))
	{
		 var elm = document.getElementById(note_type+"_client");
		 for(i=0;i<elm.options.length;i++)
		  {
			  if(elm.options[i].disabled == false)
			  {
			  	elm.options[i].selected=false;
			  }
		  } 
	}*/
 }
	
	  getRespUser(0);
		
	

}


function getRespUser(flg)
{
	var Mainstr='';
	
	
	//res_users=new Object();
	for (x in userTypeArr)
	{
		
		if(x=='unique' || x=='indexOf')
		continue;
		var optstr='';
		
		var obj=document.getElementById(flagArr[flg]+"_"+userComboArr[x]);
		if(obj)
		{
			for(var i=0;i<obj.options.length;i++)
			{
				
				var id =obj.options[i].value;
				var sel=obj.options[i].selected;
				
				var tempid=Array();
				if(res_users && res_users[id] && res_users[id]!='')
				{
					var tempid=res_users[id];
				}
				
				/*if(tempid==1 && sel==false)
				{
					
					delete res_users[id];
				}*/
				
				if(sel==true)
				{
					
					var sel='';
					if(tempid==1)
					{
						var sel='selected="selected"';
					}
					
					optstr+='<option '+sel+'  value="'+id+'">'+getUserName(id)+'</option>';
				}
			
			}
		}
		if(optstr!='')
		{
			Mainstr+='<option value=""  disabled="disabled"  class="select1">Select '+userTypeArr[x]+'</option>';
			Mainstr+=optstr;
		}
	}
	
	var table='<select name="responsible_'+flagArr[flg]+'" id="responsible_'+flagArr[flg]+'" multiple="multiple" size="15" >';
	table+='<option style="color:#FF0000;font-weight:bold" disabled="disabled">Assign Responsibility To:</option>';
	table+=Mainstr;
	table+='</select>'
	
	document.getElementById(flagArr[flg]+'_responsible_div').innerHTML=table;
	
}

function save_note(note_type)
{
	
		var noteVal=new Object();
		var NotesObj=new Object();
		
		
		
		if( !document.getElementById("responsible_"+flagArr[note_type]) || document.getElementById("rsponsible_"+flagArr[note_type])=='')
		{
			alert("You need to select a recipient for your  Note") ;
			return false;
		}
		else if( document.getElementById("responsible_"+flagArr[note_type]) && document.getElementById("responsible_"+flagArr[note_type]).value=="" )
		{
			alert("Please Select Responsible Persons") ;
			return false;
		}
		
		
		//noteVal['notes']=noteText;
		noteVal['admin_id']=UserID;
		noteVal['data_main_id']=rec_id;
		noteVal['doc_note_type']=(note_type==0) ? 0 : 2 ;
		noteVal['notes_type']=0;
		noteVal['type']=type;
		
		var noteReciever=new Object();
		var i=0;
		$( "#responsible_"+flagArr[note_type]+" option" ).each(function(index, element) {
			
			if( $(this).attr('disabled')==false)
			{
				noteReciever[i]=new Object();
				noteReciever[i]['receiver']=this.value;
				noteReciever[i]['data_man_id']=rec_id;
				
				if($(this).attr('selected') )
				{
					noteReciever[i]['responsblity']=1;
				}
				
				i++;
			}
        });
	
		
		NotesObj['notesdata'] = new Object();	
		NotesObj['notesdata']=noteVal;
		NotesObj['noteReciever']=noteReciever;
		window.opener.document.getElementById('tempHidden').value=JSON.stringify(NotesObj);
		window.close();
	
}

</script>
</head>
<body>
<table width="100%" height="100%" cellpadding="0" cellspacing="0" align="center" class="whitebackgroundtable">  <tr>
    <td valign="top" class="whitemiddle_tbl"><table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
	<td height="30"><?php include(INCLUDE_PATH."logo.php")?></td>
</tr>
        <tr><td height="45" class="MainheaderFont"> <?php echo ucfirst("Assign Note/ Query"); ?></td></tr>
       <tr>
    <td align="left" valign="top" id="searchDiv"><div id="myPrintWinDiv" style="background-color:#ffffff">
                    <table width="100%" border="0">
                      <tr>
                        <td colspan="2" align="left" valign="top">
						
                         		    <span id="USERLOV_div">
                                      
                                  </span>
                                    <span id= "responsible_div">       			
                                      </span> 
                                  </td>
                                 
                      </tr>
                       <tr valign="top">
                <td width="16%" align="left" ><input type="checkbox" name="selectallinternalreceiver" id="selectallinternalreceiver" value="All" onClick="SelectAllReceiver(0)"> <strong>Select All Users </strong></td>
                <td width="84%" align="left" ><input type="checkbox" name="selectallflydocsreceiver" id="selectallflydocsreceiver" value="onlyfd" onClick="SelectAllReceiver(2);"> 
                <strong>Select Only FLYdocs Users </strong>
                &nbsp;&nbsp;&nbsp;
                
                <span id="login_check"></span>
                </td>
                </tr>
                       <tr valign="top">
                         <td align="left" height="42" valign="bottom">
						 <?php
                            echo hooks_getbutton(array("21" => array("onclick" => "save_note(0);")),"left");
                         ?>
                         </td>
                         <td ></td>
                       </tr>
                    </table>
    </div></td>
  </tr>
        
      </table>
    </td>
  </tr>
</table>
</body>
</html>