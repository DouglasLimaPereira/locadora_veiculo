<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarroRequest;
use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    protected $carro;
    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if ($request->has('atributos_modelo')) {
            $carroRepository->selectAtributosRegistros('modelo:'.$request['atributos_modelo']);
        }else{
            $carroRepository->selectAtributosRegistros('modelo');
        }

        if ($request->has('filtro')) {
            $carroRepository->filtro($request['filtro']);
        }

        if ($request->has('atributos')) {
            $carroRepository->selectAtributos($request['atributos']);
        }

        return response()->json($carroRepository->getResultado());
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
    public function store(CarroRequest $request)
    {
        $carro = $this->carro->create([
            'modelo_id' => $request['modelo_id'],
            'placa' => $request['placa'],
            'disponivel' => $request['disponivel'],
            'km' => $request['km'],
        ]);

        return response()->json($carro);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if ($carro === null) {
            return response()->json(['error' => 'carro pesquisado não encontrada!!!'], 404);
        }
        return response()->json($carro); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carro $carro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarroRequest $request, $id)
    {
        $carro = $this->carro->find($id);
        
        if ($carro === null) {
            return response()->json(['error' => 'Não foi possível realizar a atualização de carro, carro solicitada não encontrada!!!'], 404);
        }

        $carro->update([
            'modelo_id' => $request['modelo_id'],
            'placa' => $request['placa'],
            'disponivel' => $request['disponivel'],
            'km' => $request['km'],
        ]);

        return response()->json($carro); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(['error' => 'Não foi possível realizar a exclusão de carro, carro pesquisado não encontrada!!!'], 404);
        }
        $carro->delete();

        return response()->json('Carro removido com sucesso!!!');
    }
}
