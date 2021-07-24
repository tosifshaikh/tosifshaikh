<?php 
//$tool_tip_text="FLYCHAT opening times:@Monday to Friday: 0900 - 1700 UK time@Saturday and Sunday: Closed@Bank Holidays: Closed";
function hooks_form_bigin($formname="frm",$method="post",$action="",$target="",$onsubmit="")
{
	$form="
							<table width='100%' border='0' cellspacing='0' cellpadding='0' class='tableMiddleBackground_roundedcorner'>
                            <tr>
                        		<td align='left' valign='middle'>";   
	 
	 $form.="<form name='$formname' id='$formname' method='$method' action='$action' target='$target' onsubmit='$onsubmit' enctype='multipart/form-data'>";   
    return $form;
}


function hooks_form_end()
{
    
	$form="</form>
										</td>
									</tr>
							</table>
						";
	 return $form;	 
}

function hooks_getbutton($bt,$tblAlign="right")
{
	if((is_array(current($bt)) || is_numeric(current($bt))))
	{
	    global $addrightche, $editrightche,$deleterightche;
	    /*
	     Format is :
	     value#onclick#desabled#id#name#type#extra
	     */
	    $commonArr = array(
	        "1"=>array(
	            "onclick"=>"fnAdd()",
	            "id"=>"addBtn"
	        ),
	        "2"=>array(
	            "onclick"=>"fnEdit()",
	            "disabled"=>"disabled",
	            "id"=>"editBtn"
	        ),
	        "3"=>array(
	            "onclick"=>"fnDelete()",
	            "disabled"=>"disabled",
	            "id"=>"deleteBtn"
	        ),
	        "6"=>array(
	            "onclick"=>"fnSave()",
	            "disabled"=>"disabled",
	            "id"=>"saveBtn"
	        ),
	        "5"=>array(
	            "onclick"=>"fnReset()",
	            "id"=>"resetBtn"
	        ),
	        "4"=>array(
	            "onclick"=>"fnaudit()",
	        ),
	        "245"=>array(
	            "onclick"=>"loadGrid()",
	            "disabled"=>"disabled",
	            "id"=>"reorderBtn"
	        ),
	        "41"=>array(
	            "onclick"=>"fnDelete()",
	            "disabled"=>"disabled",
	            "id"=>"archiveBtn"
	        ),
	        "246"=>array(
	            "onclick"=>"openMultipleRow()",
	            "id"=>"multipleRowsBtn"
	        ),
	        "14"=>array(
	            "onclick"=>"window.close()",
	            "id"=>"closeBtn"
	        ),
	        "234"=>array(
	            "onclick"=>"fnReactive()",
	            "disabled"=>"disabled",
	            "id"=>"reactivebtn"
	        )
	    );
	    
	    $btStr = '';
	    $flgGeneralBtn = false;
	    $pre = '<td valign="middle">';
	    $post = '</td>';
	    if(is_array($bt))
	    {
	        $flgGeneralBtn = true;
	        foreach($bt as $key => $ButtonId)
	        {
	            if(is_array($ButtonId)) {
	                $buttonProperty = $ButtonId;
	                $ButtonId = $key;
	            }
	            else{
	                $buttonProperty = array();
	                if(in_array($ButtonId, array_keys($commonArr)))
	                {
	                    if($ButtonId == '1' && $addrightche != 1)
	                        continue;
	                    if($ButtonId == '2' && $editrightche != 1)
	                        continue;
	                    if($ButtonId == '3' && $deleterightche != 1)
	                        continue;
	                    if($ButtonId == '6' && ($addrightche != 1 && $editrightche != 1 && $deleterightche != 1))
	                        continue;
	                    if(isset($commonArr[$ButtonId])) {
	                        $buttonProperty = $commonArr[$ButtonId];
	                    }
	                    	
	                }
	            }
	            $btStr .= $pre;
	            $btStr .= createButton($ButtonId,$buttonProperty);
	            $btStr .= $post;
	        }
	    }
	    else
	    {
	        $btStr .= $pre;
	        $btStr .= createButton($bt);
	        $btStr .= $post;
	    }
	    if($flgGeneralBtn)
	        echo '<table border="0" cellspacing="1" cellpadding="1" align="'.$tblAlign.'"><tr>'.$btStr.'</tr></table>';
	    else
	        echo $btStr;
		
	}
	else
	{
	    hooks_getbutton1($bt);
	}
	
}
function createButton($ButtonId,$StrArr)
{
	global $lang;
	$val = "";
	if(isset($lang[$ButtonId]) && $lang[$ButtonId] != "") {
	    $val =  ' value="'.stripslashes($lang[$ButtonId]).'" ';
	}
	
	$cls = "class='button'";
	if(isset($StrArr['disabled']) && $StrArr['disabled'] == "disabled") {
	    $cls = ' class="disbutton" ';
	}
	
	$dis = ""; 
	if(isset($StrArr['disabled']) && $StrArr['disabled'] != "") {
	    $dis = ' disabled="disabled" ';
	}
	
	$type = " type='button' ";
	if(isset($StrArr['type']) &&  $StrArr['type'] != "") {
	    $type = " type='".$StrArr['type']."' ";
	}
	
	$extra = "";
	if(isset($StrArr['extra']) && $StrArr['extra'] != "") {
	    $extra = $StrArr['extra'];
	    if(strpos(strtolower($extra),'class') !== false) {
	        $cls = '';
	    }
	}
	
	$id = "";
	if(isset($StrArr['id']) && $StrArr['id'] != "") {
	    $id = " id='".$StrArr['id']."' ";
	}
	
	$name = "";
	if(isset($StrArr['name']) && $StrArr['name'] != "") {
	    $name = " name='".$StrArr['name']."' ";
	}
	
	$onclick = "";
	if(isset($StrArr['onclick']) && $StrArr['onclick'] != "") {
	    $onclick = " onclick=\"".$StrArr['onclick']."\" ";
	}
	
	return "<input {$type} {$val} {$cls} {$id}  {$name} {$dis} {$onclick}  {$extra} />";
}

