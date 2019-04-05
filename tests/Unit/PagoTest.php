<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PagoTest extends TestCase
{
    /**
     * Tests home page works.
     *
     * @return void
     */
    public function testHome()
    {
        $response = $this->get('/');
        
        $response->assertOk();
    }

    public function testWrongFormSubmitFails()
    {
        $response = $this->post(route('pagos.store'));
        $response->assertSessionHas('errors');
    }
}
