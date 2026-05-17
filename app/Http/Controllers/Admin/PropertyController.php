<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('category')->latest();
        if ($request->filled('q')) {
            $query->where('name','like','%'.$request->q.'%')->orWhere('city','like','%'.$request->q.'%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        $properties = $query->paginate(15)->withQueryString();
        $categories = Category::all();
        return view('admin.properties.index', compact('properties','categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.properties.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:200',
            'description'    => 'required|string',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'price_per_night'=> 'required|numeric|min:1',
            'total_rooms'    => 'required|integer|min:1',
            'max_guests'     => 'required|integer|min:1',
            'facilities'     => 'nullable|array',
            'status'         => 'required|in:active,inactive',
            'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'images.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);
        $data['facilities'] = $request->facilities ?? [];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('properties/thumbnails','public');
        }

        $property = Property::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('properties/images','public');
                PropertyImage::create(['property_id' => $property->id, 'image_path' => $path]);
            }
        }

        return redirect()->route('admin.properties.index')->with('success','Properti berhasil ditambahkan.');
    }

    public function edit(Property $property)
    {
        $categories = Category::all();
        return view('admin.properties.edit', compact('property','categories'));
    }

    public function update(Request $request, Property $property)
    {
        $data = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:200',
            'description'    => 'required|string',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'price_per_night'=> 'required|numeric|min:1',
            'total_rooms'    => 'required|integer|min:1',
            'max_guests'     => 'required|integer|min:1',
            'facilities'     => 'nullable|array',
            'status'         => 'required|in:active,inactive',
            'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data['facilities'] = $request->facilities ?? [];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('properties/thumbnails','public');
        }

        $property->update($data);
        return redirect()->route('admin.properties.index')->with('success','Properti berhasil diperbarui.');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return back()->with('success','Properti berhasil dihapus.');
    }
}
