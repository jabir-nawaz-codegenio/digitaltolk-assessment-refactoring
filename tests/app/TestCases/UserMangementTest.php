<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\UserMeta;
use App\Models\Type;
use App\Models\UsersBlacklist;
use App\Models\UserLanguages;
use App\Models\Town;
use App\Models\UserTowns;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase; // Resets the database after each test

    // Test the createOrUpdate function for a new user (no $id)
    public function testCreateNewUser()
    {
        // Simulate a request with all required attributes
        $request = [
            'role' => 'customer',
            'name' => 'John Doe',
            // ... provide other required attributes ...
        ];

        // Call the function with null $id for a new user
        $result = $this->callCreateOrUpdate(null, $request);

        // Assertions
        $this->assertInstanceOf(User::class, $result); // Check if the result is an instance of User model
        $this->assertDatabaseHas('users', ['name' => 'John Doe']); // Check if the user is saved in the database
        $this->assertEquals('customer', $result->user_type); // Check if user_type is set correctly
        // ... other assertions for user_meta, company, department, etc. ...

        // Add more assertions to check other attributes and relationships
    }

    // Test the createOrUpdate function for an existing user (with $id)
    public function testUpdateExistingUser()
    {
        // Create a user in the database for testing
        $user = User::factory()->create(['name' => 'Existing User']);

        // Simulate a request with updated attributes
        $request = [
            'role' => 'translator',
            'name' => 'Updated User',
            // ... provide other updated attributes ...
        ];

        // Call the function with the existing user's $id
        $result = $this->callCreateOrUpdate($user->id, $request);

        // Assertions
        $this->assertInstanceOf(User::class, $result); // Check if the result is an instance of User model
        $this->assertDatabaseHas('users', ['name' => 'Updated User']); // Check if the user is updated in the database
        $this->assertEquals('translator', $result->user_type); // Check if user_type is updated correctly
        // ... other assertions for user_meta, user_languages, etc. ...

        // Add more assertions to check other attributes and relationships
    }

    // Helper function to call the createOrUpdate method and return the result
    private function callCreateOrUpdate($id, $request)
    {
        $userManagement = new UserManagement(); // Replace with the actual class name of the function

        // Make the method accessible (optional if the method is public)
        $method = new \ReflectionMethod(UserManagement::class, 'createOrUpdate');
        $method->setAccessible(true);

        // Call the method and return the result
        return $method->invoke($userManagement, $id, $request);
    }
}
