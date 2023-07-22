<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeloRequest;
use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    protected $modelo;
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $modeloRepository = new ModeloRepository($this->modelo);

        if ($request->has('atributos_marca')) {
            $modeloRepository->selectAtributosRegistros('marca:id, '.$request['atributos_marca']);
        }else{
            $modeloRepository->selectAtributosRegistros('marca');
        }

        if ($request->has('filtro')) {
            $modeloRepository->filtro($request['filtro']);
        }

        if ($request->has('atributos')) {
            $modeloRepository->selectAtributos($request['atributos']);
        }

        return response()->json($modeloRepository->getResultado());
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
    public function store(ModeloRequest $request)
    {
        $imagem = $request['imagem'];
        $imagem_nome = $imagem->store('imagens/modelos', 'public');
        
        $modelo = $this->modelo->create([
            'marca_id' => $request['marca_id'],
            'nome' => $request['nome'],
            'imagem' => $imagem_nome,
            'numero_portas' => $request['numero_portas'],
            'lugares' => $request['lugares'],
            'air_bag' => $request['air_bag'],
            'abs' => $request['abs'],
        ]);

        return response()->json($modelo);  
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if ($modelo === null) {
            return response()->json(['error' => 'Modelo pesquisado não encontrada!!!'], 404);
        }
        return response()->json($modelo); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModeloRequest $request, $id)
    {
        $modelo = $this->modelo->find($id);
        
        if ($modelo === null) {
            return response()->json(['error' => 'Não foi possível realizar a atualização de Marca, Marca solicitada não encontrada!!!'], 404);
        }

        if ($request['imagem'] != '') {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $imagem = $request['imagem'];
        $imagem_nome = $imagem->store('imagens/modelos', 'public');
        
        $modelo->update([
            'marca_id' => $request['marca_id'],
            'nome' => $request['nome'],
            'imagem' => $imagem_nome,
            'numero_portas' => $request['numero_portas'],
            'lugares' => $request['lugares'],
            'air_bag' => $request['air_bag'],
            'abs' => $request['abs'],
        ]);

        return response()->json($modelo); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(['error' => 'Não foi possível realizar a exclusão de Modelo, Modelo pesquisado não encontrada!!!'], 404);
        }
        
        if ($modelo['imagem'] != '') {
            Storage::disk('public')->delete($modelo->imagem);
        }
        $modelo->delete();
    }
}
