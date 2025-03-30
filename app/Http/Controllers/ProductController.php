<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): JsonResponse
	{
		$products = Product::all();

		return response()->json($products, 200);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'index'       => 'nullable|integer|min:0',
			'description' => 'nullable|string',
			'name'        => 'required|string',
			'price'       => 'required|integer|min:0',
			'tag'         => 'nullable|string',
			'tag_color'   => 'nullable|string',
			'vat_percent' => 'required|integer|min:0|max:100',
		]);

		$product = Product::create($validated);

		return response()->json($product, 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id): JsonResponse
	{
		$product = Product::findOrFail($id);

		$validated = $request->validate([
			'index'       => 'nullable|integer|min:0',
			'description' => 'nullable|string',
			'name'        => 'required|string',
			'price'       => 'required|integer|min:0',
			'tag'         => 'nullable|string',
			'tag_color'   => 'nullable|string',
			'vat_percent' => 'required|integer|min:0|max:100',
		]);

		$product->update($validated);

		return response()->json($product, 200);
	}


	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id): JsonResponse
	{
		$product = Product::findOrFail($id);
		$product->delete();

		return response()->json(null, 204);
	}
}
