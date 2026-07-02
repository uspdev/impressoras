<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class IndexTest extends DuskTestCase
{
    /**
     * A Dusk test for index.
     */
    public function test_index(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Impressoras');
        });
    }
}
