<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Producttype;
use App\Models\Stock;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ProductTypeController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:product-type-list|product-type-create|product-type-edit|product-type-delete', ['only' => ['index','store']]);
         $this->middleware('permission:product-type-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-type-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-type-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
            $types = Producttype::with('parent')->withCount('stocks')->latest()->get();
         return view('backend.admin.product-type')->with(compact('types'));
    }
    public function store(Request $request)
    {
        $this->validate($request,array(
            'name' => 'required|max:255|unique:producttypes,name',
            'parent_id' => 'nullable|integer|exists:producttypes,id',
            'prefix' => 'nullable|string|max:4|regex:/^[A-Za-z0-9]+$/|unique:producttypes,prefix',
            'asset_class' => 'required|in:FIXED,CONSUMABLE',
        ));
        //$slug  = str_slug($request->name);
        $type = new Producttype();
        $type->name = $request->name;
        $type->prefix = $request->prefix ? strtoupper(trim($request->prefix)) : null;
        $type->asset_class = $request->asset_class;
        $type->slug = Str::slug($request->name);
        $type->parent_id = $request->parent_id;
        $type->save();
        UserLogHelper::log('create', 'Created a new Product Type : '. $type->id );
        Toastr::success(' Succesfully Saved ', 'Success');
        // if request is AJAX return JSON for frontend modals
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($type);
        }
        return redirect()->back();
    }


    public function edit($id)
    {
        $type = Producttype::with('parent')->find($id);
        return $type;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'max:255', Rule::unique('producttypes', 'name')->ignore($id)],
            'parent_id' => ['nullable', 'integer', 'exists:producttypes,id'],
            'prefix' => ['nullable', 'string', 'max:4', 'regex:/^[A-Za-z0-9]+$/', Rule::unique('producttypes', 'prefix')->ignore($id)],
            'asset_class' => ['required', Rule::in(['FIXED', 'CONSUMABLE'])],
        ]);

        $parentId = $request->parent_id;
        if ($parentId && (int) $parentId === (int) $id) {
            Toastr::error('A type cannot be its own parent', 'Error');
            return redirect()->back();
        }

        if ($parentId && $this->createsCircularReference((int) $id, (int) $parentId)) {
            Toastr::error('Circular hierarchy is not allowed', 'Error');
            return redirect()->back();
        }

        //$slug  = str_slug($request->name);
        $type = Producttype::find($id);
        $previousClass = strtoupper((string) ($type->asset_class ?? 'FIXED'));
        $newClass = strtoupper((string) $request->asset_class);

        if ($previousClass !== $newClass && Stock::where('producttype_id', $id)->exists()) {
            Toastr::error('Asset class cannot be changed after stock entries exist for this type', 'Error');
            return redirect()->back();
        }

        $type->name = $request->name;
        $type->prefix = $request->prefix ? strtoupper(trim($request->prefix)) : null;
        $type->asset_class = $newClass;
        $type->slug = Str::slug($request->name);
        $type->parent_id = $parentId;
        $type->save();

        // Keep product-level flag aligned with type-level class when class changes are allowed.
        if ($previousClass !== $newClass) {
            Product::where('producttype_id', $id)
                ->update(['is_consumable' => $newClass === 'CONSUMABLE' ? 1 : 2]);
        }

        UserLogHelper::log('update', 'updated a  Product Type : '. $type->id );

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $type = Producttype::find($id);
        if (!$type) {
            Toastr::error('Product type not found', 'Error');
            return redirect()->back();
        }

        if ($type->children()->exists()) {
            Toastr::error('Delete child types first', 'Error');
            return redirect()->back();
        }

        $type->delete();

        UserLogHelper::log('delete', 'Deleted a  Product Type : '. $type->id );

        Toastr::success('Succesfully Deleted ', 'Success');

        return redirect()->back();
    }

    private function createsCircularReference(int $typeId, int $candidateParentId): bool
    {
        $currentParentId = $candidateParentId;
        while ($currentParentId) {
            if ($currentParentId === $typeId) {
                return true;
            }

            $parent = Producttype::select('id', 'parent_id')->find($currentParentId);
            if (!$parent) {
                break;
            }

            $currentParentId = $parent->parent_id ? (int) $parent->parent_id : 0;
        }

        return false;
    }
}
