<?php

namespace Meride\Model;

class Meride {
    public static function factory($modelName, $data)
    {
        $model = 'Meride\Model\\'.ucfirst($modelName);
        return new $model($data);
    }
}