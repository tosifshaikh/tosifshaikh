<?php
class TestingController extends Controller{
    public function __construct()
    {
        echo 'inside'.__CLASS__;
    }
    public function index()
    {
        echo 'index'.__CLASS__;
    }
}