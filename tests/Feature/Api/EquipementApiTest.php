<?php

namespace Tests\Feature\Api;

use App\Models\Equipement;
use App\Models\Fournisseur;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EquipementApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear the database to avoid unique constraint violations
        $this->artisan('migrate:fresh');
        
        // Create a test fournisseur for the equipement
        $this->fournisseur = Fournisseur::factory()->create();
        
        // Create and authenticate a test user with Sanctum
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
    }

    /** @test */
    public function it_can_list_equipements()
    {
        // Clear existing equipements to avoid duplicates
        Equipement::query()->delete();
        
        // Create test equipements with unique code_ref values
        $equipements = [];
        for ($i = 0; $i < 3; $i++) {
            $equipements[] = Equipement::factory()->create([
                'code_ref' => 'EQ-TEST-' . uniqid(),
                'categorie' => 'Test Category ' . ($i + 1),
                'type_emplacement' => 'Test Type ' . ($i + 1),
                'site' => 'Test Site ' . ($i + 1),
                'emplacement' => 'Test Emplacement ' . ($i + 1),
                'lot' => 'T' . ($i + 1),
                'fournisseur_id' => $this->fournisseur->id,
            ]);
        }
        
        $response = $this->getJson('/api/equipements');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'code_ref',
                            'categorie',
                            'type_emplacement',
                            'site',
                            'emplacement',
                            'lot',
                            'description',
                            'type_huile',
                            'duree_controle',
                            'ref_courroie',
                            'ref_roulement',
                            'numero_serie',
                            'marque',
                            'date_achat',
                            'date_mise_service',
                            'prix_achat',
                            'fournisseur_id',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                    'links' => [
                        'first', 'last', 'prev', 'next'
                    ],
                    'meta' => [
                        'current_page', 'from', 'last_page', 'path',
                        'per_page', 'to', 'total'
                    ]
                ],
                'message'
            ]);
    }

    /** @test */
    public function it_can_show_an_equipement()
    {
        $uniqueCodeRef = 'EQ-SHOW-' . uniqid();
        $equipement = Equipement::factory()->create([
            'code_ref' => $uniqueCodeRef,
            'categorie' => 'Show Test',
            'type_emplacement' => 'Show Test',
            'site' => 'Show Test',
            'emplacement' => 'Show Test',
            'lot' => 'ST',
            'fournisseur_id' => $this->fournisseur->id,
        ]);
        
        $response = $this->getJson("/api/equipements/{$equipement->id}");
        
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $equipement->id,
                    'code_ref' => $uniqueCodeRef,
                    'categorie' => 'Show Test',
                    'type_emplacement' => 'Show Test',
                    'site' => 'Show Test',
                    'emplacement' => 'Show Test',
                    'lot' => 'ST',
                ]
            ]);
    }

    /** @test */
    public function it_can_create_an_equipement()
    {
        $uniqueCodeRef = 'TEST-' . uniqid();
        
        $equipementData = [
            'categorie' => 'Pompe',
            'type_emplacement' => 'Tapis',
            'site' => 'Zhiliga',
            'emplacement' => 'Zone de production',
            'lot' => 'Z1',
            'code_ref' => $uniqueCodeRef,
            'description' => 'Pompe de test',
            'type_huile' => 'ISO 46',
            'duree_controle' => 90,
            'ref_courroie' => 'CR-123',
            'ref_roulement' => 'R-45-678',
            'numero_serie' => 'SERIE-' . uniqid(),
            'marque' => 'Test Brand',
            'date_achat' => '2024-01-01',
            'date_mise_service' => '2024-01-15',
            'prix_achat' => 1000.50,
            'fournisseur_id' => $this->fournisseur->id,
        ];
        
        $response = $this->postJson('/api/equipements', $equipementData);
        
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'code_ref' => $uniqueCodeRef,
                    'categorie' => 'Pompe',
                    'site' => 'Zhiliga',
                ]
            ]);
            
        $this->assertDatabaseHas('equipements', ['code_ref' => $uniqueCodeRef]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_equipement()
    {
        $response = $this->postJson('/api/equipements', []);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'categorie', 'type_emplacement', 'site', 'emplacement', 'lot', 'code_ref'
            ]);
    }

    /** @test */
    public function it_can_update_an_equipement()
    {
        $uniqueCodeRef = 'EQ-UPDATE-' . uniqid();
        $equipement = Equipement::factory()->create([
            'code_ref' => $uniqueCodeRef,
            'categorie' => 'Before Update',
            'type_emplacement' => 'Before Update',
            'site' => 'Before Update',
            'emplacement' => 'Before Update',
            'lot' => 'BU',
            'fournisseur_id' => $this->fournisseur->id,
        ]);
        
        $updateData = [
            'categorie' => 'Ventilateur',
            'type_emplacement' => 'Ventilateur',
            'site' => 'Site Principal',
            'emplacement' => 'Zone de refroidissement',
            'lot' => 'AU',
            'description' => 'Mise à jour des informations',
            'fournisseur_id' => $this->fournisseur->id,
        ];
        
        $response = $this->putJson("/api/equipements/{$equipement->id}", $updateData);
        
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $equipement->id,
                    'code_ref' => $uniqueCodeRef,
                    'categorie' => 'Ventilateur',
                    'type_emplacement' => 'Ventilateur',
                    'site' => 'Site Principal',
                    'description' => 'Mise à jour des informations',
                ]
            ]);
            
        $this->assertDatabaseHas('equipements', [
            'id' => $equipement->id,
            'code_ref' => $uniqueCodeRef,
            'categorie' => 'Ventilateur',
            'description' => 'Mise à jour des informations',
        ]);
    }

    /** @test */
    public function it_can_delete_an_equipement()
    {
        // Create a new equipement with a unique code_ref
        $uniqueCodeRef = 'TO-DELETE-' . uniqid();
        $equipement = Equipement::factory()->create([
            'code_ref' => $uniqueCodeRef,
            'categorie' => 'To Delete',
            'type_emplacement' => 'To Delete',
            'site' => 'To Delete',
            'emplacement' => 'To Delete',
            'lot' => 'TD',
            'fournisseur_id' => $this->fournisseur->id,
        ]);
        
        // Verify the equipement exists before deletion
        $this->assertDatabaseHas('equipements', ['id' => $equipement->id]);
        
        $response = $this->deleteJson("/api/equipements/{$equipement->id}");
        
        $response->assertStatus(204);
            
        // Verify the equipement no longer exists
        $this->assertDatabaseMissing('equipements', ['id' => $equipement->id]);
    }
}
