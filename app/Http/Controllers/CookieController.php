<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Predis\Client;

class CookieController extends Controller
{
    private $redis;

    public function __construct()
    {
        $this->redis = new Client();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get Cookies List
        $cookies = Cookie::paginate(5);
        // dd($cookies);
        return view('cookie',[
            'best_cookie'=>'Snickerdoodles',
            'cookies' => $cookies
        ]);
    }

    /**
     * Show the detail page for the specified resource.
     */
    public function show($id)
    {
        $cookie = Cookie::find($id);

        if (!$cookie) {
            return response()->json(['error' => 'Cookie not found'], 404);
        }
        // Increase view count using Redis
        $this->incrementViewCount($cookie);
        $viewCount = $this->getViewCount($cookie);

        return response()->json([
            'message'   =>  'Cookie Detail',
            'status'    =>  'success',
            'cookies'   =>  $cookie,
            'view_count'   =>  $viewCount
        ]);
    }

    /**
     * Increment the view count of a cookie using Redis.
     */
    private function incrementViewCount(Cookie $cookie)
    {
        $redisKey = "popular_cookies";
        $cookieId = $cookie->id;

        // Use pipeline for atomicity
        $this->redis->pipeline(function ($pipe) use ($redisKey, $cookieId) {
            // Increment the view count
            $pipe->zincrby($redisKey, 1, $cookieId);
        });
    }
    
    /**
     * Get the view count of a cookie using Predis.
     */
    private function getViewCount(Cookie $cookie)
    {
        $redisKey = "popular_cookies";
        $cookieId = $cookie->id;

        // Get the view count
        $viewCount = $this->redis->zscore($redisKey, $cookieId);

        return $viewCount ?? 0;
    }

    /**
     * Get the top 3 most popular cookies based on views using Predis.
     */
    private function getTop3PopularCookies()
    {
        $redisKey = "popular_cookies";

        // Get all cookies with their view counts
        $cookiesWithViews = $this->redis->zrevrange($redisKey, 0, -1, 'WITHSCORES');

        // Filter out cookies with zero views
        $cookiesWithViews = array_filter($cookiesWithViews, function ($viewCount) {
        return $viewCount > 0;
        });
        
        // Take only the top 3 cookies
        $top3Cookies = array_slice($cookiesWithViews, 0, 3, true);

        // Get the actual Cookie models for the top 3 cookies
        $cookieIds = array_keys($top3Cookies);
        $top3Cookies = collect();
        foreach ($cookieIds as $cookieId) {
            $cookie = Cookie::find($cookieId);
            if ($cookie) {
                $top3Cookies->push($cookie);
            }
        }
        // Attach the view count to each cookie
        $top3CookiesWithViews = [];
        foreach ($top3Cookies as $cookie) {
            $cookieId = $cookie->id;
            $viewCount = $cookiesWithViews[$cookieId] ?? 0;
            $top3CookiesWithViews[] = ['cookie' => $cookie, 'views' => $viewCount];
        }

        return $top3CookiesWithViews;
    }


    /**
     * Return Cookies List API
     * 
     * @return JSON $cookies
     */
    public function get_cookies()
    {
        $cookies = Cookie::with('category')->get();
        $top3Cookies = $this->getTop3PopularCookies();

        return response()->json([
            'message'   =>  'Cookie List',
            'status'    =>  'success',
            'top3Cookies' => $top3Cookies,
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
