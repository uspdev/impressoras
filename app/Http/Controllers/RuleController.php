<?php

namespace App\Http\Controllers;

use App\Http\Requests\RuleRequest;
use App\Models\Rule;
use App\Models\Printer;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index()
    {
        $this->authorize('admin');

        $rules = Rule::all();

        return view('rules.index', [
            'rules' => $rules,
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        return view('rules.create', [
            'rule' => new Rule(),
        ]);
    }

    public function store(RuleRequest $request)
    {
        $this->authorize('admin');

        $rule = Rule::create($request->validated());

        return redirect('/rules');
    }

    public function edit(Rule $rule)
    {
        $this->authorize('admin');

        return view('rules.edit', [
            'rule' => $rule,
        ]);
    }

    public function update(RuleRequest $request, Rule $rule)
    {
        $this->authorize('admin');

        $rule->update($request->validated());

        return redirect('/rules');
    }

    public function show(Rule $rule){

        $this->authorize('admin');

        $printers = Printer::where('rule_id', $rule->id)->get();

        return view('rules.show', [
            'rule' => $rule,
            'printers' => $printers
        ]);
    }

    public function destroy(Rule $rule)
    {
        $this->authorize('admin');

        if ($rule->printers->isNotEmpty()) {
            request()->session()->flash('alert-danger', 'Há impressoras nessa regra. Não é possível deletar');

            return redirect('/rules');
        }

        $rule->delete();

        return redirect('/rules');
    }
}
