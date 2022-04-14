<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SolutionResource;
use App\Models\Solution;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Solution::latest()->get();
        return response()->json([SolutionResource::collection($data), 'Data fetched.']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'master' => 'required|string|max:255',
            'slave' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $program = Solution::create([
            'master' => $request->master,
            'slave' => $request->slave
         ]);
        
        return response()->json(['Data created successfully.', new SolutionResource($program)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $program = Solution::find($id);
        if (is_null($program)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new SolutionResource($program)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Solution $solution)
    {
        $validator = Validator::make($request->all(),[
            'master' => 'required|string',
            'slave' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $solution->name = $request->name;
        $solution->desc = $request->desc;
        $solution->save();
        
        return response()->json(['Data updated successfully.', new SolutionResource($solution)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solution $solution)
    {
        $solution->delete();

        return response()->json('Data deleted successfully');
    }

    public function solution1(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'payload' => 'required|array|min:0',
            'payload.*' => 'required|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }
        $data = $validator->validated();
        $payload = $data['payload'];

        // Start Logic
        $temp_col = [];
        $col = [];
        for ($i=0; $i < count($payload); $i++) { 
            if (!in_array($payload[$i]['master'], $temp_col)) {
                array_push($temp_col, $payload[$i]['master']);
            }
        }
        for ($i=0; $i < count($temp_col); $i++) { 
            array_push($col, array('master' => $temp_col[$i], 'slave' => []));
        }
        for ($i=0; $i < count($col); $i++) { 
            for ($j=0; $j < count($payload); $j++) { 
                if ($col[$i]['master'] == $payload[$j]['master']) {
                    array_push($col[$i]['slave'], $payload[$j]['slave']);
                }
            }
        }
        return response()->json(['payload' => $col]);
    }

    public function solution2(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'payload' => 'required|array|min:0',
            'payload.*' => 'required|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }
        $data = $validator->validated();
        $payload = $data['payload'];

        // Start Logic
        $temp_col = [];
        $col = [];
        for ($i=0; $i < count($payload); $i++) { 
            if (!in_array($payload[$i]['master'], $temp_col)) {
                array_push($temp_col, $payload[$i]['master']);
            }
        }
        for ($i=0; $i < count($temp_col); $i++) { 
            $val = [];
            for ($j=0; $j < count($payload); $j++) { 
                if ($temp_col[$i] == $payload[$j]['master']) {
                    array_push($val, $payload[$j]['slave']);
                }
            }
            array_push($col, array($temp_col[$i] => $val));
        }
        
        return response()->json(['payload' => $col]);
        }
}
