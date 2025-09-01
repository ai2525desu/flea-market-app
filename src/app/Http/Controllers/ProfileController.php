<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user()->load('profile', 'items', 'purchases');
        $tab = $request->query('tab', 'sell');
        return view('profiles.show', compact('user', 'tab'));
    }

    public function exhibitionList(Request $request)
    {
        $user = Auth::user()->load('profile', 'items');
        $tab = $request->query('tab', 'sell');
        return view('profiles.show', compact('user', 'tab'));
    }

    public function purchaseList(Request $request)
    {
        $user = Auth::user()->load('profile', 'purchases');
        $tab = $request->query('tab', 'buy');
        return view('profiles.show', compact('user', 'tab'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profiles.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {

        $user = Auth::user();

        $oldImage = $request->old_image;

        if ($request->hasFile('image')) {
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            $imagePath = $request->file('image')->store('profiles', 'public');
        } else {
            $imagePath = $oldImage;
        }

        $user->profile()->updateOrCreate(
            [],
            [
                'image' => $imagePath
            ]
        );

        $user->address()->updateOrCreate(
            [],
            [
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building
            ]
        );
        return redirect()->route('profiles.show');
    }
}
