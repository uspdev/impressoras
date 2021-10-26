<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rule;
use App\Http\Requests\RuleRequest;

class RuleController extends Controller
{
   
	public function index()
	{
        $this->authorize('admin');

	    $rules =  Rule::all();

        return view('rules.index',[
            'rules' => $rules
        ]);
	}

	public function create()
    {
        $this->authorize('admin');

	    return view('rules.create',[
			'rule' => new Rule,
	    ]);
	}

	public function store(RuleRequest $request)
    {
       
        $this->authorize('admin');

		$rule = Rule::create($request->validated());

        return redirect("/rules");
	}

	public function edit(Rule $rule)
    {
        $this->authorize('admin');

        return view('rules.edit',[
            'rule' => $rule
        ]);
	}

	public function update(RuleRequest $request, Rule $rule)
    {
        $this->authorize('admin');

		$rule->update($request->validated());

        return redirect("/rules");
	}

	public function destroy(Rule $rule)
    {
        $this->authorize('admin');

        if($rule->printers->isNotEmpty()) {
            request()->session()->flash('alert-danger','Há impressoras nessa regra. Não é possível deletar');
            return redirect('/rules');
        }

        $rule->delete();

        return redirect('/rules');
	}

}
