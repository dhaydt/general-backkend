<?php

namespace App\Http\Controllers\admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $admin = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $admin = Product::get();
        }

        session()->put('title', 'Product list');
        $last = $admin->last();
        if (isset($last)) {
            $admin = $admin->last()->paginate(Helpers::pagination_limit())->appends($query_param);
        }

        return view('admin-views.product.list', compact('admin', 'search'));
    }

    public function add_new()
    {
        return  'product add';
    }

    public function store(Request $request)
    {
        return  'product store';
    }

    public function edit($id)
    {
        return  $id;
    }

    public function update($id)
    {
        return  $id;
    }

    public function delete($id)
    {
        return  $id;
    }
}
