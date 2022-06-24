<?php
class homeController extends Controller {
        public function AddAction($module='',$method='')
         {
         	
				$this->model('homeModel');
				$this->view('home','homeview',[
				 'id' =>$module,'name'=>$method,'data'=>$this->model->SaveDate(),'title'=>'ADD ACTION PAGE'
				 ]);
				$this->view->render();
              }

    

}
?>