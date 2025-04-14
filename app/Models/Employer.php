<?php

namespace App\Models;

use Illuminate\Support\Facades\Vite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employer extends Model
{
    /** @use HasFactory<\Database\Factories\EmployerFactory> */
    use HasFactory;

    // protected $withCount = ["jobs"];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function jobsFeatured(): HasMany
    {
        return $this->jobs()->withAttributes(['featured' => true]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo == null)
            return Vite::asset("resources/images/logo.jpg");

        return preg_match("/logos\/.*/", $this->logo) ? asset("storage/" . $this->logo) : $this->logo;
    }
}
