<?php

namespace App\Http\Controllers;

use App\menu;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    private $menu;
    public function __construct(menu $menu)
    {
        $this->menu = $menu;
    }

    public function getmenu($request = null)
    {
        $data = $this->menu->where('is_active',1)->get();
        return response()->json($data , Response::HTTP_OK);
    }
}
