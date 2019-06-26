<?php

namespace Tests\Feature;

//use App\Models\Members\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesPermissionsTest extends TestCase {
    public function testAdminUserCanVisitAdminsPage() {
    	$this->withExceptionHandling();
		$user = create('App\Models\Members\User');

		$this->signIn($user);

		Role::create(['name' => 'super admin']);
		$user->assignRole('super admin');

		$this->get('/admin')
			->assertSee('Admin Dashboard');
    }

	public function testNonAdminUserCannotVisitAdminsPage() {
		$this->withExceptionHandling();
		$user = make('App\Models\Members\User');

		$this->signIn($user);

		$this->get('/admin')
			->assertStatus(403);
	}

}
