<?php

namespace App\Http\Controllers;

use App\Models\Tipos_arquivos;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipos_arquivosRequest;
use App\Http\Requests\UpdateTipos_arquivosRequest;

class TiposArquivosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreTipos_arquivosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTipos_arquivosRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tipos_arquivos  $tipos_arquivos
     * @return \Illuminate\Http\Response
     */
    public function show(Tipos_arquivos $tipos_arquivos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tipos_arquivos  $tipos_arquivos
     * @return \Illuminate\Http\Response
     */
    public function edit(Tipos_arquivos $tipos_arquivos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTipos_arquivosRequest  $request
     * @param  \App\Models\Tipos_arquivos  $tipos_arquivos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTipos_arquivosRequest $request, Tipos_arquivos $tipos_arquivos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tipos_arquivos  $tipos_arquivos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipos_arquivos $tipos_arquivos)
    {
        //
    }
}
