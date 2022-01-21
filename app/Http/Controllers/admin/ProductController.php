<?php

namespace App\Http\Controllers\admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        return view('admin-views.product.add-new');
    }

    public function store(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'category_id' => 'required',
            // 'brand_id' => 'required',
            // 'unit' => 'required',
            'images' => 'required',
            'image' => 'required',
            'tax' => 'required|min:0',
            'unit_price' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
        ], [
            'images.required' => 'Product images is required!',
            'image.required' => 'Product thumbnail is required!',
            // 'category_id.required' => 'category  is required!',
            // 'brand_id.required' => 'brand  is required!',
            // 'unit.required' => 'Unit  is required!',
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['unit_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['unit_price'] <= $dis) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'unit_price', 'Discount can not be more or equal to the price!'
                );
            });
        }

        $p = new Product();
        // $p->user_id = 1;
        // $p->added_by = 'admin';
        $p->name = $request->name[array_search('en', $request->lang)];
        $p->slug = Str::slug($request->name[array_search('en', $request->lang)], '-').'-'.Str::random(6);

        // $category = [];

        // if ($request->category_id != null) {
        //     array_push($category, [
        //         'id' => $request->category_id,
        //         'position' => 1,
        //     ]);
        // }
        // if ($request->sub_category_id != null) {
        //     array_push($category, [
        //         'id' => $request->sub_category_id,
        //         'position' => 2,
        //     ]);
        // }
        // if ($request->sub_sub_category_id != null) {
        //     array_push($category, [
        //         'id' => $request->sub_sub_category_id,
        //         'position' => 3,
        //     ]);
        // }

        // $p->category_ids = json_encode($category);
        // $p->brand_id = $request->brand_id;
        // $p->unit = $request->unit;
        $p->details = $request->description[array_search('en', $request->lang)];

        // if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
        //     $p->colors = json_encode($request->colors);
        // } else {
        //     $colors = [];
        //     $p->colors = json_encode($colors);
        // }
        // $choice_options = [];
        // if ($request->has('choice')) {
        //     foreach ($request->choice_no as $key => $no) {
        //         $str = 'choice_options_'.$no;
        //         $item['name'] = 'choice_'.$no;
        //         $item['title'] = $request->choice[$key];
        //         $item['options'] = explode(',', implode('|', $request[$str]));
        //         array_push($choice_options, $item);
        //     }
        // }
        // $p->choice_options = json_encode($choice_options);
        // //combinations start
        // $options = [];
        // if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
        //     $colors_active = 1;
        //     array_push($options, $request->colors);
        // }
        // if ($request->has('choice_no')) {
        //     foreach ($request->choice_no as $key => $no) {
        //         $name = 'choice_options_'.$no;
        //         $my_str = implode('|', $request[$name]);
        //         array_push($options, explode(',', $my_str));
        //     }
        // }
        // //Generates the combinations of customer choice options

        // $combinations = Helpers::combinations($options);

        // $variations = [];
        // $stock_count = 0;
        // if (count($combinations[0]) > 0) {
        //     foreach ($combinations as $key => $combination) {
        //         $str = '';
        //         foreach ($combination as $k => $item) {
        //             if ($k > 0) {
        //                 $str .= '-'.str_replace(' ', '', $item);
        //             } else {
        //                 if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
        //                     $color_name = Color::where('code', $item)->first()->name;
        //                     $str .= $color_name;
        //                 } else {
        //                     $str .= str_replace(' ', '', $item);
        //                 }
        //             }
        //         }
        //         $item = [];
        //         $item['type'] = $str;
        //         $item['price'] = BackEndHelper::currency_to_usd(abs($request['price_'.str_replace('.', '_', $str)]));
        //         $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
        //         $item['qty'] = abs($request['qty_'.str_replace('.', '_', $str)]);
        //         array_push($variations, $item);
        //         $stock_count += $item['qty'];
        //     }
        // } else {
        $stock_count = (int) $request['current_stock'];
        // }

        // if ($validator->errors()->count() > 0) {
        //     return response()->json(['errors' => Helpers::error_processor($validator)]);
        // }

        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                $product_images[] = ImageManager::upload('product/', 'png', $img);
            }
            $p->images = $product_images[0];
        }
        $p->thumbnail = ImageManager::upload('product/thumbnail/', 'png', $request->image);

        //combinations end
        // $p->variation = json_encode($variations);
        // $p->unit_price = BackEndHelper::currency_to_usd($request->unit_price);
        $p->unit_price = $request->unit_price;
        // $p->purchase_price = BackEndHelper::currency_to_usd($request->purchase_price);
        $p->purchase_price = $request->purchase_price;
        $p->tax = $request->tax_type == 'flat' ? $request->tax : $request->tax;
        $p->tax_type = $request->tax_type;
        $p->label = $request->label;
        $p->discount = $request->discount_type == 'flat' ? $request->discount : $request->discount;
        $p->discount_type = $request->discount_type;
        // $p->attributes = json_encode($request->choice_attributes);
        $p->current_stock = abs($stock_count);

        $p->meta_title = $request->meta_title;
        $p->meta_description = $request->meta_description;
        $p->meta_image = ImageManager::upload('product/meta/', 'png', $request->meta_image);

        // $p->video_provider = 'youtube';
        // $p->video_url = $request->video_link;
        $p->request_status = 1;

        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $p->save();

            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    array_push($data, [
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $p->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
                    ]);
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, [
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $p->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $request->description[$index],
                    ]);
                }
            }
            // Translation::insert($data);

            Toastr::success('Product added successfully!');

            return redirect()->route('admin.listProduct', ['in_house']);
        }

        // return  'product store';
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
