<?php 
//$tool_tip_text="FLYCHAT opening times:@Monday to Friday: 0900 - 1700 UK time@Saturday and Sunday: Closed@Bank Holidays: Closed";
function hooks_form_bigin($formname="frm",$method="post",$action="",$target="",$onsubmit="")
{
	$form="<table width='100%' border='0' cellspacing='0' cellpadding='0' id='pink_bg'>
            <tr>
				<td width='5' height='5' align='left' valign='bottom'><img src='".IMAGE_PATH."tableTopLeftArc.gif'></td>
				<td class='pink_topborder'></td>
				<td width='5' height='5' align='right' valign='bottom'><img src='".IMAGE_PATH."tableTopRightArc.gif'></td>
            </tr>
            <tr>
                <td class='pink_leftborder'> </td>
                <td>
				    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
               		    <td align='left'>
							<table width='100%' border='0' cellspacing='0' cellpadding='0'>
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
							</table>
							</td>
						</tr>
					</table> 
				</td>
				<td class='pink_rightborder'></td>
			</tr>
            <tr>
                      <td width='5' height='5' align='left' valign='bottom'><img src='".IMAGE_PATH."tableBottomLeftArc.gif' /></td>
                      <td class='pink_bottomborder'></td>
                      <td width='5' height='5' align='right' valign='bottom'><img src='".IMAGE_PATH."tableBottomRightArc.gif' /></td>
            </tr>
		 </table>";
	 return $form;	 
}


function hooks_getbutton($button="")
{
	global $addrightche, $editrightche,$deleterightche;
	$form="";
	//echo $addrightche;
    $form.="<table border='0' cellspacing='1' cellpadding='1' align='right'>
             <tr>";
			for($i=0;$i<count($button);$i++){
				  if($button[$i]=="REORDER"){	
						$form.="<td valign='middle'><input type='image' id='reorderBtn' src='".IMAGE_PATH."des_reorder_blt.png' disabled='disabled' onclick='loadGrid();' /></td>";
                  }
				  else if($button[$i]=="AUDIT"){	
						$form.="<td valign='middle'><input type='image' src='".IMAGE_PATH."auditrails_blt.png' onclick='fnaudit()' /></td>";
                  }
                  else if($button[$i]=="ADD" && $addrightche==1){	
					$form.="<td valign='middle'><input name='' id='addBtn' type='image' src='".IMAGE_PATH."addButton.png' tabindex='5' onclick='return fnAdd();' /></td>";
                  }elseif($button[$i]=="EDIT" && $editrightche==1){      
            $form.="<td valign='middle'><input name='' id='editBtn' type='image' src='".IMAGE_PATH."editButton_dis.png' tabindex='6' onclick='return fnEdit();' disabled='disabled' /></td>";
                  }elseif($button[$i]=="DELETE" && $deleterightche==1){     
            $form.="<td valign='middle'><input name='' id='deleteBtn' type='image' tabindex='7' disabled='disabled' src='".IMAGE_PATH."deleteButton_dis.png' onclick='return fnDelete();' /></td>";
           		  }
				  elseif($button[$i]=="ARCHIVE" && $deleterightche==1){     
            $form.="<td valign='middle'><input name='' id='archiveBtn' type='image' tabindex='7' disabled='disabled' src='".IMAGE_PATH."archive_deciable_blt.png' onclick='return fnDelete();' /></td>";
           		  }
				  elseif($button[$i]=="SAVE" && ($addrightche=='1' || $editrightche=='1')){
            $form.="<td valign='middle'><input name='' id='saveBtn' type='image' tabindex='8' disabled='disabled' src='".IMAGE_PATH."saveButton_dis.png' onclick='return fnSave();' /></td>";
            	  }elseif($button[$i]=="RESET"){ 
            $form.="<td valign='middle'><input name='' id='resetBtn' tabindex='9' type='image' src='".IMAGE_PATH."resetButton.png' width='73' height='32' onclick='return fnReset();'></td>";
            	  }elseif($button[$i]=="ADDMULTIPLEROW"){ 
            $form.="<td valign='middle'><input name='' id='multipleRowsBtn' tabindex='10' type='image' src='".IMAGE_PATH."addmultiplerows_blt.png' width='165' height='32' onclick='openMultipleRow();'></td>";
            	  }elseif($button[$i]=="FLYCHAT"){ 
            $form.="<td valign='middle' width='40'>";
			$tool_tip_text1	= _TOOL_TIP_TEXT;
			$tt=explode("@",$tool_tip_text1);
			$cnt_tt = count($tt);
            $form.="<div id='proactivechatcontaineremwxgydkzk' style='padding-top:0px;' onMouseOver=\"Tip('";
			for($n=0;$n<$cnt_tt;$n++) { $form.= ($tt[$n]);$form.= "<br>"; }
			$form.="')\" onMouseOut='UnTip()'></div>
            	      <table border='0' cellspacing='0' cellpadding='0'>
                      <tr>
                          <td align='center' id='swifttagcontaineremwxgydkzk'><div style='display: inline;' id='swifttagdatacontaineremwxgydkzk' onMouseOver=\"Tip('";
						  for($n=0;$n<$cnt_tt;$n++) { $form.= ($tt[$n]);$form.= "<br>"; }
						  $form.="')\" onMouseOut='UnTip()'></div>
                          </td>
                      </tr>
                  </table>
                 </div>
<script type=\"text/javascript\">var swiftscriptelememwxgydkzk=document.createElement(\"script\");swiftscriptelememwxgydkzk.type=\"text/javascript\";var swiftrandom = Math.floor(Math.random()*1001); var swiftuniqueid = \"emwxgydkzk\"; var swifttagurlemwxgydkzk=\"http://gen2.kayako.com/visitor/index.php?/Default/LiveChat/HTML/HTMLButton/cHJvbXB0dHlwZT1jaGF0JnVuaXF1ZWlkPWVtd3hneWRremsmdmVyc2lvbj00LjAxLjIwNCZwcm9kdWN0PUZ1c2lvbiZjdXN0b21vbmxpbmU9aHR0cCUzQSUyRiUyRmdlbjIua2F5YWtvLmNvbSUyRl9fc3dpZnQlMkZmaWxlcyUyRmZpbGVfd3QxazB6MnJxdW55aXo2LmdpZiZjdXN0b21vZmZsaW5lPWh0dHAlM0ElMkYlMkZnZW4yLmtheWFrby5jb20lMkZfX3N3aWZ0JTJGZmlsZXMlMkZmaWxlX2dqYjFnNHNma243aXQ0di5naWYmY3VzdG9tYXdheT1odHRwJTNBJTJGJTJGZ2VuMi5rYXlha28uY29tJTJGX19zd2lmdCUyRmZpbGVzJTJGZmlsZV9ma3d3MDdoYXlweWhtN2suZ2lmJmN1c3RvbWJhY2tzaG9ydGx5PWh0dHAlM0ElMkYlMkZnZW4yLmtheWFrby5jb20lMkZfX3N3aWZ0JTJGZmlsZXMlMkZmaWxlX2F1cXB3YTVvN2sxcjZhOS5naWYKMTE4MDNmOTI4Mzk0OTU3ODEwNTZlOTJkNzVhMGI3NjBjODM0MTliNQ==\";setTimeout(\"swiftscriptelememwxgydkzk.src=swifttagurlemwxgydkzk;document.getElementById('swifttagcontaineremwxgydkzk').appendChild(swiftscriptelememwxgydkzk);\",1);</script></td>";
              }
			  else{}
			 }
         $form.="</tr></table>";
    return $form;
}
?>