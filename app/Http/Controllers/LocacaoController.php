<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocacaoRequest;
use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    protected $locacao;
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        // if ($request->has('atributos_modelo')) {
        //     $locacaoRepository->selectAtributosRegistros('modelo:'.$request['atributos_modelo']);
        // }else{
        //     $locacaoRepository->selectAtributosRegistros('modelo');
        // }

        if ($request->has('filtro')) {
            $locacaoRepository->filtro($request['filtro']);
        }

        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request['atributos']);
        }

        return response()->json($locacaoRepository->getResultado());
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
    public function store(LocacaoRequest $request)
    {
        $locacao = $this->locacao->create($request->all());

        return response()->json($locacao);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(['error' => 'locaçao pesquisado não encontrada!!!'], 404);
        }
        return response()->json($locacao); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locacao $locacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocacaoRequest $request, $id)
    {
        $locacao = $this->locacao->find($id);
        
        if ($locacao === null) {
            return response()->json(['error' => 'Não foi possível realizar a atualização de locaçao, locaçao solicitada não encontrada!!!'], 404);
        }

        $locacao->update($request->all());

        return response()->json($locacao); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(['error' => 'Não foi possível realizar a exclusão de Locação, Locação pesquisado não encontrada!!!'], 404);
        }
        $locacao->delete();

        return response()->json('Locação removido com sucesso!!!');
    }
}
