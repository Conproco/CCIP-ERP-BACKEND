<?php

namespace Src\Product\Infrastructure\Persistence;

use Src\Product\Domain\Entities\ProductEntity;
use Src\Product\Domain\Repositories\ProductRepository;
use App\Models\Product;
use App\Models\Move;
use Illuminate\Support\Facades\DB;

class EloquentProductRepository implements ProductRepository
{
    public function find(int $id): ?ProductEntity
    {
        $product = Product::with('unit')->find($id);
        
        return $product ? $this->toEntity($product) : null;
    }

    public function findWithTrashed(int $id): ?ProductEntity
    {
        $product = Product::withTrashed()->with('unit')->find($id);
        
        return $product ? $this->toEntity($product) : null;
    }

    public function findByName(string $name): ?ProductEntity
    {
        $product = Product::where('name', $name)->first();
        
        return $product ? $this->toEntity($product) : null;
    }

    public function all(array $filters = []): array
    {
        $query = $this->applyFilters(Product::query(), $filters);
        
        return $query->get()
            ->map(fn($product) => $this->toEntity($product))
            ->toArray();
    }

    public function paginate(array $filters = [], int $perPage = 15): mixed
    {
        $query = Product::with('unit');

        $query = $this->applyFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    public function save(ProductEntity $product): ProductEntity
    {
        $data = [
            'name' => $product->name,
            'description' => $product->description,
            'primary_code' => $product->primaryCode,
            'secondary_code' => $product->secondaryCode,
            'sc_type' => $product->scType,
            'unit_id' => $product->unitId,
        ];

        $model = Product::create($data);
        
        return $this->toEntity($model->load('unit'));
    }

    public function update(int $id, array $data): ProductEntity
    {
        $product = Product::findOrFail($id);
        
        $updateData = [
            'name' => $data['name'],
            'description' => $data['description'],
            'primary_code' => $data['primary_code'],
            'secondary_code' => $data['secondary_code'],
            'sc_type' => $data['sc_type'],
            'unit_id' => $data['unit_id'],
        ];

        $product->update($updateData);
        
        return $this->toEntity($product->load('unit'));
    }

    public function delete(int $id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function restore(int $id): bool
    {
        $product = Product::withTrashed()->findOrFail($id);
        return $product->restore();
    }

    public function search(string $search, array $fields = [] , $includeTrashed=false): array
    {
        $query = Product::select('id', 'name', 'description', 'primary_code', 'secondary_code', 'sc_type', 'unit_id', 'created_at', 'updated_at', 'deleted_at')
            ->with('unit');
           //->whereNull('deleted_at'); // Solo productos activos

        if ($includeTrashed) {
            $query->withTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        if (!empty($fields) && !empty($search)) {
            $query->where(function ($q) use ($search, $fields) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'like', "%$search%");
                }
            });
        }

        return $query->orderBy('name')
            ->limit(50)
            ->get()
            ->map(fn($product) => $this->toEntity($product))
            ->toArray();
    }

    public function searchFirst(int $limit = 5): array
    {
        $firstProducts = Move::select('product_id', DB::raw('COUNT(*) as count'))
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->with(['product' => function ($query) {
                $query->select('id', 'name', 'description', 'primary_code', 'secondary_code', 'sc_type', 'unit_id', 'created_at', 'updated_at', 'deleted_at')
                      ->whereNull('deleted_at'); // Solo productos activos
            }, 'product.unit'])
            ->take($limit)
            ->get();

        $products = $firstProducts->map(function ($item) {
            if (!$item->product) {
                return null;
            }
            return $this->toEntity($item->product);
        })
        ->filter()
        ->values()
        ->toArray();

        // Si no hay productos desde moves (o están eliminados), devolver los más recientes
        if (empty($products)) {
            $products = Product::whereNull('deleted_at')
                ->with('unit')
                ->orderBy('created_at', 'desc')
                ->take($limit)
                ->get()
                ->map(fn($product) => $this->toEntity($product))
                ->toArray();
        }

        return $products;
    }

    private function applyFilters($query, array $filters): mixed
    {
        if (isset($filters['state']) && $filters['state'] === 'inactive') {
            $query->onlyTrashed();
        }

        if (isset($filters['searchQuery'])) {
            $searchQuery = $filters['searchQuery'];
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%$searchQuery%")
                    ->orWhere('secondary_code', 'like', "%$searchQuery%")
                    ->orWhere('primary_code', 'like', "%$searchQuery%");
            });
        }

        if (isset($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        if (isset($filters['sortBy']) && isset($filters['sortDirection'])) {
            $query->orderBy($filters['sortBy'], $filters['sortDirection']);
        }

        return $query;
    }

    private function toEntity(Product $product): ProductEntity
    {
        return ProductEntity::fromArray([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'primary_code' => $product->primary_code,
            'secondary_code' => $product->secondary_code,
            'sc_type' => $product->sc_type,
            'unit_id' => $product->unit_id,
            'unit_name' => $product->unit?->name ?? 'Sin unidad',
            'deleted_at' => $product->deleted_at?->toDateTimeString(),
            'created_at' => $product->created_at?->toDateTimeString(),
            'updated_at' => $product->updated_at?->toDateTimeString(),
        ]);
    }
}
