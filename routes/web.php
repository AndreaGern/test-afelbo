<?php

use App\Http\Controllers\AccountabilityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingTypeController;
use App\Http\Controllers\StoneClassController;
use App\Http\Controllers\StoneTypeController;
use App\Models\Commission;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


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

Route::get('/', function () {
    $notDeliveredCommissions = Commission::where('stato_lavorazione', '!=', 'consegnato')->get();
    $tasksNotCompleted = Task::notCompletedCount();
    $tasks = null;
    if (Auth::user()->isOnlyOperator() && Auth::user()->operator) {
        $tasks = Auth::user()->operator->tasks()->where('completed', false)->has('process')->has('distribution.order')->orderByDesc('created_at')->paginate(10);
    }
    return view('welcome', compact('notDeliveredCommissions', 'tasksNotCompleted', 'tasks'));
})->name('homepage')->middleware('auth');

Route::put('/cambia-ruolo', function () {
    $user = User::find(Auth::id());
    if (!$user) {
        return redirect()->back()->withErrors('Attenzione! Area non autorizzata');
    }
    if ($user->email === 'cristianafelbo@gmail.com') {
        if ($user->role === 'operator') {
            $user->update(['role' => 'admin']);
        } elseif ($user->role === 'admin') {
            $user->update(['role' => 'operator']);
        }
        return redirect(route('homepage'))->with('message', 'Area cambiata con successo');
    }
    return redirect()->back()->withErrors('Attenzione! Area non autorizzata');
})->middleware('auth')->name('changeRole');




Route::prefix('operator')->middleware('auth')->group(function () {
    Route::name('operator.')->middleware('isOperator')->group(function () {
        //? Sezione distribuzioni a lui assegnate.
        Route::get('/lavorazioni', [OperatorController::class, 'tasksIndex'])->name('tasksIndex');
        Route::get('/lavorazioni-in-corso', [OperatorController::class, 'notCompletedTasksIndex'])->name('notCompletedTasksIndex');
        Route::get('/change-date', [OperatorController::class, 'changeDateTasksIndex'])->name('changeDateTasksIndex');
        Route::get('/lavorazioni/ripartizione/{distribution}', [OperatorController::class, 'distributionShow'])->name('distributionShow');
        Route::put('/lavorazione/{task}/ordine/quantità-completata', [OperatorController::class, 'updateCompletedQuantity'])->name('updateCompletedQuantity');
        Route::put('/lavorazione/{task}/ordine/annulla-quantità-completata', [OperatorController::class, 'undoCompletedQuantity'])->name('undoCompletedQuantity');
        //? Solo vedere suoi movimenti finanziari.
        Route::get('/movimenti-finanziari', [OperatorController::class, 'movementsDetail'])->name('movementsDetail');

        //?Rotte reset password primo accesso operatore
        Route::get('/reset-password/primo-accesso', [OperatorController::class, 'firstLoginReset'])->name('firstLoginReset');
        Route::put('/reset-password/save-new-password', [OperatorController::class, 'saveNewPassword'])->name('saveNewPassword');
    });
});


