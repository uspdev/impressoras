<?php

namespace Tests\Browser;

use App\Models\Printer;
use App\Models\Rule;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ImpressoraCrudTest extends DuskTestCase
{
    use DatabaseTruncation;

    private function criaRegra(): Rule
    {
        return Rule::create([
            'name' => 'Regra Teste Dusk',
            'queue_control' => 0,
            'quota_period' => 'Mensal',
            'quota' => 100,
            'quota_type' => 'Páginas',
        ]);
    }

    public function test_crud_impressora()
    {
        $rule = $this->criaRegra();

        $this->browse(function (Browser $browser) use ($rule) {
            // Login como admin
            $browser->visit('/')
                ->clickLink('Entrar')
                ->waitFor('#loginUsuario')
                ->typeSlowly('loginUsuario', '1111')
                ->press('Login')
                ->waitForText('Impressoras', 5)
                ->assertSee('Impressoras');

            // CREATE
            $browser->visit('/printers')
                ->clickLink('Adicionar impressora')
                ->waitFor('input[name=name]')
                ->type('name', 'Impressora Teste Dusk')
                ->type('machine_name', 'ImpressoraTesteDusk001')
                ->type('location', 'Sala de Testes')
                ->select('rule_id', (string) $rule->id)
                ->press('Enviar')
                ->waitForLocation('/printers')
                ->assertSee('Impressora Teste Dusk');

            // READ
            $browser->visit('/printers')
                ->waitForText('Impressora Teste Dusk')
                ->assertSee('Regra Teste Dusk');

            // UPDATE
            $browser->click('a[href*="/printers/"][href$="/edit"]')
                ->waitFor('input[name=name]')
                ->clear('name')
                ->type('name', 'Impressora Teste Dusk Editada')
                ->press('Enviar')
                ->waitForLocation('/printers')
                ->assertSee('Impressora Teste Dusk Editada');

            // DELETE
            $browser->visit('/printers')
                ->waitForText('Impressora Teste Dusk Editada')
                ->click('#actions button[type=submit]')
                ->acceptDialog()
                ->waitUntilMissingText('Impressora Teste Dusk Editada')
                ->assertDontSee('Impressora Teste Dusk Editada');
        });
    }

    // Usa fila criada no CUPS (init-printers.sh).
    public function test_impressao_de_teste()
    {
        $rule = $this->criaRegra();
        $printer = Printer::create([
            'name' => 'Impressora Teste Dusk',
            'machine_name' => 'ImpressoraTesteDusk001',
            'location' => 'Sala de Testes',
            'rule_id' => $rule->id,
            'active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($printer) {
            $browser->visit('/')
                ->clickLink('Entrar')
                ->waitFor('#loginUsuario')
                ->typeSlowly('loginUsuario', '1111')
                ->press('Login')
                ->waitForText('Impressoras', 5)
                ->visit("/printers")
                ->waitForText('Impressora Teste Dusk')
                ->click("a[href='/printers/{$printer->id}/printtest']")
                ->waitForText('Teste de impressão enviado com sucesso', 15)
                ->assertSee('Teste de impressão enviado com sucesso');
        });
    }
}