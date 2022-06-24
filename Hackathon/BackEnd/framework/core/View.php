<?php 
class View
{
    protected $view_file;
	protected $view_data;
        public function __construct($viewModule,$viewName,$view_data)
      {
		  $this->view_file=$viewModule.DS.$viewName;
		  $this->view_data=$view_data;

      }
	  public function render()
	  {
		  
		  if(file_exists(VIEW_PATH.$this->view_file.'.php')){
			  include VIEW_PATH.$this->view_file.'.php';
		  }
	  }
}
?>