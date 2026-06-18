<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Get setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        try {
            $setting = self::find($key);
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Set/update setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @return \App\Models\Setting
     */
    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
