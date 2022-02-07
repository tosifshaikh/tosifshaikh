<?php 
// Base Controller
class Controller{
        protected $view;
		protected $model;
       
       public function view($viewModule,$viewName,$data=[]){		   
		   $this->view=new View($viewModule,$viewName,$data);
		   return $this->view;
	   }
	   public function model($modelName,$data=[]){
		   if(file_exists(MODEL_PATH.$modelName.'.php')){
			  $this->model=singletonClass::getInstance($modelName);
			  //$this->model= &getInstance($modelName);
		   }
	   }
}
?>