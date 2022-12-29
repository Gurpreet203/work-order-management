<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('workOrders.index', [
            'workOrders' => WorkOrder::visibleTo()
                ->search(request(['search']))
                ->paginate()
        ]);
    }
}
