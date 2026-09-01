<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegraCrudTest extends DuskTestCase
{
    /**
     * Método para fazer o Crud Completo
     */
    use DatabaseTruncation; // zera banco de dados 
    public function test_crud_regras()
    {
        $this->browse(function (Browser $browser) {
            // Login como admin
            $browser->visit('/')
                ->clickLink('Entrar')
                ->waitFor('#loginUsuario') # Importante: Espera a página de login carregar
                ->typeSlowly('loginUsuario', '1111')
                ->press('Login')
                ->waitForText('Regras', 5)
                ->assertSee('Regras')
                ->clickLink('Regras');

            // CREATE 
            $browser->assertSee('Adicionar regra')
                ->clickLink('Adicionar regra')
                ->waitFor('input[name=name]')
                ->type('name', 'Regra Teste Dusk')
                ->radio('queue_control', '0')
                ->select('quota_period', 'Mensal')
                ->type('quota', '100')
                ->select('quota_type', 'Páginas')
                ->press('Enviar')
                ->waitForLocation('/rules')
                ->assertSee('Regra Teste Dusk');

            // READ (show) 
            $browser->clickLink('Regra')
                ->waitForText('Regra Teste Dusk')
                ->assertSee('Regra Teste Dusk')
                ->assertSee('Mensal')
                ->assertSee('100')
                ->assertSee('Páginas');

            // UPDATE 
            $browser->visit('/rules')
                ->waitForText('Regra Teste Dusk')
                ->click('a[href*="/rules/"][href$="/edit"]')
                ->waitFor('input[name=name]')
                ->clear('name')
                ->type('name', 'Regra Teste Dusk Editada')
                ->clear('quota')
                ->type('quota', '200')
                ->press('Enviar')
                ->waitForLocation('/rules')
                ->assertSee('Regra Teste Dusk Editada')
                ->assertSee('200');

            // DELETE 
            $browser->visit('/rules')
                ->waitForText('Regra Teste Dusk Editada')
                ->click('#actions button[type=submit]')
                ->acceptDialog()
                ->waitUntilMissingText('Regra Teste Dusk Editada')
                ->assertDontSee('Regra Teste Dusk Editada');
        });
    }
}
