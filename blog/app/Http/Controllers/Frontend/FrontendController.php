<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    protected $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    protected function index()
    {
       $featuredProduct = $this->product->where([['trending','=',1],['is_delete','=',0]])->take(15)->get();
        return view('frontend.index',['featuredProduct' => $featuredProduct ]);
    }
}
