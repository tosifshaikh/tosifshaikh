<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo  $this->view_data['title'] ?></title>
<script src="<?php echo MODULE_JS ?>home.js"></script>
<head>
<body>
inside home view
<form id='frm' method='post'>
<table width='100%'>
 <tr><td><div id='dynamicGrid'></div>
<!--<table cellspacing=4 cellpadding=4>
<tr>
        <td align='left' valign='top'>Work Item:</td>
        <td align='left' valign='top'><input type='text' name='wino' id='wino' placeholder='WI NO'/></td>
        <td align='left' valign='top'>Title:</td>
        <td align='left' valign='top'><input type='text' name='wititle' id='wititle' placeholder='Title'/></td>
        <td align='left' valign='top'>Programmer/TL:</td>
        <td align='left' valign='top'><input type='text' name='programmer' id='programmer' placeholder='Programmer/TL' /></td>
</tr>
<tr>
        <td align='left' valign='top'>Select Type:</td>
        <td align='left' valign='top' id='selType' placeholder='Select Type'>
        <select id='selectType' name='selectType'>
        <option value=''>Select Type</option>
        <option value='0'>Bug</option>
        <option value='1'>Enhancement</option>
        </select>
        </td>  
        <td align='left' valign='top'>WI Size:</td>
        <td align='left' valign='top' id='selSize'>
        <select id='selectType' name='selectSize'>
        <option value=''>Select Size</option>
        <option value='0'>Small</option>
        <option value='1'>Medium</option>
        <option value='2'>Big</option>
        </select>
        </td>
        <td align='left' valign='top'>Enter Estimation Date:</td> 
        <td align='left' valign='top' ><input type="text" id="estimationDate" placeholder='Estimation Date'></td> 
</tr>
<tr>
        <td align='left' valign='top'>Enter Comments</td>
        <td align='left' valign='top'><textarea id='comments' name='comments' placeholder='Comments'></textarea></td>
        <td align='left' valign='top'>Page Location</td>
        <td align='left' valign='top'><input id='location' name='location' placeholder='Location' /></td>
</tr>
<tr>
        <td align='left' valign='top'><input type='button' id='btnSave' value='SAVE'></td>
        <td align='left' valign='top'><input type='button' id='btnReset' value='RESET'></td>
        
</tr>
</table>-->
 </td></tr>
 <tr><td><div id='dataGrid'></div></td></tr>      
</table>
</form>
</body>
</html>