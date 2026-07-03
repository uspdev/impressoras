<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegraCrudTest extends DuskTestCase
{
    /**
     * Método para fazer o Crud Completo
     */
    public function test_crud_regras()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Entrar')
                ->waitFor('#loginUsuario') # Importante: Espera a página de login carregar
                ->typeSlowly('loginUsuario', '1111')
                ->press('Login')
                ->waitFor('.login_logout_link') # Importante: Espera a página de retornada carregar
                ->assertSee('Regras')
                # Alan vai Implementat CRUD de regras
                ->clickLink('Regras');
        });
    }
}
