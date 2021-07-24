<?php
class uploadController extends Controller {
        public function uploadimgAction($dataArr=array())
         {
           	$uploadpath = "public/images/";
			$encoded_file = $_POST['file'];
			$decoded_file = base64_decode($encoded_file);
			$filename = $_POST['filename'];
			file_put_contents($uploadpath.$filename,$decoded_file);
			$this->model('uploadModel'); 
			$cnt = $this->model->savefile($filename,$_POST['rec_id']);   
			echo json_encode(array('success' => 1));exit;
		}

    

}
?>