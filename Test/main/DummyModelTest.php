<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Plugins\SamplePlugin\Model\DummyModel;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class DummyModelTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        // creamos un dummy y comprobamos los valores por defecto
        $dummy = new DummyModel();
        $dummy->name = 'Test dummy';
        $this->assertEquals('new', $dummy->dummy_type);
        $this->assertEquals(0, $dummy->price);

        // lo guardamos y comprobamos que existe
        $this->assertTrue($dummy->save());
        $this->assertTrue($dummy->exists());
        $this->assertNotNull($dummy->id);

        // lo volvemos a cargar de la base de datos
        $reloaded = new DummyModel();
        $this->assertTrue($reloaded->loadFromCode($dummy->id));
        $this->assertEquals('Test dummy', $reloaded->name);

        // lo borramos y comprobamos que ya no existe
        $this->assertTrue($dummy->delete());
        $this->assertFalse($dummy->exists());
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
