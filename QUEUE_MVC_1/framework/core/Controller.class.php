<?php 
// Base Controller
class Controller{
        use SingletonTrait;
		protected $model=null;
		protected $params=array();
      //  private static $instances = array();
        public function loadView($view='index')
        {
                include CURR_VIEW_PATH . $view.".php";    
        }
		public function setValues($model,$params){
			$this->model=$model;
			$this->params=$params;
		}
		
//         public static function getInstance() 
//     {
//         $cls = get_called_class(); // late-static-bound class name
//         if (!isset(self::$instances[$cls])) {
//             self::$instances[$cls] = new static;
//         }
//         return self::$instances[$cls];

//     }
}
?>