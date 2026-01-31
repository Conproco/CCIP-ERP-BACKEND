<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OperationProductsImport;
use App\Exports\Project\Expenses\OperationProductsStructureExport;
use Src\Product\Application\Services\ProductService;
use Src\Product\Application\DTOs\CreateProductDTO;
use Src\Product\Application\DTOs\UpdateProductDTO;
use Src\Product\Application\DTOs\ProductFiltersDTO;
use Src\Product\Application\DTOs\ProductResponseDTO;
use Src\Units\Application\Services\UnitService;


class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly UnitService $unitService
    ) {}

    public function search(Request $request)
    {
        $search = (string) ($request->query('search') ?? '');
        $fields = $request->query('fields');
        $includeTrashed = filter_var($request->query('include_trashed', false), FILTER_VALIDATE_BOOLEAN);
        if (is_string($fields)) {
            $fields = explode(',', $fields);
        }

        if (empty($fields)) {
            $fields = ['name', 'primary_code', 'secondary_code'];
        }
        $products = $this->productService->search($search, $fields, $includeTrashed);
        
        return response()->json($products);
    }

    public function searchFirst()
    {
        $firstProducts = $this->productService->searchFirst(5);
        
        return response()->json($firstProducts);
    }

    public function index(Request $request)
    {
        $units = $this->unitService->execute();

        $data = [
            'units' => $units
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        };
    }

    public function getProducts(Request $request)
    {
        $filters = ProductFiltersDTO::fromRequest($request->all());
        
        $products = $this->productService->paginate($filters, 15);
        
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products')],
            'description' => 'nullable|string',
            'primary_code' => 'nullable|string|max:255',
            'secondary_code' => 'nullable|string|max:255',
            'sc_type' => 'nullable|string|max:50',
            'unit_id' => 'required|exists:units,id'
        ]);

        $dto = CreateProductDTO::fromRequest($validateData);
        $product = $this->productService->create($dto);
        $response = ProductResponseDTO::fromEntity($product);
        
        return response()->json($response->toArray(), 200);
    }

    public function update(Request $request, int $id)
    {
        $validateData = $request->validate([
            'name' => ['sometimes','required', 'string', 'max:255', Rule::unique('products')->ignore($id)],
            'description' => 'sometimes|string',
            'primary_code' => 'sometimes|string|max:255',
            'secondary_code' => 'sometimes|string|max:255',
            'sc_type' => 'sometimes|string|max:50',
            'unit_id' => 'sometimes|exists:units,id'
        ]);
        $currentProduct = $this->productService->find($id);
        $fullData = array_merge([
            'name' => $currentProduct->name,
            'description' => $currentProduct->description,
            'primary_code' => $currentProduct->primaryCode,
            'secondary_code' => $currentProduct->secondaryCode,
            'sc_type' => $currentProduct->scType,
            'unit_id' => $currentProduct->unitId,
        ], $validateData);
        $dto = UpdateProductDTO::fromRequest($id, $fullData);
        $product = $this->productService->update($dto);
        $response = ProductResponseDTO::fromEntity($product);
        
        return response()->json($response->toArray(), 200);
    }

    public function disable(int $id)
    {
        $this->productService->delete($id);
        return response()->json(['message' => 'Producto deshabilitado correctamente'], 200);
    }

    public function restore(int $id)
    {   
        try{
            
            $this->productService->restore($id);
            return response()->json(['message' => 'Producto restaurado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al restaurar el producto: ' . $e->getMessage()], 500);
        }
    }

    public function OperationImport(Request $request)
    {
        $request->validate([
            'file' => 'sometimes|file|mimes:xlsx,xls,csv'
        ]);
        try {
            $import = new OperationProductsImport();
            Excel::import($import, $request->file('file'));
            return response()->json($import->getResults(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function downloadStructure()
    {
        return Excel::download(new OperationProductsStructureExport, 'Estructura-Carga-Productos.xlsx');
    }
}
