<?php

namespace Gernzy\Server\Classes;

use Gernzy\Server\Services\ActionInterface;
use Illuminate\Support\Str;

class FooBeforeCheckout implements ActionInterface
{
    public function __construct()
    {
    }

    public function run(ActionClass $action)
    {
        $data = $action->getModifiedData();

        array_push($data, [
            'user_id_foo' => Str::random(12),
            'date' => date("Y-m-d H:i:s")
        ]);

        $action->attachData(FooBeforeCheckout::class, $data);

        $mod = $action->getModifiedData();

        $action->eventPreventDefault();

        return $action;
    }
}
