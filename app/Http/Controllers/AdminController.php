<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Slide;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    // khởi tạo hàm index
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get()->take(10);
        $dashboardDatas = DB::select('SELECT sum(after_discount) AS totalAmount,
                                            sum(if(status = "Đã đặt hàng", after_discount, 0)) AS totalOrderedAmount,
                                            sum(if(status = "Đã vận chuyển", after_discount, 0)) AS totalDeliveredAmount,
                                            sum(if(status = "Đã hủy", after_discount, 0)) AS totalCanceledAmount,
                                            count(*) AS total,
                                            sum(if(status = "Đã đặt hàng", 1, 0)) AS totalOrdered,
                                            sum(if(status = "Đã vận chuyển", 1, 0)) AS totalDelivered,
                                            sum(if(status = "Đã hủy", 1, 0)) AS totalCanceled
                                            FROM orders');

        $monthlyDatas = DB::select("SELECT m.id AS monthNo, m.name AS monthName,
                                            IFNULL(D.totalAmount,0) AS totalAmount,
                                            IFNULL(D.totalOrderedAmount,0) AS totalOrderedAmount,
                                            IFNULL(D.totalDeliveredAmount,0) AS totalDeliveredAmount,
                                            IFNULL(D.totalCanceledAmount,0) AS totalCanceledAmount
                                            FROM month_names m
                                            LEFT JOIN (SELECT DATE_FORMAT(created_at, '%b') AS monthName,
                                                MONTH(created_at) AS monthNo,
                                                sum(after_discount) AS totalAmount,
                                                sum(if(status = 'Đã đặt hàng', after_discount, 0)) AS totalOrderedAmount,
                                                sum(if(status = 'Đã vận chuyển', after_discount, 0)) AS totalDeliveredAmount,
                                                sum(if(status = 'Đã hủy', after_discount, 0)) AS totalCanceledAmount
                                                FROM orders WHERE YEAR(created_at)=YEAR(NOW()) GROUP BY YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
                                                ORDER BY MONTH(created_at))
                                            D ON D.monthNo=m.id");

        $amountM = implode(',', collect($monthlyDatas)->pluck('totalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('totalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('totalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('totalCanceledAmount')->toArray());

        $totalAmount = collect($monthlyDatas)->sum('totalAmount');
        $totalOrderedAmount = collect($monthlyDatas)->sum('totalOrderedAmount');
        $totalDeliveredAmount = collect($monthlyDatas)->sum('totalDeliveredAmount');
        $totalCanceledAmount = collect($monthlyDatas)->sum('totalCanceledAmount');

        return view(
            'admin.index',
            compact('orders', 'dashboardDatas', 'monthlyDatas', 'amountM', 'OrderedAmountM', 'DeliveredAmountM', 'CanceledAmountM', 'totalAmount', 'totalOrderedAmount', 'totalDeliveredAmount', 'totalCanceledAmount')
        )->with('success', 'Xin chào admin');
    }

    // brands
    // khởi tạo hàm brands
    public function brands()
    {
        // gọi brand
        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        // gọi view admin.brands với dữ liệu truyền vào
        return view('admin.brands', compact('brands'));
    }
    // add brand
    public function brand_add()
    {
        return view('admin.brand-add');
    }
    // store brand
    public function brand_store(Request $request)
    {
        // Tạo slug từ name nếu slug không được gửi từ client
        $request->merge([
            'slug' => $request->input('slug') ?: Str::slug($request->input('name')),
        ]);

        // Validate dữ liệu
        $request->validate([
            'name' => ['required', 'string'],
            'slug' => ['required', 'unique:brands,slug'],
            'image' => ['required', 'mimes:png,jpg,jpeg', 'max:3072'],
        ], [
            'name.required' => 'Tên thương hiệu không được để trống',
            'name.string' => 'Tên thương hiệu phải là chuỗi',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'image.required' => 'Ảnh thương hiệu không được để trống',
            'image.mimes' => 'Định dạng ảnh phải là PNG, JPG, JPEG',
            'image.max' => 'Kích thước ảnh không quá 3MB',
        ]);

        // Lưu vào database
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $image = $request->file('image');

        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $brand->image = $file_name;
        $this->GenerateBrandThumbnailsImage($image, $file_name);
        $brand->save();
        return redirect()->route('admin.brands')->with('success', 'Thêm thương hiệu thành công');
    }
    // edit brand
    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }
    // update brand
    public function brand_update(Request $request)
    {
        // Tạo slug từ name nếu slug không được gửi từ client
        $request->merge([
            'slug' => $request->input('slug') ?: Str::slug($request->input('name')),
        ]);

        // Validate dữ liệu
        $request->validate([
            'name' => ['required', 'string'],
            'slug' => ['required', 'unique:brands,slug,' . $request->id],
            'image' => ['mimes:png,jpg,jpeg', 'max:3072'],
        ], [
            'name.required' => 'Tên thương hiệu không được để trống',
            'name.string' => 'Tên thương hiệu phải là chuỗi',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'image.mimes' => 'Định dạng ảnh phải là PNG, JPG, JPEG',
            'image.max' => 'Kích thước ảnh không quá 3MB',
        ]);

        // Lưu vào database
        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
                File::delete(public_path('uploads/brands') . '/' . $brand->image);
            }
            $image = $request->file('image');

            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $brand->image = $file_name;
            $this->GenerateBrandThumbnailsImage($image, $file_name);
        }
        $brand->save();
        return redirect()->route('admin.brands')->with('success', 'Thêm thương hiệu thành công');
    }
    // delete brand
    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
            File::delete(public_path('uploads/brands') . '/' . $brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('success', 'Xóa thương hiệu thành công');
    }

    // public function searchBrand(Request $request)
    // {
    //     $search = $request->search;
    //     $brands = Brand::where('name', 'LIKE', "%$search%")
    //         ->orWhere('slug', 'LIKE', "%$search%")
    //         ->orderBy('id', 'desc')
    //         ->paginate(10);

    //     return view('admin.brands', compact('brands'));
    // }

    public function GenerateBrandThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    // category
    // category index
    public function categories()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.categories', compact('categories'));
    }
    // add categories
    public function category_add()
    {
        return view('admin.category-add');
    }
    // store category
    public function category_store(Request $request)
    {
        // Tạo slug từ name nếu slug không được gửi từ client
        $request->merge([
            'slug' => $request->input('slug') ?: Str::slug($request->input('name')),
        ]);

        // Validate dữ liệu
        $request->validate([
            'name' => ['required', 'string'],
            'slug' => ['required', 'unique:categories,slug'],
            'image' => ['mimes:png,jpg,jpeg', 'max:3072'],
        ], [
            'name.required' => 'Tên danh mục không được để trống',
            'name.string' => 'Tên danh mục phải là chuỗi',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'image.mimes' => 'Định dạng ảnh phải là PNG, JPG, JPEG',
            'image.max' => 'Kích thước ảnh không quá 3MB',
        ]);

        // Lưu vào database
        $categoey = new Category();
        $categoey->name = $request->name;
        $categoey->slug = $request->slug;
        $image = $request->file('image');

        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $categoey->image = $file_name;
        $this->GenerateCategoryThumbnailsImage($image, $file_name);
        $categoey->save();

        // toastr()->success('Thêm danh mục thành công', [], 'Thành công');
        return redirect()->route('admin.categories')->with('success', 'Thêm danh mục thành công');
    }
    // edit category
    public function category_edit($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit', compact('category'));
    }
    // update category
    public function category_update(Request $request)
    {
        // Tạo slug từ name nếu slug không được gửi từ client
        $request->merge([
            'slug' => $request->input('slug') ?: Str::slug($request->input('name')),
        ]);

        $request->validate([
            'name' => ['required', 'string'],
            'slug' => ['required', 'unique:categories,slug,' . $request->id],
            'image' => ['mimes:png,jpg,jpeg', 'max:3072'],
        ], [
            'name.required' => 'Tên thương hiệu không được để trống',
            'name.string' => 'Tên thương hiệu phải là chuỗi',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'image.mimes' => 'Định dạng ảnh phải là PNG, JPG, JPEG',
            'image.max' => 'Kích thước ảnh không quá 3MB',
        ]);

        // Lưu vào database
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
                File::delete(public_path('uploads/categories') . '/' . $category->image);
            }
            $image = $request->file('image');

            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $category->image = $file_name;
            $this->GeneratecategoryThumbnailsImage($image, $file_name);
        }
        $category->save();

        // toastr()->success('Cập nhật danh mục thành công', [], 'Thành công');
        return redirect()->route('admin.categories')->with('success', 'Cập nhật danh mục thành công');
    }
    // delete category
    public function category_delete($id)
    {
        $category = Category::find($id);
        if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
            File::delete(public_path('uploads/categories') . '/' . $category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Xóa danh mục thành công');
    }
    public function GenerateCategoryThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());  // Changed 'read' to 'make'
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    // products
    // product index
    public function products()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products', compact('products'));
    }
    // add product
    public function product_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }
    // store product
    public function product_store(Request $request)
    {
        // Tạo slug từ name nếu slug không được gửi từ client
        $request->merge([
            'slug' => $request->input('slug') ?: Str::slug($request->input('name')),
        ]);

        // Validate dữ liệu
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'unique:products,slug'],
            'short_description' => ['required'],
            'description' => ['required'],
            'regular_price' => ['required'],
            'SKU' => ['required'],
            'stock_status' => ['required'],
            'featured' => ['required'],
            'quantity' => ['required', 'numeric'],
            // 'image' => ['required', 'mimes:png,jpg,jpeg', 'max:3072'],
            'category_id' => ['required'],
            'brand_id' => ['required'],
        ], [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.string' => 'Tên sản phẩm phải là chuỗi',
            'name.max' => 'Tên sản phẩm không quá 255 ký tự',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'short_description.required' => 'Mô tả ngắn không được để trống',
            'description.required' => 'Mô tả chi tiết không được để trống',
            'regular_price.required' => 'Giá gốc không được để trống',
            'SKU.required' => 'Mã sản phẩm không được để trống',
            'stock_status.required' => 'Trạng thái hàng tồn không được để trống',
            'featured.required' => 'Sản phẩm nổi bật không được để trống',
            'quantity.required' => 'Số lượng không được để trống',
            'quantity.numeric' => 'Số lượng phải là số',
            'image.required' => 'Ảnh sản phẩm không được để trống',
            // 'image.mimes' => 'Định dạng ảnh phải là PNG, JPG, JPEG',
            'image.max' => 'Kích thước ảnh không quá 3MB',
            'category_id.required' => 'Danh mục sản phẩm không được để trống',
            'brand_id.required' => 'Thương hiệu sản phẩm không được để trống',
        ]);

        // khởi tạo sản phẩm
        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        // upload ảnh sản phẩm
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $product->image = $imageName;
            $this->GenerateProductImage($image, $imageName);
        }

        // ảnh mô tả
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            $allowedfileExtension = ['jpg', 'jpeg', 'png'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtension);
                if ($gcheck) {
                    $gfileName = $current_timestamp . '-' . $counter . '.' . $gextension;
                    $this->GenerateProductImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        // lưu vào database
        $product->save();
        // toastr()->success('Thêm sản phẩm thành công', [], 'Thành công');
        return redirect()->route('admin.products')->with('success', 'Thêm sản phẩm thành công');
    }
    public function GenerateProductImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/products');
        $destinationPathThumbnails = public_path('uploads/products/thumbnails');
        $img = Image::read($image->path());

        $img->cover(540, 689, 'top');
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

        $img->resize(104, 104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnails . '/' . $imageName);
    }
    // edit product
    public function product_edit($id)
    {
        $product = Product::find($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }
    // update product
    public function product_update(Request $request)
    {
        // Tạo slug từ name nếu slug không được gửi từ client
        $request->merge([
            'slug' => $request->input('slug') ?: Str::slug($request->input('name')),
        ]);
        // Validate dữ liệu
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'unique:products,slug,' . $request->id],
            'short_description' => ['required'],
            'description' => ['required'],
            'regular_price' => ['required'],
            'SKU' => ['required'],
            'stock_status' => ['required'],
            'featured' => ['required'],
            'quantity' => ['required', 'numeric'],
            // 'image' => ['mimes:png,jpg,jpeg', 'max:3072'],
            'category_id' => ['required'],
            'brand_id' => ['required'],
        ], [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.string' => 'Tên sản phẩm phải là chuỗi',
            'name.max' => 'Tên sản phẩm không quá 255 ký tự',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'short_description.required' => 'Mô tả ngắn không được để trống',
            'description.required' => 'Mô tả chi tiết không được để trống',
            'regular_price.required' => 'Giá gốc không được để trống',
            'SKU.required' => 'Mã sản phẩm không được để trống',
            'stock_status.required' => 'Trạng thái hàng tồn không được để trống',
            'featured.required' => 'Sản phẩm nổi bật không được để trống',
            'quantity.required' => 'Số lượng không được để trống',
            'quantity.numeric' => 'Số lượng phải là số',
            // 'image.mimes' => 'Định dạng ảnh phải là PNG, JPG, JPEG',
            'image.max' => 'Kích thước ảnh không quá 3MB',
            'category_id.required' => 'Danh mục sản phẩm không được để trống',
            'brand_id.required' => 'Thương hiệu sản phẩm không được để trống',
        ]);
        // khởi tạo
        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        // upload ảnh sản phẩm
        if ($request->hasFile('image')) {
            // xóa ảnh cũ
            if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
                File::delete(public_path('uploads/products') . '/' . $product->image);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
            }

            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $product->image = $imageName;
            $this->GenerateProductImage($image, $imageName);
        }

        // ảnh mô tả
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            // xóa ảnh cũ
            foreach (explode(',', $product->images) as $ofile) {
                if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                    File::delete(public_path('uploads/products') . '/' . $ofile);
                }
                if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                    File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
                }
            }

            $allowedfileExtension = ['jpg', 'jpeg', 'png'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtension);
                if ($gcheck) {
                    $gfileName = $current_timestamp . '-' . $counter . '.' . $gextension;
                    $this->GenerateProductImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        // toastr()->success('Cập nhật sản phẩm thành công', [], 'Thành công');
        return redirect()->route('admin.products')->with('success', 'Cập nhật sản phẩm thành công');
    }

    // delete product
    public function product_delete($id)
    {
        $product = Product::find($id);
        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }
        foreach (explode(',', $product->images) as $ofile) {
            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }
        $product->delete();
        // toastr()->success('Xóa sản phẩm thành công', [], 'Thành công');
        return redirect()->route('admin.products')->with('success', 'Xóa sản phẩm thành công');
    }

    // coupon code
    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_date', 'desc')->paginate(12);
        return view('admin.coupon', compact('coupons'));
    }
    // add coupon
    public function coupon_add()
    {
        return view('admin.coupon-add');
    }
    // store coupon
    public function coupon_store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code|max:20',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ], [
            'code.required' => 'Mã giảm giá không được để trống',
            'code.unique' => 'Mã giảm giá đã tồn tại',
            'code.max' => 'Mã giảm giá không vượt quá 20 ký tự',
            'type.required' => 'Thể loại mã không được để trống',
            'value.required' => 'Giảm giá không được để trống',
            'value.numeric' => 'Giảm giá phải là số',
            'cart_value.required' => 'Giá trị giỏ hàng không được để trống',
            'expiry_date.required' => 'Ngày hết hạn không được để trống',
            'expiry_date.date' => 'Ngày hết hạn phải dưới dạng ngày tháng',
        ]);

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();

        // toastr()->success('Thêm mã giảm giá thành công', [], 'Thành công');
        return redirect()->route('admin.coupons')->with('success', 'Thêm mã giảm giá thành công');
    }
    // edit coupon
    public function coupon_edit($id)
    {
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit', compact('coupon'));
    }
    // update coupon
    public function coupon_update(Request $request)
    {
        $coupon = Coupon::find($request->id);
        $request->validate([
            'code' => 'required|max:20|unique:coupons,code,' . $request->id . ',id',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ], [
            'code.required' => 'Mã giảm giá không được để trống',
            'code.unique' => 'Mã giảm giá đã tồn tại',
            'code.max' => 'Mã giảm giá không vượt quá 20 ký tự',
            'type.required' => 'Thể loại mã không được để trống',
            'value.required' => 'Giảm giá không được để trống',
            'value.numeric' => 'Giảm giá phải là số',
            'cart_value.required' => 'Giá trị giỏ hàng không được để trống',
            'expiry_date.required' => 'Ngày hết hạn không được để trống',
            'expiry_date.date' => 'Ngày hết hạn phải dưới dạng ngày tháng',
        ]);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        // toastr()->success('Cập nhật mã giảm giá thành công', [], 'Thành công');
        return redirect()->route('admin.coupons')->with('success', 'Chỉnh sửa mã giảm giá thành công');
    }
    // delete coupon
    public function coupon_delete($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        // toastr()->success('Xóa mã giảm giá thành công', [], 'Thành công');
        return redirect()->route('admin.coupons')->with('success', 'Xóa mã giảm giá thành công');
    }

    // orders
    public function orders()
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(12);
        return view('admin.order', compact('orders'));
    }
    // order detail
    public function order_details($orderId)
    {
        $order = Order::find($orderId);
        $orderItems = OrderItem::where('order_id', $orderId)->orderBy('id', 'desc')->paginate(12);
        $transaction = Transaction::where('order_id', $orderId)->first();
        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }
    // order update status
    public function order_update_status(Request $request)
    {
        $order = Order::find($request->order_id);
        $transaction = Transaction::where('order_id', $request->order_id)->first();
        $order->status = $request->status;
        if ($request->status == 'Đã vận chuyển') {
            $order->delivered_date = Carbon::now();
            $transaction->status = 'Đã vận chuyển';
            $transaction->save();
        } else if ($request->status == 'Đã hủy') {
            $order->canceled_date = Carbon::now();
        } else {
            $transaction->status = '';
            $order->canceled_date = '';
        }
        $order->save();
        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }

    // slides
    public function slides()
    {
        $slides = Slide::orderBy('id', 'desc')->paginate(12);
        return view('admin.slides', compact('slides'));
    }
    // add slide
    public function slide_add()
    {
        return view('admin.slide-add');
    }
    // store slide
    public function slide_store(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string',
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'links' => 'required|string',
            'status' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'tagline.required' => 'Khẩu hiệu không được để trống',
            'tagline.string' => 'Khẩu hiệu phải là chuỗi',
            'title.required' => 'Tiêu đề không được để trống',
            'title.string' => 'Tiêu đề phải là chuỗi',
            'subtitle.required' => 'Phụ đề không được để trống',
            'subtitle.string' => 'Phụ đề phải là chuỗi',
            'links.required' => 'Liên kết không được để trống',
            'links.string' => 'Liên kết phải là chuỗi',
            'status.required' => 'Trạng thái không được để trống',
            'image.required' => 'Hình ảnh không được để trống',
            'image.image' => 'Hình ảnh phải là ảnh',
            'image.mimes' => 'Hình ảnh phải có đuôi file jpeg, png, jpg, gif, svg',
            'image.max' => 'Hình ảnh không quá 2MB',
        ]);

        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->links = $request->links;
        $slide->status = $request->status;

        // lưu ảnh
        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateSlideImage($image, $file_name);
        $slide->image = $file_name;

        $slide->save();
        return redirect()->route('admin.slides')->with('success', 'Thêm slide thành công');
    }
    public function GenerateSlideImage($image, $file_name)
    {
        $destinationPath = public_path('uploads/slides');
        $img = Image::read($image->path());
        $img->cover(400, 690, 'top');
        $img->resize(400, 690, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $file_name);
    }
    // edit slide
    public function slide_edit($id)
    {
        $slide = Slide::find($id);
        return view('admin.slide-edit', compact('slide'));
    }
    // update slide
    public function slide_update(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string',
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'links' => 'required|string',
            'status' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'tagline.required' => 'Khẩu hiệu không được để trống',
            'tagline.string' => 'Khẩu hiệu phải là chuỗi',
            'title.required' => 'Tiêu đề không được để trống',
            'title.string' => 'Tiêu đề phải là chuỗi',
            'subtitle.required' => 'Phụ đề không được để trống',
            'subtitle.string' => 'Phụ đề phải là chuỗi',
            'links.required' => 'Liên kết không được để trống',
            'links.string' => 'Liên kết phải là chuỗi',
            'status.required' => 'Trạng thái không được để trống',
            'image.image' => 'Hình ảnh phải là ảnh',
            'image.mimes' => 'Hình ảnh phải có đuôi file jpeg, png, jpg, gif, svg',
            'image.max' => 'Hình ảnh không quá 2MB',
        ]);
        $slide = Slide::find($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->links = $request->links;
        $slide->status = $request->status;

        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
                File::delete(public_path('uploads/slides') . '/' . $slide->image);
            }
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateSlideImage($image, $file_name);
            $slide->image = $file_name;
        }

        $slide->save();
        toastr()->success('Cập nhật slide thành công', [], 'Thành công');
        return redirect()->route('admin.slides')->with('success', 'Cập nhật slide thành công');
    }
    // delete slide
    public function slide_delete($id)
    {
        $slide = Slide::find($id);
        if (File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
            File::delete(public_path('uploads/slides') . '/' . $slide->image);
        }
        $slide->delete();
        toastr()->success('Xóa slide thành công', [], 'Thành công');
        return redirect()->route('admin.slides')->with('success', 'Xóa slide thành công');
    }
}
