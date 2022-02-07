<?php 
//$tool_tip_text="FLYCHAT opening times:@Monday to Friday: 0900 - 1700 UK time@Saturday and Sunday: Closed@Bank Holidays: Closed";
function hooks_form_bigin($formname="frm",$method="post",$action="",$target="",$onsubmit="")
{
	$form="<table width='100%' border='0' cellspacing='0' cellpadding='0' class='tableMiddleBackground_roundedcorner'>
                            <tr>
                        		<td width='50%' align='left' valign='middle'>";   
	 
	 $form.="<form name='$formname' id='$formname' method='$method' action='$action' target='$target' onsubmit='$onsubmit' enctype='multipart/form-data'>";   
    return $form;
}


function hooks_form_end()
{
    
	$form="</form>
										</td>
									</tr>
							</table>";
	 return $form;	 
}


function hooks_getbutton($button="")
{
	global $addrightche, $editrightche,$deleterightche;
	$form="";
	//echo "asdasD".$addrightche;
    $form.="<table border='0' cellspacing='1' cellpadding='1' align='right'>
             <tr>";
			for($i=0;$i<count($button);$i++){
				  if($button[$i]=="REORDER"){	
						$form.="<td valign='middle'><input type='button' class='button' id='reorderBtn' disabled='disabled' onclick='loadGrid();' /></td>";
                  }
				  else if($button[$i]=="AUDIT"){	
						$form.="<td valign='middle'><input type='button' class='button' value='AUDIT TRAIL' onclick='fnaudit()' /></td>";
                  }
                  else if($button[$i]=="ADD" && $addrightche==1){	
					$form.="<td valign='middle'><input name='' id='addBtn' type='button' class='button' value='ADD' tabindex='5' onclick='return fnAdd();' /></td>";
                  }elseif($button[$i]=="EDIT" && $editrightche==1){      
            $form.="<td valign='middle'><input name='' id='editBtn' type='button' class='disbutton' value='EDIT' tabindex='6' onclick='return fnEdit();' disabled='disabled' /></td>";
                  }elseif($button[$i]=="DELETE" && $deleterightche==1){     
            $form.="<td valign='middle'><input name='' id='deleteBtn' type='button' class='disbutton' value='DELETE' tabindex='7' disabled='disabled' onclick='return fnDelete();' /></td>";
           		  }
				  elseif($button[$i]=="ARCHIVE" && $deleterightche==1){     
            $form.="<td valign='middle'><input name='' id='archiveBtn' type='button' class='disbutton' value='ARCHIVE' tabindex='7' disabled='disabled' onclick='return fnDelete();' /></td>";
           		  }
				  elseif($button[$i]=="SAVE" && ($addrightche=='1' || $editrightche=='1')){
            $form.="<td valign='middle'><input name='' id='saveBtn' type='button' class='disbutton' value='SAVE' tabindex='8' disabled='disabled' onclick='return fnSave();' /></td>";
            	  }elseif($button[$i]=="RESET"){ 
            $form.="<td valign='middle'><input name='' id='resetBtn' tabindex='9' type='button' class='button' value='RESET' onclick='return fnReset();'></td>";
            	  }elseif($button[$i]=="ADDMULTIPLEROW"){ 
            $form.="<td valign='middle'><input name='' id='multipleRowsBtn' tabindex='10' type='button' class='button' value='ADD MULTIPLE ROW' onclick='openMultipleRow();'></td>";
            	  }/*elseif($button[$i]=="FLYCHAT"){ 
            $form.="<td valign='middle' width='40'>";
			$tool_tip_text1	= _TOOL_TIP_TEXT;
			$tt=explode("@",$tool_tip_text1);
			$cnt_tt = count($tt);*/
			?>
			
             <?php 
			//$tipvar = "";
			//for($n=0;$n<$cnt_tt;$n++) { $tipvar .= ($tt[$n])."<br>"; }
			
			?>
            
            
            <?php
			
			/*$form.= "<div><div id='proactivechatcontainerzbag74sinu'  style='padding-top:9px; *padding-top:8px;' onMouseOver='Tip(\"".$tipvar."\")' onMouseOut='UnTip()'></div><table border='0' cellspacing='2' cellpadding='2'><tr><td align='center' id='swifttagcontainerzbag74sinu'><div style='display: inline;' id='swifttagdatacontainerzbag74sinu'></div></td> </tr><tr><td align='center'><div style='WIDTH: 100%; TEXT-ALIGN: center;'><span style='FONT-SIZE: 9px; FONT-FAMILY: Tahoma, Arial, Helvetica, sans-serif;'><a href='http://www.kayako.com/products/live-chat-software/' style='TEXT-DECORATION: none; COLOR: #000000' target='_blank'></span></div></td></tr></table></div> <script type='text/javascript'>var swiftscriptelemzbag74sinu=document.createElement('script');swiftscriptelemzbag74sinu.type='text/javascript';var swiftrandom = Math.floor(Math.random()*1001); var swiftuniqueid = 'zbag74sinu'; var swifttagurlzbag74sinu='http://gen2.kayako.com/visitor/index.php?/Default/LiveChat/HTML/HTMLButton/cHJvbXB0dHlwZT1jaGF0JnVuaXF1ZWlkPXpiYWc3NHNpbnUmdmVyc2lvbj00LjQwLjk4NiZwcm9kdWN0PUZ1c2lvbiZjdXN0b21vbmxpbmU9aHR0cCUzQSUyRiUyRmdlbjIua2F5YWtvLmNvbSUyRl9fc3dpZnQlMkZmaWxlcyUyRmZpbGVfZ3MxZGI3dm02OW84dzhuLnBuZyZjdXN0b21vZmZsaW5lPWh0dHAlM0ElMkYlMkZnZW4yLmtheWFrby5jb20lMkZfX3N3aWZ0JTJGZmlsZXMlMkZmaWxlX3o5aHAwZm01ZXV0czlnZC5wbmcmY3VzdG9tYXdheT1odHRwJTNBJTJGJTJGZ2VuMi5rYXlha28uY29tJTJGX19zd2lmdCUyRmZpbGVzJTJGZmlsZV94MDN6Nm1qYmowbXA1bmIucG5nJmN1c3RvbWJhY2tzaG9ydGx5PWh0dHAlM0ElMkYlMkZnZW4yLmtheWFrby5jb20lMkZfX3N3aWZ0JTJGZmlsZXMlMkZmaWxlX3ZxZnBlc3FmOGZxZTF0aS5wbmcKMTJhNjRiMzY4YTM2NjJiMDE5YjYxMTQzMjEwODViMjVjOTc1MTdlYw==';setTimeout('swiftscriptelemzbag74sinu.src=swifttagurlzbag74sinu;document.getElementById('swifttagcontainerzbag74sinu').appendChild(swiftscriptelemzbag74sinu);',1);</script>";*/
			
			
			
		/*	$form.= '<div><div id="proactivechatcontainerzbag74sinu"  style="padding-top:9px; *padding-top:8px;" onMouseOver="Tip(\''.$tipvar.'\')" onMouseOut="UnTip()"></div><table border="0" cellspacing="2" cellpadding="2"><tr><td align="center" id="swifttagcontainerzbag74sinu"><div style="display: inline;" id="swifttagdatacontainerzbag74sinu"></div></td> </tr><tr><td align="center"><div style="WIDTH: 100%; TEXT-ALIGN: center;"><span style="FONT-SIZE: 9px; FONT-FAMILY: Tahoma, Arial, Helvetica, sans-serif;"><a href="http://www.kayako.com/products/live-chat-software/" style="TEXT-DECORATION: none; COLOR: #000000" target="_blank"></a></span></div></td></tr></table></div> <script type="text/javascript">var swiftscriptelemzbag74sinu=document.createElement("script");swiftscriptelemzbag74sinu.type="text/javascript";var swiftrandom = Math.floor(Math.random()*1001); var swiftuniqueid = "zbag74sinu"; var swifttagurlzbag74sinu="http://gen2.kayako.com/visitor/index.php?/Default/LiveChat/HTML/HTMLButton/cHJvbXB0dHlwZT1jaGF0JnVuaXF1ZWlkPXpiYWc3NHNpbnUmdmVyc2lvbj00LjQwLjk4NiZwcm9kdWN0PUZ1c2lvbiZjdXN0b21vbmxpbmU9aHR0cCUzQSUyRiUyRmdlbjIua2F5YWtvLmNvbSUyRl9fc3dpZnQlMkZmaWxlcyUyRmZpbGVfZ3MxZGI3dm02OW84dzhuLnBuZyZjdXN0b21vZmZsaW5lPWh0dHAlM0ElMkYlMkZnZW4yLmtheWFrby5jb20lMkZfX3N3aWZ0JTJGZmlsZXMlMkZmaWxlX3o5aHAwZm01ZXV0czlnZC5wbmcmY3VzdG9tYXdheT1odHRwJTNBJTJGJTJGZ2VuMi5rYXlha28uY29tJTJGX19zd2lmdCUyRmZpbGVzJTJGZmlsZV94MDN6Nm1qYmowbXA1bmIucG5nJmN1c3RvbWJhY2tzaG9ydGx5PWh0dHAlM0ElMkYlMkZnZW4yLmtheWFrby5jb20lMkZfX3N3aWZ0JTJGZmlsZXMlMkZmaWxlX3ZxZnBlc3FmOGZxZTF0aS5wbmcKMTJhNjRiMzY4YTM2NjJiMDE5YjYxMTQzMjEwODViMjVjOTc1MTdlYw==";setTimeout("swiftscriptelemzbag74sinu.src=swifttagurlzbag74sinu;document.getElementById(\'swifttagcontainerzbag74sinu\').appendChild(swiftscriptelemzbag74sinu);",1);</script>';*/			
			?>
           
           
			<?php
			
          
             // }
			 // else{}
			 }
         $form.="</tr></table>";
    return $form;
}
function flychat()
{
	 include_once(INCLUDE_PATH."flychatfixscript.php");
}
?>