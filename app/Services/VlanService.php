<?php

namespace App\Services;

use App\Models\Vlan;
use Illuminate\Support\Facades\Auth;

class VlanService
{
    /**
     * Create a new VLAN with its relations.
     */
    public function createVlan(array $data, $user = null): Vlan
    {
        $user = $user ?: Auth::user();
        $orgId = $data['organization_id'] ?? ($user ? $user->organization_id : null);

        $vlan = Vlan::create([
            'organization_id' => $orgId,
            'name'            => $data['name'] ?? 'VLAN Baru',
            'server_name'     => $data['server_name'] ?? null,
            'ip_address'      => $data['ip_address'] ?? null,
            'support_pppoe'   => !empty($data['support_pppoe']),
            'support_voucher' => !empty($data['support_voucher']),
            'regencie_id'     => $data['regencie_id'] ?? null,
            'district_id'     => $data['district_id'] ?? null,
            'village_id'      => $data['village_id'] ?? null,
            'hometown_id'     => $data['hometown_id'] ?? null,
        ]);

        if (!empty($data['olts']) && is_array($data['olts'])) {
            $vlan->olts()->sync($data['olts']);
        }

        if (!empty($data['mix_radiuses']) && is_array($data['mix_radiuses'])) {
            $vlan->mixRadiuses()->sync($data['mix_radiuses']);
        }

        if (!empty($data['pakets']) && is_array($data['pakets'])) {
            $vlan->pakets()->sync($data['pakets']);
        }

        if (!empty($data['prices']) && is_array($data['prices'])) {
            $vlan->prices()->sync($data['prices']);
        }

        return $vlan;
    }
}
