<?php

namespace Tests\Feature;

use App\Models\User\ProjectMember;

class v4_userRoutesTest extends API_V4_Test
{


	public function test_v4_access_groups()
	{
		/**@category V4_API
		 * @category Route Name: v4_access_groups.index
		 * @category Route Path: https://api.dbp.test/access/groups?v=4&key=1234
		 * @see      \App\Http\Controllers\User\AccessGroupController::index
		 */
		$path = route('v4_access_groups.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_access_groups.store
		 * @category Route Path: https://api.dbp.test/access/groups/?v=4&key=1234
		 * @see      \App\Http\Controllers\User\AccessGroupController::store
		 */
		$path = route('v4_access_groups.store', $this->params);
		echo "\nTesting The creation of a new Access Group at: $path";
		$response = $this->withHeaders($this->params)->post($path, [
			'name'        => 'TEST_CREATED_BY_TEST',
			'description' => 'A test Group Created Automatically',
		]);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_access_groups.show
		 * @category Route Path: https://api.dbp.test/access/groups/TEST_CREATED_BY_TEST?v=4&key=1234&pretty
		 * @see      \App\Http\Controllers\User\AccessGroupController::show
		 */
		$additional_params = ['id' => 'TEST_CREATED_BY_TEST'];
		$path              = route('v4_access_groups.show', array_merge($additional_params, $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_access_groups.update
		 * @category Route Path: https://api.dbp.test/access/groups/TEST_CREATED_BY_TEST?v=4&key=1234
		 * @see      \App\Http\Controllers\User\AccessGroupController::update
		 */
		$additional_params = ['id' => 'TEST_CREATED_BY_TEST'];
		$path              = route('v4_access_groups.update', array_merge($additional_params, $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path, ['description' => 'Shortened']);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_access_groups.destroy
		 * @category Route Path: https://api.dbp.test/access/groups/{group_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\AccessGroupController::destroy
		 */
		$additional_params = ['id' => 'TEST_CREATED_BY_TEST'];
		$path              = route('v4_access_groups.destroy', array_merge($additional_params, $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->delete($path);
		$response->assertSuccessful();
	}

/**
	public function test_v4_articles()
	{
		/**@category V4_API
		 * @category Route Name: v4_articles.index
		 * @category Route Path: https://api.dbp.test/articles?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::index

		$path = route('v4_articles.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_articles.store
		 * @category Route Path: https://api.dbp.test/articles?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::store

		$path    = route('v4_articles.store', $this->params);
		$article = [
			'cover'           => 'www.example.com/url/to/image.jpg',
			'cover_thumbnail' => 'www.example.com/url/to/image_thumbnail.jpg',
			'tags'            => [['iso' => 'eng', 'name' => 'Test Tag 1']],
			'translations'    => [
				['iso' => 'eng', 'name' => 'Test Title 1', 'body' => 'This is the body of the article'],
				['iso' => 'spa', 'name' => 'El Testo Articleo', 'body' => 'Soy el Conteno de Article'],
			],
		];
		echo "\nPosting to: $path";
		$response = $this->withHeaders($this->params)->post($path, $article);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_articles.show
		 * @category Route Path: https://api.dbp.test/articles/{article_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::show


		$path = route('v4_articles.show', array_merge(['name' => 'test-title-1'], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_articles.update
		 * @category Route Path: https://api.dbp.test/articles/{article_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::update

		$path = route('v4_articles.update', array_merge(['name' => 'test-title-1'], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path,
			['translations' => ['iso' => 'eng', 'body' => 'Updated Body']]);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_articles.destroy
		 * @category Route Path: https://api.dbp.test/articles/{article_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::destroy

		$path = route('v4_articles.destroy', array_merge(['name' => 'test-title-1'], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}
*/
	public function test_v4_resources()
	{
		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.index
		 * @category Route Path: https://api.dbp.test/resources?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::index
		 */
		$path = route('v4_resources.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.show
		 * @category Route Path: https://api.dbp.test/resources/{resource_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::show
		 */
		$path = route('v4_resources.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();


		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.update
		 * @category Route Path: https://api.dbp.test/resources/{resource_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::update
		 */
		$path = route('v4_resources.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();


		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.store
		 * @category Route Path: https://api.dbp.test/resources?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::store
		 */
		$path = route('v4_resources.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.destroy
		 * @category Route Path: https://api.dbp.test/resources/{resource_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::destroy
		 */
		$path = route('v4_resources.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}


	public function test_v4_users()
	{

		/**
		 * @category V4_API
		 * @category Route Name: v4_user.index
		 * @category Route Path: https://api.dbp.test/users?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::index
		 */
		$path = route('v4_user.index', array_add($this->params, 'project_id', '52341'));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user.store
		 * @category Route Path: https://api.dbp.test/users?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::store
		 */
		$new_user = [
			'avatar'      => 'example.com/avatar.jpg',
			'email'       => 'testerMcTester@gmail.com',
			'name'        => 'Tester McTesterson',
			'notes'       => 'A user generated by Feature Tests',
			'password'    => 'test_1234',
			'project_id'  => '52341'
		];
		$path = route('v4_user.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->post($path,$new_user);
		$response->assertSuccessful();

		// Ensure the new user matches the input
		$new_user = json_decode($response->getContent());
		$new_user = $new_user->data;
		$this->assertSame($new_user->avatar, 'example.com/avatar.jpg');
		$this->assertSame($new_user->email, 'testerMcTester@gmail.com');
		$this->assertSame($new_user->name, 'Tester McTesterson');

		/**
		 * @category V4_API
		 * @category Route Name: v4_user.update
		 * @category Route Path: https://api.dbp.test/users/{user_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::update
		 */
		$path = route('v4_user.update', array_merge(['user_id' => $new_user->id,'project_id' => '52341'],$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path, ['notes' => 'A user updated by Feature tests']);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user.show
		 * @category Route Path: https://api.dbp.test/users/1096385?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::show
		 */
		$path = route('v4_user.show', array_merge(['user_id' => '1096388','project_id' => '52341'],$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders(array_merge(['user_id' => '1096388','project_id' => '52341'],$this->params))->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user.destroy
		 * @category Route Path: https://api.dbp.test/users/{user_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::destroy
		 */
		$path = route('v4_user.destroy', array_merge(['user_id' => $new_user->id,'project_id' => '52341'],$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->delete($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.login
	 * @category Route Path: https://api.dbp.test/users/login?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController::login
	 */
	public function test_v4_user_login()
	{
		$login = ['email' => 'jonbitgood@gmail.com', 'password' => 'test_password123'];

		$path = route('v4_user.login', $this->params);
		echo "\nTesting Login Via Password: $path";
		$response = $this->withHeaders($this->params)->post($path, $login);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.geolocate
	 * @category Route Path: https://api.dbp.test/users/geolocate?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController::geoLocate
	 */
	public function test_v4_user_geolocate()
	{
		$path = route('v4_user.geolocate', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.oAuth
	 * @category Route Path: https://api.dbp.test/users/login/{driver}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController::getSocialRedirect
	 */
	public function test_v4_user_oAuth()
	{
		$path = route('v4_user.oAuth', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.oAuthCallback
	 * @category Route Path: https://api.dbp.test/users/login/{driver}/callback?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController::getSocialHandle
	 */
	public function test_v4_user_oAuthCallback()
	{
		$path = route('v4_user.oAuthCallback', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.password_reset
	 * @category Route Path: https://api.dbp.test/users/password/reset?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserPasswordsController::validatePasswordReset
	 */
	public function test_v4_user_password_reset()
	{
		$account = [
			'new_password'              => 'test_password123',
			'new_password_confirmation' => 'test_password123',
			'token_id'                  => '12345',
			'email'                     => 'jonbitgood@gmail.com',
			'project_id'                => '52341',
		];
		$path = route('v4_user.password_reset', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->post($path,$account);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.password_email
	 * @category Route Path: https://api.dbp.test/users/password/email?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserPasswordsController::triggerPasswordResetEmail
	 */
	public function test_v4_user_password_email()
	{

		$path = route('v4_user.password_email', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->post($path, ['email' => 'jonbitgood@gmail.com', 'project_id' => '52341']);
		$response->assertSuccessful();
	}


	public function test_v4_user_accounts()
	{

		/**
		 * @category V4_API
		 * @category Route Name: v4_user_accounts.index
		 * @category Route Path: https://api.dbp.test/accounts?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserAccountsController::index
		 */
		$project_connection = ProjectMember::inRandomOrder()->first();
		$project_fields = ['project_id' => $project_connection->project_id, 'user_id' => $project_connection->user_id];
		$path = route('v4_user_accounts.index', array_merge($project_fields,$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user_accounts.store
		 * @category Route Path: https://api.dbp.test/accounts?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserAccountsController::store
		 */
		$path = route('v4_user_accounts.store', array_merge($project_fields,$this->params));
		echo "\nTesting: $path";
		$account = [
			'user_id' => '5',
			'provider_id' => 'test',
			'provider_user_id' => '8179004',
		];
		$response = $this->withHeaders($this->params)->post($path, $account);
		$response->assertSuccessful();

		$test_account = json_decode($response->getContent());

		/**
		 * @category V4_API
		 * @category Route Name: v4_user_accounts.show
		 * @category Route Path: https://api.dbp.test//accounts/{account_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserAccountsController::show
		 */
		$project_fields = array_add($project_fields,'account_id',$test_account->id);
		$path = route('v4_user_accounts.show', array_merge($project_fields,$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user_accounts.update
		 * @category Route Path: https://api.dbp.test//accounts/{account_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserAccountsController::update
		 */
		$path = route('v4_user_accounts.update', array_merge($project_fields,$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path, ['provider_user_id' => 'aiorniga']);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user_accounts.destroy
		 * @category Route Path: https://api.dbp.test/accounts/{account_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserAccountsController::destroy
		 */
		$path = route('v4_user_accounts.destroy', array_merge($project_fields,$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->delete($path);
		$response->assertSuccessful();
	}


	public function test_v4_notes()
	{
		/**
		 * @category V4_API
		 * @category Route Name: v4_notes.index
		 * @category Route Path: https://api.dbp.test/users/5/notes?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserNotesController::index
		 */
		$path = route('v4_notes.index', array_add($this->params, 'user_id', '5'));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_notes.show
		 * @category Route Path: https://api.dbp.test/users/5/notes/127?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserNotesController::show
		 */
		$path = route('v4_notes.show', array_merge(['user_id' => 5,'note_id' => 127],$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_notes.store
		 * @category Route Path: https://api.dbp.test/users/5/notes?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserNotesController::store
		 */
		$test_note = [
			'user_id' => 5,
			'bible_id' => 'ENGESV',
			'book_id' => 'GEN',
			'chapter' => 1,
			'verse_start' => 1,
			'verse_end' => 2,
			'notes' => 'A generated test note',
		];
		$path = route('v4_notes.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->post($path, $test_note);
		$response->assertSuccessful();

		$test_created_note = json_decode($response->getContent())->data;

		/**
		 * @category V4_API
		 * @category Route Name: v4_notes.update
		 * @category Route Path: https://api.dbp.test/users/{user_id}/notes/{note_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserNotesController::update
		 */
		$path = route('v4_notes.update', array_merge(['user_id' => 5,'note_id' => $test_created_note->id],$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path, ['description' => 'A generated test note that has been updated']);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_notes.destroy
		 * @category Route Path: https://api.dbp.test/users/{user_id}/notes/{note_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserNotesController::destroy
		 */
		$path = route('v4_notes.destroy', array_merge(['user_id' => 5,'note_id' => $test_created_note->id],$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->delete($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_messages.index
	 * @category Route Path: https://api.dbp.test/users/messages?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserContactController::index

	public function test_v4_messages_index()
	{
		$path = route('v4_messages.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}
	 */

	/**
	 * @category V4_API
	 * @category Route Name: v4_messages.show
	 * @category Route Path: https://api.dbp.test/users/messages/{note_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserContactController::show

	public function test_v4_messages_show()
	{
		$path = route('v4_messages.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}
	 * */


	public function test_v4_bookmarks()
	{
		/**
		 * @category V4_API
		 * @category Route Name: v4_bookmarks.index
		 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserBookmarksController::index
		 */
		$path = route('v4_bookmarks.index', array_add($this->params,'user_id',5));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_bookmarks.store
		 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserBookmarksController::store
		 */
		$test_bookmark = [
			'bible_id'      => 'ENGESV',
			'user_id'       => 5,
			'book_id'       => 'GEN',
			'chapter'       => 1,
			'verse_start'   => 10,
		];
		$path = route('v4_bookmarks.store', array_add($this->params,'user_id',5));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->post($path, $test_bookmark);
		$response->assertSuccessful();

		$test_bookmark = json_decode($response->getContent())->data;

		/**
		 * @category V4_API
		 * @category Route Name: v4_bookmarks.update
		 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks/{bookmark_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserBookmarksController::update
		 */
		$path = route('v4_bookmarks.update', array_merge(['user_id' => 5,'bookmark_id' =>$test_bookmark->id],$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path,['book_id' => 'EXO']);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_bookmarks.destroy
		 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks/{bookmark_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserBookmarksController::destroy
		 */
		$path = route('v4_bookmarks.destroy', array_merge(['user_id' => 5,'bookmark_id' =>$test_bookmark->id],$this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->delete($path);
		$response->assertSuccessful();
	}


	public function test_v4_highlights()
	{
		/**
		 * @category V4_API
		 * @category Route Name: v4_highlights.index
		 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserHighlightsController::index
		 */
		$path = route('v4_highlights.index', array_add($this->params,'user_id', 5));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_highlights.store
		 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserHighlightsController::store
		 */
		$test_highlight_post = [
			'bible_id'          => 'ENGESV',
			'user_id'           => 5,
			'book_id'           => 'GEN',
			'chapter'           => '1',
			'verse_start'       => '1',
			'reference'         => 'Genesis 1:1',
			'highlight_start'   => '10',
			'highlighted_words' => '40',
			'highlighted_color' => '#fff000',
		];

		$path = route('v4_highlights.store', array_add($this->params,'user_id', 5));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->post($path, $test_highlight_post);
		$response->assertSuccessful();

		$test_highlight = json_decode($response->getContent())->data;

		/**
		 * @category V4_API
		 * @category Route Name: v4_highlights.update
		 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights/{highlight_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserHighlightsController::update
		 */
		$path = route('v4_highlights.update', array_merge(['user_id' => 5,'highlight_id' => $test_highlight->id], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path,['highlighted_color' => '#ff1100']);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_highlights.destroy
		 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights/{highlight_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UserHighlightsController::destroy
		 */
		$path = route('v4_highlights.destroy', array_merge(['user_id' => 5,'highlight_id' => $test_highlight->id], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->delete($path);
		$response->assertSuccessful();
	}


}