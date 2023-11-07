<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CookieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get Cookies List
        $cookies = Cookie::get();
        // dd($cookies);
        return view('cookie',[
            'best_cookie'=>'Snickerdoodles',
            'cookies' => $cookies
        ]);
    }

    /**
     * Return Cookies List API
     * 
     * @return JSON $cookies
     */
    public function get_cookies()
    {
        $cookies = Cookie::get();
        return response()->json([
            'message'   =>  'Cookie List',
            'status'    =>  'success',
            'cookies'   =>  $cookies
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('cookie.index')
                ->with('error', 'Validation failed')
                ->withErrors($validator)
                ->withInput();
        }        
    
        $newCookie = new Cookie;
        $newCookie->title = $request->title;
        $newCookie->description = $request->description;
    
        // Handle image upload and save the image path
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
            $newCookie->image = str_replace('public/', 'storage/', $imagePath);
        }
    
        $newCookie->save();
    
        return redirect()->route('cookie.index')
            ->with('success', 'Cookie Created');
    }

    /**
     * Create Cookie API
     */
    public function create_cookie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $newCookie = new Cookie;
        $newCookie->title = $request->title;
        $newCookie->description = $request->description;
        $newCookie->save();

        return response()->json([
            'message' => 'Cookie Created',
            'status' => 'success',
            'new_cookie' => $newCookie
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cookie = Cookie::find($id);
        return view('edit_cookie', [
            'cookie' => $cookie,
        ]);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('cookie.edit', $id)
                ->with('error', 'Validation failed')
                ->withErrors($validator)
                ->withInput();
        }
    
        $cookie = Cookie::find($id);
    
        if (!$cookie) {
            return redirect()->route('cookie.index')
                ->with('error', 'Cookie not found');
        }
    
        $cookie->title = $request->title;
        $cookie->description = $request->description;
    
        // Handle image upload and update the image path
        if ($request->hasFile('image')) {
            // Delete the old image (if it exists)
            if ($cookie->image) {
                Storage::delete(str_replace('storage/', 'public/', $cookie->image));
            }
    
            $imagePath = $request->file('image')->store('public/images');
            $cookie->image = str_replace('public/', 'storage/', $imagePath);
        }
    
        $cookie->save();
    
        return redirect()->route('cookie.index')
            ->with('success', 'Cookie Updated');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cookie = Cookie::find($id);

        if ($cookie) {
            $cookie->delete();
            session()->flash('success', 'Cookie deleted successfully');
        } else {
            session()->flash('error', 'Cookie not found');
        }

        return redirect('/cookie');
    }

}
