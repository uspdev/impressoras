<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegraCrudTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function test_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Entrar')
                ->waitFor('#loginUsuario') # Importante: Espera a página de login carregar
                ->typeSlowly('loginUsuario', '1111')
                ->press('Login')
                ->waitFor('.login_logout_link') # Importante: Espera a página de retornada carregar
                ->assertSee('Regras');
        });
    }
}
