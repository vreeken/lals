<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
	protected $guarded = [];

	public static function insertDefaultCalendars() {
		$defaultCalendars = [
			[
				'title' => 'LALSRM Public Calendar',
				'permission' => 0
			],
			[
				'title' => 'LALSRM Members Only Calendar',
				'permission' => 1
			],
			[
				'title' => 'LALSRM Admins Only Calendar',
				'permission' => 2
			]
		];

		foreach ($defaultCalendars as $cal) {
			$c = Calendar::where('title', $cal['title'])->first();
			if ($c) { continue; }
			Calendar::create([
				'title' => $cal['title'],
				'permission' => $cal['permission']
			]);
		}
	}
}
