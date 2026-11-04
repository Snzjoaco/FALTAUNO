<?php

namespace App\Console\Commands;

use App\Models\AdminActivity;
use App\Models\User;
use Illuminate\Console\Command;

class ExpireOwnerPlans extends Command
{
    protected $signature = 'app:expire-owner-plans';

    protected $description = 'Marca vencidos los planes manuales de dueños cuya fecha de cobro ya pasó';

    public function handle(): int
    {
        User::courtOwners()
            ->where('owner_status', 'activo')
            ->where('billing_status', 'al_dia')
            ->whereNotNull('next_charge_at')
            ->whereDate('next_charge_at', '<', today())
            ->chunkById(100, function ($owners) {
                foreach ($owners as $owner) {
                    $oldValues = $owner->only(['billing_status', 'next_charge_at']);
                    $owner->billing_status = 'vencido';
                    $owner->save();
                    AdminActivity::record('billing.plan_expired', $owner, $oldValues, [
                        'billing_status' => 'vencido',
                    ]);
                }
            });

        $this->info('Se actualizaron los planes vencidos.');

        return self::SUCCESS;
    }
}
