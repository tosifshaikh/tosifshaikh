<?php
namespace App\Controllers;

use MVC\Controller;
use App\Models\Task;
class TaskController extends Controller
{
    private $request;
    private $task;
    public function __construct() {
        $this->request = $_REQUEST;
        $this->task = new Task();
    }
    public function Task2()
    {
        if(isset($this->request['q'])) {
            echo $this->task->getTask2Grid($this->request['q']);
            exit;
        }

        $this->render('Tasks/Task2', ['data' =>  $this->task->getTask2Grid()]);
 
    }
    public function Task5() {
        if(isset($this->request['exportcsv'])) {
            $this->task->export();
        }
        $this->render('Tasks/Task5', ['data' =>  $this->task->getGrid(0),'school' => $this->task->getSchool()]);
    }
    public function Task5Grid()
    {
        echo $this->task->getGrid(0);
        exit;
    }
    public function Task5Save()
    {
        echo $this->task->SaveMembers($this->request['data'] ?? []);
       exit;
    }
    public function Task5ReportData()
    {
        echo $this->task->countNumMemberBySchool();
        exit;
    }
}
