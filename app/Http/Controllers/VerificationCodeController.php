<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVerificationCodeRequest;
use App\Http\Requests\UpdateVerificationCodeRequest;
use App\Models\VerificationCode;

class VerificationCodeController extends Controller
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
     * @param  \App\Http\Requests\StoreVerificationCodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVerificationCodeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VerificationCode  $verificationCode
     * @return \Illuminate\Http\Response
     */
    public function show(VerificationCode $verificationCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VerificationCode  $verificationCode
     * @return \Illuminate\Http\Response
     */
    public function edit(VerificationCode $verificationCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVerificationCodeRequest  $request
     * @param  \App\Models\VerificationCode  $verificationCode
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVerificationCodeRequest $request, VerificationCode $verificationCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VerificationCode  $verificationCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(VerificationCode $verificationCode)
    {
        //
    }
}
