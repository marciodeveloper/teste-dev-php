<?php

namespace App\Repositories;

use App\Models\Fornecedor;

class FornecedorRepository implements FornecedorRepositoryInterface
{
    public function all($filters)
    {
        $query = Fornecedor::query();

        if (isset($filters['nome'])) {
            $query->where('nome', 'like', '%' . $filters['nome'] . '%');
        }

        if (isset($filters['tipo_documento'])) {
            $query->where('tipo_documento', $filters['tipo_documento']);
        }

        if (isset($filters['ordenarPor'])) {
            $query->orderBy($filters['ordenarPor'], $filters['ordem'] ?? 'asc');
        }

        return $query->paginate(10);
    }

    public function find($id)
    {
        return Fornecedor::find($id);
    }

    public function create(array $data)
    {
        return Fornecedor::create($data);
    }

    public function update($id, array $data)
    {
        $fornecedor = Fornecedor::find($id);
        $fornecedor->update($data);

        return $fornecedor;
    }

    public function delete($id)
    {
        return Fornecedor::destroy($id);
    }

    public function findByDocumento($documento)
    {
        return Fornecedor::where('documento', $documento)->first();
    }

}