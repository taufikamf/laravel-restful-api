<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Program;
use App\Http\Resources\ProgramResource;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Program::latest()->get();
        return response()->json([ProgramResource::collection($data), 'Data fetched.']);
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
        $validator = Validator::make($request->all(), [
            'id_product' => 'required|string',
            'productName' => 'required|string',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $program = Program::create([
            'id_product' => $request->id_product,
            'productName' => $request->productName,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return response()->json(['Data created successfully.', new ProgramResource($program)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $program = Program::find($id);
        if (is_null($program)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new ProgramResource($program)]);
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
    public function update(Request $request, Program $program)
    {
        $validator = Validator::make($request->all(),[
            'id_product' => 'required|string',
            'productName' => 'required|string',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $program->id_product = $request->id_product;
        $program->productName = $request->productName;
        $program->price = $request->price;
        $program->quantity = $request->quantity;
        $program->save();

        return response()->json(['Data updated successfully.', new ProgramResource($program)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return response()->json('Data deleted successfully');
    }
}
