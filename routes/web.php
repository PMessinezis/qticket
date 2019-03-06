<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Auth::routes();

// Route::get('/checksensors', function() { return readCRSensors(); });

Route::group(['middleware' => ['SSO']], function () {

	Route::get('/', function () {
		// dd($_SERVER);
		if ($_SERVER['REQUEST_URI'] == '/') {
			return view('layouts.main');
		} else {
			return redirect('/');
		}

	});

// Route::get('/newticket', 'TicketController@create');

	Route::get('/ticket/', function () {return redirect('/');});

	Route::post('/newticket', 'TicketController@new');

	Route::get('/attached/{attachment}/{whatever}', 'TicketController@getAttachment');
	Route::get('/export', function () {
		return Excel::download(new \App\Exports\TicketsExport, 'tickets_' . date("Ymd.Hi") . '.xlsx');

	}
	);

// Route::get('/test', function(){
	// 						$class="App\TicketUpdate";
	// 						return $class::fields2check;

// 					}
	// 		);

});

Route::get('admin', '\Backpack\Base\app\Http\Controllers\AdminController@redirect')->name('backpack');

Route::group(['prefix' => 'json', 'middleware' => ['SSO']], function () {
	Route::get('user', function () {return Auth::User()->load('resolver');});

	Route::get('static', 'TicketController@getStaticData');

	Route::get('categories', function () {return App\Category::all();});
	Route::get('statuses', function () {return App\Status::all();});
	Route::get('groups', function () {return App\Group::where("isActive", true)->orderBy("name")->get();});
	Route::get('resolvers', function () {return App\Resolver::with('user')->orderBy("lastname")->get();});
	Route::get('vendors', function () {return App\Vendor::all();});
	Route::get('rootcauses', function () {return App\RootCause::all();});
	Route::get('users', function () {return App\User::orderBy('lastname')->get();});
	Route::get('alert', function () {
		$alerts = DB::table('alerts')->select('*')->orderBy('id', 'desc')->get();
		return (count($alerts) ? $alerts[0]->alert : "");
	});
	Route::post('/setalert', function () {
		if (Auth::User()->isAdmin()) {
			$rec = ['alert' => request('alert', NULL), 'updatedBy' => Auth::User()->uid];
			DB::table('alerts')->insert($rec);
			return 'OK';
		}
	});

	Route::get('search', 'TicketController@ticketSearch');
	Route::get('tickets', 'TicketController@tickets');
	Route::get('ticket/{ticket}', 'TicketController@ticketGet');

	Route::post('/ticket/{ticket}', 'TicketController@ticketPost');
	Route::post('/reopen/{ticket}', 'TicketController@ticketReopen');
	Route::post('/close/{ticket}', 'TicketController@ticketClose');
	Route::post('/cancel/{ticket}', 'TicketController@ticketCancel');

	Route::get('username/{name}', function ($name) {
		$k = "%$name%";
		return array_column(DB::select("select uid from users where lastname like  '" . $k . "' or email like  '" . $k . "'"), 'uid');
	});

});

Route::group(['prefix' => 'admin', 'middleware' => ['SSO', 'admin']], function () {
	Route::get('dashboard', '\Backpack\Base\app\Http\Controllers\AdminController@dashboard')->name('backpack.dashboard');

	CRUD::resource('source', 'SourceController');
	CRUD::resource('type', 'TypeController');
	CRUD::resource('status', 'StatusController');
	CRUD::resource('category', 'CategoryController');
	CRUD::resource('subcategory', 'SubcategoryController');
	CRUD::resource('rootcause', 'RootCauseController');
	CRUD::resource('vendor', 'VendorController');
	CRUD::resource('department', 'DepartmentController');
	CRUD::resource('group', 'GroupController');
	CRUD::resource('resolver', 'ResolverController');
	CRUD::resource('user', 'UserController');
	CRUD::resource('ticket', 'TicketController');

});

Route::get('guide/{guide}', function ($guide) {
	$guides = getGuides();
	$file = "";
	foreach ($guides as $key => $g) {
		if ($guide == $g['id']) {
			$file = $g['file'];
			break;
		}
	}
	if ($file > '') {
		$file = config('qticket.guides') . "\\$file";
		$headers = [
			'Content-Type' => \File::mimeType($file),
		];
		return response()->file($file, $headers);

	} else {
		return redirect('/');
	}
});

Route::group(['middleware' => ['SSO']], function () {

	Route::get('/mail/ticketOpened/{ticket}', function (App\Ticket $ticket) {
		if ($ticket) {
			return new App\Mail\TicketOpened($ticket);
		} else {
			return redirect('/');
		};
	});

	Route::get('/{id}', function ($id) {
		if (strtoupper(substr($id, 0, 2)) == 'QT' && strlen($id) > 2) {
			$id = intval(substr($id, 2, strlen($id) - 2));
		}
		$ticket = App\Ticket::find($id);
		$user = Auth::user();
		$uid = $user->uid;
		if (isset($ticket->id)) {
			if ($user->isResolver ||
				$uid == $ticket->requestedBy_uid || ($uid == $ticket->onBehalfOf_uid)
			) {
				return redirect('/')->with('preload_ticket_id', $ticket->id);
			}
		}
		// dd($ticket,$uid);
		return redirect('/');

	});

});
