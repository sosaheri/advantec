<?php

namespace App\Contracts;

use App\Models\Order;

interface DispatchGatewayInterface
{
    /**
     * Envía la orden al servicio externo de logística.
     * * @param Order $order
     * @return array [status => string, dispatch_id => string, external_response => array]
     * @throws \Exception
     */
    public function triggerDispatch(Order $order): array;
}