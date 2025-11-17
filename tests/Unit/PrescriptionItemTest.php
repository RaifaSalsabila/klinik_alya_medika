<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\PrescriptionItem;
use App\Models\Prescription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrescriptionItemTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_prescription_item()
    {
        $prescription = Prescription::factory()->create();

        $itemData = [
            'prescription_id' => $prescription->id,
            'medicine_name' => 'Paracetamol 500mg',
            'dosage' => '500mg',
            'quantity' => 10,
            'instructions' => '3x1 sehari setelah makan',
        ];

        $item = PrescriptionItem::create($itemData);

        $this->assertInstanceOf(PrescriptionItem::class, $item);
        $this->assertEquals('Paracetamol 500mg', $item->medicine_name);
        $this->assertEquals(10, $item->quantity);
    }

    #[Test]
    public function it_belongs_to_a_prescription()
    {
        $item = PrescriptionItem::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $item->prescription());
        $this->assertInstanceOf(Prescription::class, $item->prescription);
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $item = new PrescriptionItem();
        $fillable = ['prescription_id', 'medicine_name', 'dosage', 'quantity', 'instructions'];

        $this->assertEquals($fillable, $item->getFillable());
    }

    #[Test]
    public function it_can_have_different_quantities()
    {
        $quantities = [1, 5, 10, 20, 30];

        foreach ($quantities as $quantity) {
            $item = PrescriptionItem::factory()->create(['quantity' => $quantity]);
            $this->assertEquals($quantity, $item->quantity);
            $this->assertIsInt($item->quantity);
        }
    }

    #[Test]
    public function it_can_store_various_instructions()
    {
        $instructions = [
            '3x1 sehari setelah makan',
            '2x1 sehari sebelum makan',
            '1x1 sehari saat tidur',
            '4x1 sehari selama 7 hari',
        ];

        foreach ($instructions as $instruction) {
            $item = PrescriptionItem::factory()->create(['instructions' => $instruction]);
            $this->assertEquals($instruction, $item->instructions);
        }
    }
}
