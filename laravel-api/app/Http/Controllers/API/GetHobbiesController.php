<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hobby;

class GetHobbiesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $hobbies = Hobby::select('id', 'name')->get();
        return response()->json($hobbies);
    }
}
