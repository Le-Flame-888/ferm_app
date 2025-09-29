<?php

namespace App\Services;

use App\Models\Equipement;

class ReferenceGeneratorService
{
    /**
     * Generate a unique reference code for equipment
     *
     * @param string $categorie
     * @param string $site
     * @return string
     */
    public static function generateEquipementReference(string $categorie, string $site): string
    {
        $prefix = strtoupper(substr($categorie, 0, 3));
        $siteCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $site), 0, 3));
        $year = date('y');
        $month = date('m');
        
        // Start with the base reference
        $baseRef = "{$prefix}-{$siteCode}-{$year}{$month}";
        
        // Find the highest sequence number for this reference pattern
        $lastEquipement = Equipement::where('code_ref', 'like', "{$baseRef}%")
            ->orderBy('code_ref', 'desc')
            ->first();
        
        if ($lastEquipement) {
            // Extract the sequence number and increment it
            $lastRef = $lastEquipement->code_ref;
            $parts = explode('-', $lastRef);
            $sequence = (int) end($parts) + 1;
        } else {
            // First equipment with this pattern
            $sequence = 1;
        }
        
        // Format the sequence with leading zeros
        $sequenceStr = str_pad($sequence, 3, '0', STR_PAD_LEFT);
        
        return "{$baseRef}-{$sequenceStr}";
    }
}
