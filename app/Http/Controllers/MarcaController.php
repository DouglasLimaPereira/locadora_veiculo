<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarcaRequest;
use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    protected $marca;
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if ($request->has('atributos_modelos')) {
            $marcaRepository->selectAtributosRegistros('modelos:'.$request['atributos_modelos']);
        }else{
            $marcaRepository->selectAtributosRegistros('modelos');
        }

        if ($request->has('filtro')) {
            $marcaRepository->filtro($request['filtro']);
        }

        if ($request->has('atributos')) {
            $marcaRepository->selectAtributos($request['atributos']);
        }

        return response()->json($marcaRepository->getResultado());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarcaRequest $request)
    {
        $imagem = $request['imagem'];
        $imagem_nome = $imagem->store('logos', 'public');
        
        $marca = $this->marca->create([
            'nome' => $request['nome'],
            'imagem' => $imagem_nome,
        ]);

        return $marca;        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if ($marca === null) {
            return response()->json(['error' => 'Marca pesquisado não encontrada!!!'], 404);
        }
        return $marca; 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MarcaRequest $request, $id)
    {
        $marca = $this->marca->find($id);
        
        if ($marca === null) {
            return response()->json(['error' => 'Não foi possível realizar a atualização de Marca, Marca solicitada não encontrada!!!'], 404);
        }

        if ($request['imagem'] != '') {
            Storage::disk('public')->delete($marca->imagem);
        }
        $imagem = $request['imagem'];
        $imagem_nome = $imagem->store('logos', 'public');
        
        $marca->update([
            'nome' => $request['nome'],
            'imagem' => $imagem_nome,
        ]);

        return $marca; 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['error' => 'Não foi possível realizar a exclusão de Marca, Marca pesquisado não encontrada!!!'], 404);
        }
        
        if ($marca['imagem'] != '') {
            Storage::disk('public')->delete($marca->imagem);
        }
        $marca->delete();
    }
}
