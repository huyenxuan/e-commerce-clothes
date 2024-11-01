<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    // khởi tạo hàm index
    public function index()
    {
        // gọi view admin.index với dữ liệu truyền vào
        return view('admin.index');
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

        toastr()->success('Thêm thương hiệu thành công', [], 'Thành công');
        // ->with('success', 'Thêm thương hiệu thành công')
        return redirect()->route('admin.brands');
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

        toastr()->success('Cập nhật thương hiệu thành công', [], 'Thành công');
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
        toastr()->success('Xóa thương hiệu thành công', [], 'Thành công');
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

        toastr()->success('Thêm danh mục thành công', [], 'Thành công');
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

        // toastr()->success('Cập nhật thương hiệu thành công');
        // ->with('success', 'Thêm thương hiệu thành công')
        toastr()->success('Cập nhật danh mục thành công', [], 'Thành công');
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
        toastr()->success('Cập nhật danh mục thành công', [], 'Thành công');
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
            'sale_price' => ['required'],
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
            'sale_price.required' => 'Giá khuyến mãi không được để trống',
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
        toastr()->success('Thêm sản phẩm thành công', [], 'Thành công');
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
            'sale_price' => ['required'],
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
            'sale_price.required' => 'Giá khuyến mãi không được để trống',
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
        // lưu vào database
        $product->save();
        toastr()->success('Cập nhật sản phẩm thành công', [], 'Thành công');
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
        toastr()->success('Xóa sản phẩm thành công', [], 'Thành công');
        return redirect()->route('admin.products')->with('success', 'Xóa sản phẩm thành công');
    }
}
