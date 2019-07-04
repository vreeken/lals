<?php

namespace App\Http\Controllers\Members;

use App\Models\Members\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;

class MembersController extends Controller {

	public function index() {
		/*$follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

		$postCount = Cache::remember(
			'count.posts.' . $user->id,
			now()->addSeconds(30),
			function () use ($user) {
				return $user->posts->count();
			});

		$followersCount = Cache::remember(
			'count.followers.' . $user->id,
			now()->addSeconds(30),
			function () use ($user) {
				return $user->profile->followers->count();
			});

		$followingCount = Cache::remember(
			'count.following.' . $user->id,
			now()->addSeconds(30),
			function () use ($user) {
				return $user->following->count();
			});

		return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));*/
		$user = Auth::user();
		$links = $user->getMemberLinks();
		
		if ($user->isAdmin()) {
			array_unshift($links, ['text'=>'Admin Dashboard', 'href'=>'admin_dashboard', 'role'=>'any', 'icon'=>'<i class="fas fa-tachometer-alt"></i>']);
		}
		return view('members.dashboard', ['links'=>$links]);
	}


	public function showInvoices(Request $request) {
		//$user = Auth::user();

		return view('members.invoices');
	}






	/*public function edit(User $user) {
		$this->authorize('update', $user->profile);

		return view('profiles.edit', compact('user'));
	}

	public function update(User $user) {
		$this->authorize('update', $user->profile);

		$data = request()->validate([
			'title' => 'required',
			'description' => 'required',
			'url' => 'url',
			'image' => '',
		]);

		if (request('image')) {
			$imagePath = request('image')->store('profile', 'public');

			$image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
			$image->save();

			$imageArray = ['image' => $imagePath];
		}

		auth()->user()->profile->update(array_merge(
			$data,
			$imageArray ?? []
		));

		return redirect("/profile/{$user->id}");
	}*/
}
