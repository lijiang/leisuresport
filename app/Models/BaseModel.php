<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


/**
 * Class BaseModel
 * @package App\Model
 */
class BaseModel extends Model
{

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->relations)) {
            return parent::getAttribute($key);
        } else {
            return parent::getAttribute(Str::snake($key));
        }
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        return parent::setAttribute(Str::snake($key), $value);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $newArray = array();
        foreach ($array as $name => $value) {
            $newArray[str_replace('_', '', lcfirst(ucwords($name, '_')))] = $value;
        }
        return $newArray;
    }

}
