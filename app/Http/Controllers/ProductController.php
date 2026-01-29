<?php
    
namespace App\Http\Controllers;
    
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
    
class ProductController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $products = Product::latest()->paginate(5);

        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('products.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:191',
            'model' => 'required|string|max:191',
            'type' => 'required|integer',
            'unit' => 'required|string|max:50',
            'description' => 'required|string'
        ]);

        $data = [
            'producttype_id' => $request->input('type'),
            'title' => trim($request->input('brand') . ' ' . $request->input('model')),
            'brand' => $request->input('brand'),
            'slug' => Str::slug($request->input('brand') . ' ' . $request->input('model')),
            'model' => $request->input('model'),
            'unit' => $request->input('unit'),
            'is_serial' => $request->has('serial') ? 1 : 0,
            'is_license' => $request->has('license') ? 1 : 0,
            'is_taggable' => $request->has('taggable') ? 1 : 0,
            'is_consumable' => $request->has('is_consumable') ? 1 : 0,
            'description' => $request->input('description')
        ];

        $product = Product::create($data);

        if ($request->ajax()) {
            return response()->json($product);
        }

        return redirect()->route('products.index')->with('success','Product created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product): View
    {
        return view('products.show',compact('product'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product): View
    {
        return view('products.edit',compact('product'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:191',
            'model' => 'required|string|max:191',
            'type' => 'required|integer',
            'unit' => 'required|string|max:50',
            'description' => 'required|string'
        ]);

        $data = [
            'producttype_id' => $request->input('type'),
            'title' => trim($request->input('brand') . ' ' . $request->input('model')),
            'brand' => $request->input('brand'),
            'slug' => Str::slug($request->input('brand') . ' ' . $request->input('model')),
            'model' => $request->input('model'),
            'unit' => $request->input('unit'),
            'is_serial' => $request->has('serial') ? 1 : 0,
            'is_license' => $request->has('license') ? 1 : 0,
            'is_taggable' => $request->has('taggable') ? 1 : 0,
            'is_consumable' => $request->has('is_consumable') ? 1 : 0,
            'description' => $request->input('description')
        ];

        $product->update($data);

        if ($request->ajax()) {
            return response()->json($product);
        }

        return redirect()->route('products.index')->with('success','Product updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
    
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}