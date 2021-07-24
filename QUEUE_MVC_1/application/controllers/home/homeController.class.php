<?php
class homeController extends Controller {
        // Hold an instance of the class
  //  private static $instance;
        public function AddAction(){
              // if(isset($_REQUEST['act'])){
          //   print_r(homeModel::getInstance());exit;
              //  $mod1=new homeModel();
              //  $mod->processData($_REQUEST);
              // }
               
                 $this->loadView('home');
              }
//               // The singleton method
//     public static function singleton()
//     {
//         if (!isset(self::$instance)) {
//             $class=__CLASS__;
//             self::$instance = new $class;
//         }
//         return self::$instance;
//     }
    

}
?>