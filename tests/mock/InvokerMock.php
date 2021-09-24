<?php declare(strict_types=1);

class InvokerMock implements \Invoker\InvokerInterface {
    public function call($callable, array $parameters = []) {
        return call_user_func_array($callable, $parameters);
    }
}
