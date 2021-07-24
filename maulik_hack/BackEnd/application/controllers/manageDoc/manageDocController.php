<?php
class manageDocController extends Controller {
	public function getDocAction($dataArr=array())
	{	
		$this->model('manageDocModel');   
		http_response_code(200);
		$data = array();			
		$data = $this->model->getDocuments($dataArr);     
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
?>