Route::middleware('isAdmin')->prefix('admin')->group(function () {
    Route::name('operator.')->group(function () {
        Route::get('/operatori', [OperatorController::class, 'index'])->name('index');
        Route::prefix('/operatore')->group(function () {
            Route::post('/store', [OperatorController::class, 'store'])->name('store');
            Route::get('/visualizza-lavorazioni/{operator}', [OperatorController::class, 'show'])->name('show');
            Route::get('/filtra-lavorazioni/{operator}', [OperatorController::class, 'filterDistributions'])->name('filterDistributions');
            Route::get('/modifica/{operator}', [OperatorController::class, 'edit'])->name('edit');
            Route::put('/update/{operator}', [OperatorController::class, 'update'])->name('update');
            Route::delete('/delete/{operator}', [OperatorController::class, 'destroy'])->name('delete');
            Route::put('/reset-password/{operator}', [OperatorController::class, 'resetPassword'])->name('resetPassword');
        });
    });

    Route::name('client.')->group(function () {
        Route::get('/clienti', [ClientController::class, 'index'])->name('index');
        Route::prefix('/cliente')->group(function () {
            Route::post('/store', [ClientController::class, 'store'])->name('store');
            Route::get('/modifica/{client}', [ClientController::class, 'edit'])->name('edit');
            Route::put('/update/{client}', [ClientController::class, 'update'])->name('update');
            Route::delete('/delete/{client}', [ClientController::class, 'destroy'])->name('delete');
        });
    });

    Route::name('product.')->group(function () {
        Route::get('/schedario', [ProductController::class, 'index'])->name('index');
        Route::get('/aggiungi-prodotto', [ProductController::class, 'create'])->name('create');
        Route::post('/products/store', [ProductController::class, 'store'])->name('store');
        Route::delete('/products/delete/{product}', [ProductController::class, 'delete'])->name('delete');
    });

    Route::name('stoneType.')->group(function () {
        Route::get('/tipi-pietre', [StoneTypeController::class, 'index'])->name('index');
        Route::post('/stoneType/store', [StoneTypeController::class, 'store'])->name('store');
        Route::get('/stoneType/edit/{stoneType}', [StoneTypeController::class, 'edit'])->name('edit');
        Route::put('/stoneType/update/{stoneType}', [StoneTypeController::class, 'update'])->name('update');
        Route::delete('/stoneType/delete/{stoneType}', [StoneTypeController::class, 'delete'])->name('delete');
    });

    Route::name('stoneClass.')->group(function () {
        Route::get('/classi-pietre', [StoneClassController::class, 'index'])->name('index');
        Route::post('/gemsclass/store', [StoneClassController::class, 'store'])->name('store');
        Route::get('/gemsclass/edit/{stoneClass}', [StoneClassController::class, 'edit'])->name('edit');
        Route::put('/gemsclass/update/{stoneClass}', [StoneClassController::class, 'update'])->name('update');
        Route::delete('/gemsclass/delete/{stoneClass}', [StoneClassController::class, 'destroy'])->name('delete');
    });

    Route::name('settingType.')->group(function () {
        Route::get('/incastonature', [SettingTypeController::class, 'index'])->name('index');
        Route::post('/gemssetting/store', [SettingTypeController::class, 'store'])->name('store');
        Route::get('/gemssetting/edit/{settingType}', [SettingTypeController::class, 'edit'])->name('edit');
        Route::put('/gemssetting/update/{settingType}', [SettingTypeController::class, 'update'])->name('update');
        Route::delete('/gemssetting/delete/{settingType}', [SettingTypeController::class, 'destroy'])->name('delete');
    });

    Route::name('commission.')->group(function () {
        Route::get('/commesse', [CommissionController::class, 'index'])->name('index');
        Route::get('/aggiungi-commessa', [CommissionController::class, 'create'])->name('create');
        Route::post('/salva-commessa', [CommissionController::class, 'store'])->name('store');
        Route::get('/modifica-commessa/{commission}', [CommissionController::class, 'edit'])->name('edit');
        Route::put('/aggiorna-commessa/{commission}', [CommissionController::class, 'update'])->name('update');
        Route::delete('/cliente/{client}/elimina-commessa/{commission}', [CommissionController::class, 'destroy'])->name('delete');
        Route::get('/scarica-commessa/{commission}', [CommissionController::class, 'downloadCommissionPdf'])->name('downloadCommissionPdf');
        Route::put('/consegna-commessa/{commission}', [CommissionController::class, 'deliverCommission'])->name('deliverCommission');
    });

    Route::name('order.')->group(function () {
        Route::get('/seleziona-prodotto-dal-db/commessa/{commission}', [OrderController::class, 'selectDbProduct'])->name('select-db-product');
        Route::get('/crea-ordine-dal-db/commessa/{commission}/prodotto/{product}', [OrderController::class, 'createFromDb'])->name('create-from-db');
        Route::post('/salva-ordine-dal-db/commessa/{commission}/prodotto/{product}', [OrderController::class, 'storeFromDb'])->name('store-from-db');
        Route::get('/crea-ordine/commessa/{commission}', [OrderController::class, 'create'])->name('create');
        Route::post('/salva-ordine/commessa/{commission}', [OrderController::class, 'store'])->name('store');
        Route::delete('/elimina-ordine/commessa/{commission}/ordine/{order}', [OrderController::class, 'destroy'])->name('delete');
        Route::get('/modifica-ordine/commessa/{commission}/ordine/{order}', [OrderController::class, 'edit'])->name('edit');
        Route::put('/aggiorna-ordine/commessa/{commission}/ordine/{order}', [OrderController::class, 'update'])->name('update');
        Route::put('/consegna-prodotti-ordine/commessa/{commission}/ordine/{order}/', [OrderController::class, 'deliverProducts'])->name('deliverProducts');
        Route::get('/visualizza-ordine/commessa/{commission}/ordine/{order}', [OrderController::class, 'show'])->name('show');
    });

    Route::name('distribution.')->group(function () {
        Route::get('/ripartisci-lavoro/ordine/{order}', [DistributionController::class, 'create'])->name('create');
        Route::post('/salva-ripartizione/ordine/{order}', [DistributionController::class, 'store'])->name('store');
        Route::get('/ripartisci-lavoro/ordine/{order}/distribuzione-unitaria/{distribution}', [DistributionController::class, 'createUnitaryDistribution'])->name('createUnitaryDistribution');
        Route::get('/ripartisci-lavoro/ordine/{order}/distribuzione-parziale/{distribution}', [DistributionController::class, 'createPartialDistribution'])->name('createPartialDistribution');
    });

    Route::name('accountability.')->group(function () {
        Route::prefix('/contabilita')->group(function () {
            Route::get('/dipendenti', [AccountabilityController::class, 'operators'])->name('operators');
            Route::get('/dipendente/{operator}', [AccountabilityController::class, 'operator'])->name('operator');
            Route::get('/clienti', [AccountabilityController::class, 'clients'])->name('clients');
            Route::get('/cliente/{client}', [AccountabilityController::class, 'client'])->name('client');
            Route::post('/cliente/{client}/aggiungi-movimento', [AccountabilityController::class, 'newMovementClient'])->name('newMovementClient');
            Route::post('/dipendente/{operator}/aggiungi-movimento', [AccountabilityController::class, 'newMovementOperator'])->name('newMovementOperator');
            Route::delete('/cliente/{client}/movimento/{movement}/elimina-movimento', [AccountabilityController::class, 'deleteMovementClient'])->name('deleteMovementClient');
            Route::delete('/operatore/{operator}/movimento/{movement}/elimina-movimento', [AccountabilityController::class, 'deleteMovementOperator'])->name('deleteMovementOperator');
            Route::put('/operatore/azzera-contatori/{operator}', [AccountabilityController::class, 'resetCountersOperator'])->name('resetCountersOperator');
            Route::put('/cliente/azzera-contatori/{client}', [AccountabilityController::class, 'resetCountersClient'])->name('resetCountersClient');
        });
    });
});

