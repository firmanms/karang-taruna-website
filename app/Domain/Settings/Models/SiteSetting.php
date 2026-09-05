<?php

namespace App\Domain\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'setting_group',
        'setting_key',
        'setting_value',
        'description',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::where('setting_key', $key)->value('setting_value') ?? $default;
    }

    public static function isEnabled(string $key, bool $default = true): bool
    {
        $val = static::where('setting_key', $key)->value('setting_value');
        if ($val === null) {
            return $default;
        }

        return in_array(strtolower(trim((string) $val)), ['1', 'true', 'yes', 'on', 'aktif', 'enable', 'enabled'], true);
    }
}
