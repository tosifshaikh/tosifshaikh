<?php
class loginController extends Controller {
        
		public function login()
		{
			
		}
		
		public function checkLoginAction($dataArr=array())
         {
           //header("Content-Type: application/json; charset=UTF-8");
		   //echo json_encode(array('success' => 1));exit;
		   $this->model('loginModel');   
           //header("Content-Type: application/json; charset=UTF-8");
               http_response_code(200); 
		      $cnt = $this->model->isloginCheck($dataArr[2],$dataArr[3]);     
		      header('Content-Type: application/json');
			  echo json_encode(array('success' => ($cnt>0)?1:0));
         }

    

}
?>