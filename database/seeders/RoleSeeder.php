<?php

use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        $roles = ['Admin', 'Author', 'Editor'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