Route::get('/insertall', function () {
    DB::table('users')->insert([
        [
            'id' => '1',
            'name' => 'Cristian Afelbo',
            'email' => 'cristianafelbo@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'

        ],
        [
            'id' => '2',
            'name' => 'Mario Rossi',
            'email' => 'mario@rossi.it',
            'password' => Hash::make('12345678'),
            'role' => 'operator'
        ],
        [
            'id' => '3',
            'name' => 'Sarah Donvito',
            'email' => 'sarah@donvito.it',
            'password' => Hash::make('12345678'),
            'role' => 'operator'
        ],
    ]);
    DB::table('operators')->insert([
        [
            'id' => '1',
            'code' => '1',
            'workstation' => '1',
            'user_id' => '1',
        ],
        [
            'id' => '2',
            'code' => '1',
            'workstation' => '1',
            'user_id' => '2',
        ],
        [
            'id' => '3',
            'code' => '1',
            'workstation' => '1',
            'user_id' => '3',
        ],
    ]);
    DB::table('clients')->insert([
        [
            'code' => 'JB01',
            'id' => '1',

        ],
        [
            'code' => 'MR05',
            'id' => '2',
        ],
        [
            'code' => 'SD01',
            'id' => '3',
        ],
    ]);
    DB::table('products')->insert([
        [
            'code' => 'OR/GR1BR3-GR10BR1',
            'description' => 'Orologio con 2 pietre di incastonature griffato	',
            'gold_weight' => 12,
            'type' => 'OR',
        ],
        [
            'code' => 'OR/GR1BR3-GR10BR1',
            'description' => 'Orologio con 2 pietre di incastonature griffato	',
            'gold_weight' => 12,
            'type' => 'OR',
        ],
        [
            'code' => 'AN/PP50RU11',
            'description' => 'Anello con 50 pietre rubino di incastonatura PP e di classe 11',
            'gold_weight' => 15,
            'type' => 'AN',
        ],
        [
            'code' => 'DI/PG200SM1-PP50RU11',
            'description' => 'Diadema con 200 pietre smeraldo di incastonatura PG e di classe 1',
            'gold_weight' => 20,
            'type' => 'DI',
        ],
        [
            'code' => 'OR/GC16RU4-PP50RU11-PA3TO3',
            'description' => 'Orologio con 16 pietre rubino di incastonatura GC e di classe 4',
            'gold_weight' => 30,
            'type' => 'OR',
        ],
        [
            'code' => 'AN/PR40PL3-GC93OP8-SG92RU1-GC91AM6',
            'description' => 'Anello 4 tipi di incastonature',
            'gold_weight' => 30,
            'type' => 'AN',
        ],
    ]);
    DB::table('stone_classes')->insert([
        [
            'code' => '1',
            'description' => 'Classe I',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '2',
            'description' => 'Classe II',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '3',
            'description' => 'Classe III',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '4',
            'description' => 'Classe IV',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '5',
            'description' => 'Classe V',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '6',
            'description' => 'Classe VI',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '7',
            'description' => 'Classe VII',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '8',
            'description' => 'Classe VIII',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '9',
            'description' => 'Classe IX',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '10',
            'description' => 'Classe X',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '11',
            'description' => 'Classe XI',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],
        [
            'code' => '12',
            'description' => 'Classe XII',
            'min_weight' => '0.005g',
            'max_weight' => '0.05g',
        ],




    ]);
    DB::table('stone_types')->insert([
        [
            'code' => 'AM',
            'description' => 'Ametista'
        ],
        [
            'code' => 'RU',
            'description' => 'Rubino'
        ],
        [
            'code' => 'SW',
            'description' => 'swarovski'
        ],
        [
            'code' => 'BR',
            'description' => 'brillante'
        ],
        [
            'code' => 'AQ',
            'description' => 'acquamarina'
        ],
        [
            'code' => 'BN',
            'description' => 'brillante nero'
        ],
        [
            'code' => 'BW',
            'description' => 'brillante brown'
        ],
        [
            'code' => 'CI',
            'description' => 'citrino'
        ],
        [
            'code' => 'GN',
            'description' => 'granato'
        ],
        [
            'code' => 'GS',
            'description' => 'garnet spessartite'
        ],
        [
            'code' => 'IO',
            'description' => 'iolite'
        ],
        [
            'code' => 'OP',
            'description' => 'opale'
        ],
        [
            'code' => 'PC',
            'description' => 'pietre di colore'
        ],
        [
            'code' => 'PE',
            'description' => 'peridoto'
        ],
        [
            'code' => 'PL',
            'description' => 'perla'
        ],
        [
            'code' => 'TA',
            'description' => 'tanzanite'
        ],
        [
            'code' => 'TO',
            'description' => 'topazio'
        ],
        [
            'code' => 'TR',
            'description' => 'tormalina'
        ],
        [
            'code' => 'TU',
            'description' => 'turchese'
        ],
        [
            'code' => 'ZA',
            'description' => 'zaffiro'
        ],
        [
            'code' => 'ZI',
            'description' => 'zircone'
        ],
        [
            'code' => 'SM',
            'description' => 'smeraldo'
        ]
    ]);
    DB::table('setting_types')->insert([
        [
            'code' => 'BA',
            'description' => 'battuto'
        ],
        [
            'code' => 'BG',
            'description' => 'battuto giro'
        ],
        [
            'code' => 'GC',
            'description' => 'griff corona'
        ],
        [
            'code' => 'GR',
            'description' => 'griff'
        ],
        [
            'code' => 'GT',
            'description' => 'grifftennis'
        ],
        [
            'code' => 'PA',
            'description' => 'pave'
        ],
        [
            'code' => 'PF',
            'description' => 'pave forato'
        ],
        [
            'code' => 'PI',
            'description' => 'pave intagliato'
        ],
        [
            'code' => 'PU',
            'description' => 'pulitura'
        ],
        [
            'code' => 'PP',
            'description' => 'pave pregiato'
        ],
        [
            'code' => 'PR',
            'description' => 'preintagliato'
        ],
        [
            'code' => 'PZ',
            'description' => 'pave con zirconi'
        ],
        [
            'code' => 'SG',
            'description' => 'sgriffato'
        ],
        [
            'code' => 'SQ',
            'description' => 'squarcio'
        ]
    ]);
    $pietra1 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 3,
        'prezzariouser' => 1,
        'stone_class_id' => 3,
        'stone_type_id' => 4,
        'setting_type_id' => 4,
    ]);
    $pietra2 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 10,
        'prezzariouser' => 7,
        'stone_class_id' => 1,
        'stone_type_id' => 4,
        'setting_type_id' => 4,
    ]);
    $pietra3 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 3,
        'prezzariouser' => 1,
        'stone_class_id' => 11,
        'stone_type_id' => 2,
        'setting_type_id' => 10,
    ]);
    $pietra4 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 3,
        'prezzariouser' => 1,
        'stone_class_id' => 1,
        'stone_type_id' => 22,
        'setting_type_id' => 7,
    ]);
    $pietra5 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 3,
        'prezzariouser' => 1,
        'stone_class_id' => 11,
        'stone_type_id' => 2,
        'setting_type_id' => 10,
    ]);
    $pietra6 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 6,
        'prezzariouser' => 3,
        'stone_class_id' => 4,
        'stone_type_id' => 2,
        'setting_type_id' => 3,
    ]);
    $pietra7 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 25,
        'prezzariouser' => 20,
        'stone_class_id' => 11,
        'stone_type_id' => 2,
        'setting_type_id' => 10,
    ]);
    $pietra8 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 99,
        'prezzariouser' => 80,
        'stone_class_id' => 3,
        'stone_type_id' => 17,
        'setting_type_id' => 6,
    ]);
    $pietra9 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 3,
        'prezzariouser' => 1,
        'stone_class_id' => 3,
        'stone_type_id' => 15,
        'setting_type_id' => 11,
    ]);
    $pietra10 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 3,
        'prezzariouser' => 1,
        'stone_class_id' => 8,
        'stone_type_id' => 12,
        'setting_type_id' => 3,
    ]);
    $pietra11 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 3,
        'prezzariouser' => 1,
        'stone_class_id' => 1,
        'stone_type_id' => 2,
        'setting_type_id' => 13,
    ]);
    $pietra12 = DB::table('stones')->insertGetId([
        'stone_weight' => 0.5,
        'client_cost' => 3,
        'prezzariouser' => 1,
        'stone_class_id' => 6,
        'stone_type_id' => 1,
        'setting_type_id' => 3,
    ]);

    DB::table('processes')->insert([
        [
            'stone_id' => $pietra1,
            'product_id' => 1,
            'quantity' => 1
        ],
        [
            'stone_id' => $pietra2,
            'product_id' => 1,
            'quantity' => 10
        ],
        [
            'stone_id' => $pietra3,
            'product_id' => 2,
            'quantity' => 50
        ],
        [
            'stone_id' => $pietra4,
            'product_id' => 3,
            'quantity' => 200
        ],
        [
            'stone_id' => $pietra5,
            'product_id' => 3,
            'quantity' => 50
        ],
        [
            'stone_id' => $pietra6,
            'product_id' => 4,
            'quantity' => 16
        ],
        [
            'stone_id' => $pietra7,
            'product_id' => 4,
            'quantity' => 50
        ],
        [
            'stone_id' => $pietra8,
            'product_id' => 4,
            'quantity' => 3
        ],
        [
            'stone_id' => $pietra9,
            'product_id' => 5,
            'quantity' => 40
        ],
        [
            'stone_id' => $pietra10,
            'product_id' => 5,
            'quantity' => 93
        ],
        [
            'stone_id' => $pietra11,
            'product_id' => 5,
            'quantity' => 92
        ],
        [
            'stone_id' => $pietra12,
            'product_id' => 5,
            'quantity' => 91
        ]


    ]);

    return redirect('/');
});
