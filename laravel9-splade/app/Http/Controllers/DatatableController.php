<?php

namespace App\Http\Controllers;

use App\Models\Datatable_Column;
use Illuminate\Http\Request;
//use ProtoneMedia\Splade\Facades\Splade;
use ProtoneMedia\Splade\SpladeTable;

class DatatableController extends Controller
{
    //
    // public function __construct() {

    // }
    public function index()
    {
        return view('datatable',[
            'data' => SpladeDatatable::class
               // ->column(key:'column',label:'text')
                //->column('email')
            //    ->paginate(15),

            //'users' => Users::class,
        ]);
    }

}
