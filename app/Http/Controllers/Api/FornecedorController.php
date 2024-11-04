<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ValidaDocumento;
use App\Models\Fornecedor;
use Illuminate\Support\Facades\Http;
use App\Repositories\FornecedorRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class FornecedorController extends Controller
{
    protected $fornecedorRepository;

    public function __construct(FornecedorRepositoryInterface $fornecedorRepository)
    {
        $this->fornecedorRepository = $fornecedorRepository;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cacheKey = 'fornecedores_page_' . $request->get('page', 1);

        $fornecedores = Cache::remember($cacheKey, 60, function () use ($request) {
            return $this->fornecedorRepository->all($request->all());
        });

        return response()->json($fornecedores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'required|string|unique:fornecedores,documento',
            'tipo_documento' => 'required|in:CPF,CNPJ',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'endereco' => 'nullable|string',
        ]);

        if ($validated['tipo_documento'] === 'CPF') {
            if (!ValidaDocumento::cpf($validated['documento'])) {
                return response()->json(['error' => 'CPF inválido.'], 422);
            }
        } else {
            if (!ValidaDocumento::cnpj($validated['documento'])) {
                return response()->json(['error' => 'CNPJ inválido.'], 422);
            }
        }

        $fornecedor = $this->fornecedorRepository->create($validated);

        return response()->json($fornecedor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $fornecedor = $this->fornecedorRepository->find($id);
        return response()->json($fornecedor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'required|string|unique:fornecedores,documento,' . $id,
            'tipo_documento' => 'required|in:CPF,CNPJ',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'endereco' => 'nullable|string',
        ]);

        if ($validated['tipo_documento'] === 'CPF') {
            if (!ValidaDocumento::cpf($validated['documento'])) {
                return response()->json(['error' => 'CPF inválido.'], 422);
            }
        } else {
            if (!ValidaDocumento::cnpj($validated['documento'])) {
                return response()->json(['error' => 'CNPJ inválido.'], 422);
            }
        }

        $fornecedor = $this->fornecedorRepository->update($id, $validated);

        return response()->json($fornecedor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->fornecedorRepository->delete($id);
        return response()->json(null, 204);
    }

    /**
     * Busca um fornecedor pelo documento (CPF/CNPJ).
     */
    public function buscarDocumento($documento)
    {
        $fornecedor = $this->fornecedorRepository->findByDocumento($documento);

        if ($fornecedor) {
            return response()->json($fornecedor);
        }

        if (ValidaDocumento::cnpj($documento)) {
            // CNPJ
            $response = Http::get("https://brasilapi.com.br/api/cnpj/v1/{$documento}");

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'CNPJ não encontrado.'], 404);
            }
        } elseif (ValidaDocumento::cpf($documento)) {
            // CPF
            return response()->json(['error' => 'Consulta de CPF não suportada.'], 400);
        } else {
            return response()->json(['error' => 'Documento inválido.'], 422);
        }
    }
}
