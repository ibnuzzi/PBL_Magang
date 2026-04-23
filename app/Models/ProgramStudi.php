<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';

    protected $fillable = [
        'kode',
        'nama',
        'jenjang',
    ];

    // ─── Relationships ───────────────────────────────────────────────

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
