<?php
class manageDocModel extends Model{
	  public function getDocuments($params)
	  {
		$resultArr = array();
		$resultArr=$this->selectAll("MCC_Document","*",array("rec_id"=>$params[2]));
				
		
		for($i=1;$i<=5;$i++){
			//$resultArr[]=array('fileid'=>$i,'fileName'=>'image'.$i,'status'=>3);	
		}
		return $resultArr;
	  }
}
?>