/*
Remove Below Code after Multi Language Work is uploaded ON SIT.
*/
function hooks_getbutton1()
{
	global $addrightche, $editrightche,$deleterightche;
	/*
	Format is :
	value#onclick#desabled#id#name#type
	*/
	$commonArr = array(
	'ADD'=>'ADD#fnAdd()##addBtn#',
	'EDIT'=>'EDIT#fnEdit()#disabled#editBtn#',
	'DELETE'=>'DELETE#fnDelete()#disabled#deleteBtn#',
	'SAVE'=>'SAVE#fnSave()#disabled#saveBtn#',
	'RESET'=>'RESET#fnReset()##resetBtn#',
	'AUDIT'=>'AUDIT TRAIL#fnaudit()###',
	'REORDER'=>'REORDER#loadGrid()#disabled#reorderBtn#',
	'ARCHIVE'=>'ARCHIVE#fnDelete()#disabled#archiveBtn#',
	'ADDMULTIPLEROW'=>'ADD MULTIPLE ROW#openMultipleRow()##multipleRowsBtn#',
	'CLOSE'=>'CLOSE#window.close();##closeBtn#',
	'REACTIVE'=>'REACTIVE#fnReactive();#disabled#reactivebtn#',
	);
	$btStr = '';
	$args = func_get_args();
	$flgGeneralBtn = false;
	$pre = '<td valign="middle">';
	$post = '</td>';
	foreach($args as $bt)
	{
		if(is_array($bt))
		{
			
			$flgGeneralBtn = true;
			foreach($bt as $b)
			{
				
				if(in_array($b,array_keys($commonArr)))
				{
					if($b == 'ADD' && $addrightche != 1)
					continue;
					if($b == 'EDIT' && $editrightche != 1)
					continue;
					if($b == 'DELETE' && $deleterightche != 1)
					continue;
					if($b == 'SAVE' && ($addrightche != 1 && $editrightche != 1 && $deleterightche != 1))
					continue;
					
					$b = $commonArr[$b];
					
				}
				$btStr .= $pre;
				$btStr .= createButton1($b);
				$btStr .= $post;
			}
		}
		else
		{
			$btStr .= $pre;
			$btStr .= createButton1($bt);
			$btStr .= $post;
		}
	}
	if($flgGeneralBtn)
	echo '<table border="0" cellspacing="1" cellpadding="1" align="right"><tr>'.$btStr.'</tr></table>';
	else
	echo $btStr;
}
function createButton1($str)
{
	global $lang;
	$strArr = array();
	$strArr = explode("#",$str);
	$val = $strArr[0];
	$cls = ($strArr[2] == 'disabled')?'disbutton':'button';
	$dis = ($strArr[2] == 'disabled')?'disabled="disabled"':'';
	$type = ($strArr[5] == "")?'button':$strArr[5];
	$extra = ($strArr[6] == "")?$strArr[6]:'';
	return '<input type="'.$type.'" value="'.$val.'" class="'.$cls.'" id="'.$strArr[3].'" name="'.$strArr[4].'" '.$dis.' onclick="'.$strArr[1].'"  '.$extra.' />';
}
?>