<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\Models\Assistant;
use App\Services\ReplicadoTemp;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AssistantController extends Controller
{
    public function index()
    {
        $this->authorize('admin');

        // monitores cadastrados na tabela local
        $assistants_locals = Assistant::get()->map(function ($assistant) {
            return [
                'id' => $assistant->id,
                'codpes' => $assistant->codpes,
                'fromReplicado' => false,
            ];
        })->toArray();

        // monitores cadastrados no Replicado
        $assistants_fromReplicado = array_map(function ($codpes) {
            return [
                'id' => null,
                'codpes' => $codpes,
                'fromReplicado' => true,
            ];
        }, ReplicadoTemp::listarMonitores(env('IMPRESSORAS_CODSLAMON', 22)));

        // une os dois conjuntos, removendo duplicados e ordenando de como a que os do Replicado venham primeiro
        $assistants = collect(array_merge($assistants_fromReplicado, $assistants_locals))
            ->map(function ($assistant) {
                return (object) $assistant;
            })
            ->sortBy('codpes')
            ->sortByDesc('fromReplicado')
            ->unique('codpes')
            ->values();

        $assistants_enriched = $assistants->map(function ($assistant) {
            return (object) [
                'id' => $assistant->id,
                'codpes' => $assistant->codpes,
                'name' => \Uspdev\Replicado\Pessoa::obterNome($assistant->codpes),
                'email' => \Uspdev\Replicado\Pessoa::retornarEmailUsp($assistant->codpes),
                'fromReplicado' => $assistant->fromReplicado,
            ];
        });

        \UspTheme::activeUrl('/assistants');
        return view('assistants.index', [
            'assistants' => $assistants_enriched,
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        \UspTheme::activeUrl('/assistants');
        return view('assistants.create', [
            'assistant' => new Assistant(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('admin');

        if ($add = $request->add_codpes) {
            $assistant = Assistant::create([
                'codpes' => $request->add_codpes,
            ]);
        }

        \UspTheme::activeUrl('/assistants');
        return redirect('/assistants');
    }

    public function destroy(Assistant $assistant)
    {
        $this->authorize('admin');

        $assistant->delete();

        return redirect('/assistants');
    }
}
