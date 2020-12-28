<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudController extends Controller
{
    protected $service;

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request
     * @return object
     */
    public function index(Request $request) {
        $payload = $this->service->getList($request->all());
        return response()->json($payload);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function store(Request $request) {
        return response()->json($this->service->save($this->prepareData($request->all())));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return object
     */
    public function update(Request $request, $id) {
        return response()->json($this->service->update($id, $this->prepareData($request->all())));
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareData($data) {
        return $data;
    }



}
