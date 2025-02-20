<?php

namespace App\Models;

use App\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use DefaultDatetimeFormat;

    public $timestamps = false;

    protected $fillable = [
        'key',
        'values',
        'description',
        'type',
    ];

    const TYPE_TEXT = 1;
    const TYPE_JSON = 2;
    const TYPE = [
        ['id' => self::TYPE_TEXT, 'name' => 'text'],
        ['id' => self::TYPE_JSON, 'name' => 'json'],
    ];

    /**
     * 更新系统配置
     * @param array $data
     * @return void
     */
    public static function updateConfig(array $data)
    {
        foreach ($data as $key => $value) {
            $config = Config::where('key', $key)->first();
            if (!$config) {
                $config = new Config();
                $config->key = $key;
                $config->type = self::TYPE_TEXT;
            }
            $config->values = $config->type == self::TYPE_JSON ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            $config->save();
        }
    }

    /**
     * 根据类型的不同 有不同的get逻辑
     * @param $value
     * @return mixed
     */
    public function getValuesAttribute($value)
    {
        if ($this->attributes['type'] == self::TYPE_JSON)
            return json_decode($value, true);
        else
            return $value;
    }

    /**
     * 获取全部配置
     * @return array
     */
    public static function getAllConfig(): array
    {
        $all = Config::all();
        $result = [];
        foreach ($all as $item) {
            $result[$item->key] = $item->values;
        }
        return $result;
    }
}
