<?php
namespace Fadiramzi99\HRLPackage\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MainController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Hello, this is your message!'
        ]);
    }
}