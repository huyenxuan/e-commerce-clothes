<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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

    // khởi tạo hàm brands
    public function brands()
    {
        // gọi brand
        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        // gọi view admin.brands với dữ liệu truyền vào
        return view('admin.brands', compact('brands'));
    }

    // add brand
    public function add_brand()
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

        // toastr()->success('Thêm thương hiệu thành công');
        // ->with('success', 'Thêm thương hiệu thành công')
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
            'slug' => ['required', 'unique:brands,slug'],
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

        // toastr()->success('Cập nhật thương hiệu thành công');
        // ->with('success', 'Thêm thương hiệu thành công')
        return redirect()->route('admin.brands')->with('ư', 'Thêm thương hiệu thành công');
    }

    // delete brand
    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
            File::delete(public_path('uploads/brands') . '/' . $brand->image);
        }
        $brand->delete();
        // toastr()->success('Xóa thương hiệu thành công');
        // ->with('success', 'Xóa thương hiệu thành công')
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
        $img = Image::read($image->path());  // Changed 'read' to 'make'
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

}
