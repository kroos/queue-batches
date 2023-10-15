<?php

namespace App\Http\Controllers;

// load model
use App\Models\Interview;

// load validation
use App\Http\Requests\StoreInterviewRequest;
use App\Http\Requests\UpdateInterviewRequest;

// for controller output
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

// load facade
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class InterviewController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		return view('index');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreInterviewRequest $request)/*: RedirectResponse*/
	{
		// return response()->json('ok');
		// dd($request->all());
		if($request->file('csv')){
			$file = $request->file('csv')->getClientOriginalName();
			$currentDate = Str::random(10);
			$fileName = $currentDate . '_' . $file;
			// Store File in Storage Folder
			$request->csv->storeAs('public/csv', $fileName);
			// storage/app/uploads/file.png
			// Store File in Public Folder
			// $request->csv->move(public_path('uploads'), $fileName);
			// public/uploads/file.png
			$data += ['file' => $fileName];
		}
		$l = Interview::create($data);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Interview $interview): View
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Interview $interview): View
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateInterviewRequest $request, Interview $interview): RedirectResponse
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Interview $interview): JsonResponse
	{
		//
	}
}
