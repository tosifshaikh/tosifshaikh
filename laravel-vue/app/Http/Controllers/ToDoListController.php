<?php

namespace App\Http\Controllers;

use App\Models\ToDoListCategory;
use App\Models\ToDoListTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToDoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $ToDoListCategory;
    private $ToDoListTask;

    public function __construct(ToDoListCategory $ToDoListCategory, ToDoListTask $toDoListTask)
    {
        $this->ToDoListCategory = $ToDoListCategory;
        $this->ToDoListTask = $toDoListTask;
    }

    public function prepareData($TaskCategory = [], $task = [])
    {
        //dd($TaskCategory);
        foreach ($TaskCategory as $k => $value) {
            $TaskCategory[$k]['tasks'] = [];
            foreach ($task as $taskey => $taskValue) {
                // echo $value['category_id'];
                if ($value['id'] == $taskValue['category_id']) {
                    $task[$taskey]['order'] = 1;
                    $task[$taskey]['type'] = 1;
                    $TaskCategory[$k]['tasks'][] = $task[$taskey];
                }
            }
        }

        return $TaskCategory;
    }

    public function index()
    {
        // $TaskCategory = $this->ToDoListCategory->select('id as category_id','category as category_name')->get()->keyBy('category_id')->toArray();
        $TaskCategory = $this->ToDoListCategory->select('id', 'category as category_name')->get()->toArray();
        // $taskList =  $this->toDoListTask->select('id as task_id','category_id','category as category_name')->get()->toArray();
        $task = $this->ToDoListTask->select(
            'id',
            'category_id',
            'priority',
            'title',
            'Description',
            'user_id',
            'updated_at'
        )->get()->toArray();
        $data = $this->prepareData($TaskCategory, $task);
        //  $TaskCategory->task=$task;
        /* $data = DB::table('todolist_category')->select("todolist_category.id as category_id",
             "todolist_category.category as category_name",
             "tasks.title as task_title",
             "tasks.Description as task_description",
             "tasks.priority as task_priority",
             "tasks.user_id as task_user_id")->leftJoin("tasks", "tasks.category_id", "=", "todolist_category.id")->get();*/
        return response()->json($data, config('constant.STATUS.SUCCESS_CODE'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->ToDoListTask->category_id = $request->categoryID;
        $this->ToDoListTask->title = $request->title;
        $this->ToDoListTask->Description = $request->description;
        $this->ToDoListTask->priority = $request->priority;
        $this->ToDoListTask->type = $request->type;
        $this->ToDoListTask->user_id = 1;
        if (!$this->ToDoListTask->save()) {
            return response()->json(['message' => __('message.Error Msg'),
                'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'), ], config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json(['data' => $this->ToDoListTask, 'message' => __('message.Task.Add')], config('constant.STATUS.SUCCESS_CODE'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ('Category-Update' == $id) {
            return $this->CategoryUpdate($request);
        }
        $request->validate([
            'title' => 'required',
            'categoryID' => 'required|int',
            'id' => 'required|int',
        ]);
        /* $ToDoListTask = array();
         $ToDoListTask->fill([
             'category_id'
         ]);*/
        $this->ToDoListTask = $this->ToDoListTask->findOrFail($id);
        $this->ToDoListTask->category_id = $request->categoryID;
        $this->ToDoListTask->title = $request->title;
        $this->ToDoListTask->Description = $request->description;
        $this->ToDoListTask->priority = $request->priority;
        $this->ToDoListTask->type = $request->type;
        $this->ToDoListTask->user_id = 1;

        if (!$this->ToDoListTask->update()) {
            return response()->json(['message' => __('message.Error Msg'),
                'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'), ], config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json(['data' => $this->ToDoListTask, 'message' => __('message.Task.Edit')], config('constant.STATUS.SUCCESS_CODE'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function CategoryUpdate(Request $request)
    {
        $data = $this->ToDoListTask->find($request->id);
        $data->category_id = $request->toCategory;
        if (!$data->update()) {
            return response()->json(['message' => __('message.Error Msg'),
                'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'), ], config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json(['data' => $data->toArray(), 'message' => __('message.Task.Edit')], config('constant.STATUS.SUCCESS_CODE'));
    }

    public function destroy($id)
    {
        $data = $this->ToDoListTask->find($id);
        if (!$data->delete()) {
            return response()->json(['message' => __('message.Error Msg'),
                'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'), ], config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json(['data' => $data->toArray(), 'message' => __('message.Task.Delete')], config('constant.STATUS.SUCCESS_CODE'));
    }
}
