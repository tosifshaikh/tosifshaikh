<?php
class uploadModel extends Model{
  public function savefile($fname="",$recid="")
  {
 
	$res_ar=$this->insert("MCC_Document",array("Filename"=>$fname,"Rec_id"=>$recid,"status"=>1));
   // $query = "insert into file (filename,rec_id,status) values (".$fname.",".$recid.",1)";
    //$arr = array($uname,$pass);
    //$cntVal=$this->db->fetchColumn($query,$arr,0);
    $cntVal=1;
    return ($cntVal>0)?1:0;
  }  
}

?>