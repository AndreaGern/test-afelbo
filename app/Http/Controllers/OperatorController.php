<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Distribution;
use App\Models\Operator;
use App\Models\Task;
use App\Models\User;
use App\Rules\SameUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OperatorController extends Controller
{
    use PasswordValidationRules;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $operators = Operator::all();
        return view('admin.operators.operators-main', compact('operators'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class),
            ],
            'code' => 'required',
            'workstation' => 'required',
        ])->validate();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'operator',
            'password' => Hash::make('Computer1!'),
        ]);

        $user->operator()->create([
            'code' => $request->code,
            'workstation' => $request->workstation,
        ]);
        return redirect(route('operator.index'))->with('message', 'Operatore creato con successo. La sua password provvisoria per il primo accesso sarà Computer1!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operator  $operator
     * @return \Illuminate\Http\Response
     */
    public function show(Operator $operator)
    {
        $tasks = $operator->tasks()->has('process')->orderByDesc('created_at')->paginate(10) ?? null;
        $page = 'show';
        $filter = 'all';
        return view('admin.operators.show', compact('operator', 'tasks', 'page', 'filter'));
    }

    public function filterDistributions(Operator $operator, Request $request)
    {
        $page = 'show';
        $filter = $request->filter;
        if ($filter == 'all') {
            $tasks = $operator->tasks()->has('process')->orderByDesc('created_at')->paginate(10) ?? null;
        } elseif ($filter == 'not-completed') {
            $tasks = $operator->tasks()->has('process')->has('distribution.order')->where('completed', false)->orderByDesc('created_at')->paginate(10) ?? null;
        }
        return view('admin.operators.show', compact('operator', 'tasks', 'filter', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operator  $operator
     * @return \Illuminate\Http\Response
     */
    public function edit(Operator $operator)
    {
        return view('admin.operators.edit', compact('operator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Operator  $operator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Operator $operator)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class)->ignore($operator->user),
            ],
            'code' => 'required',
            'workstation' => 'required',
        ])->validate();

        $operator->user->update(['name' => $request->name, 'email' => $request->email]);
        $operator->update([
            'code' => $request->code,
            'workstation' => $request->workstation,
        ]);
        return redirect()->back()->with('message', 'Modifica effettuata con successo.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operator  $operator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Operator $operator)
    {
        if ($operator->user->email === 'cristianafelbo@gmail.com') {
            return redirect()->back()->withErrors('Impossibile eliminare questo operatore');
        }
        if (count($operator->tasks) > 0) {
            return redirect()->back()->withErrors('Impossibile eliminare questo operatore perché ha delle lavorazioni a lui assegnate.');
        }

        $operator->user->delete();

        return redirect()->back()->with('message', 'Operatore eliminato con successo.');
    }

    public function resetPassword(Operator $operator)
    {
        $operator->user->update(['password' => Hash::make('Computer1!')]);
        $operator->update(['firstLogin' => true]);
        return redirect()->back()->with('message', 'Password operatore resettata a Computer1!');
    }

    public function firstLoginReset()
    {
        return view('admin.operators.first-login-reset');
    }

    public function saveNewPassword(Request $request)
    {
        Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'max:255',
                new SameUser()
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::find(Auth::id());
        $user->update(['password' => Hash::make($request->password)]);
        $user->operator->update(['firstLogin' => false]);
        return redirect(route('homepage'))->with('message', 'Nuova password impostata con successo.');
    }

    //? SEZIONE DA OPERATORE LOGGATO
    public function tasksIndex()
    {
        //? Prendo e mostro tutti i tasks delle lavorazioni per questo operatore
        $tasks = Auth::user()->operator ? Auth::user()->operator->tasks()->has('process')->orderByDesc('created_at')->paginate(10) : null;
        $page = 'all';
        return view('operator.work-show', compact('tasks', 'page'));
    }

    public function notCompletedTasksIndex()
    {
        //? Prendo e mostro tutti i tasks delle lavorazioni non completate per questo operatore
        $tasks = Auth::user()->operator ? Auth::user()->operator->tasks()->has('process')->has('distribution.order')->where('completed', false)->orderByDesc('created_at')->paginate(10) : null;
        $page = 'not-completed';
        return view('operator.work-show-not-completed', compact('tasks', 'page'));
    }

    public function changeDateTasksIndex(Request $request)
    {
        $minDate = $request->minDate;
        $maxDate = $request->maxDate;
        $parsedMaxDate = Carbon::parse($maxDate)->addDays(1);
        $page = $request->page;
        if ($minDate == null || $maxDate == null) {
            return redirect()->back()->withErrors('Inserisci entrambe le date');
        }
        if ($minDate > $maxDate) {
            return redirect()->back()->withErrors('La data di inizio deve essere precedente alla data di fine');
        }

        $tasks = Auth::user()->operator ?
            (
                $page == "all" ?
                Auth::user()->operator->tasks()->has('process')->whereBetween('created_at', [$minDate, $parsedMaxDate])->orderByDesc('created_at')->paginate(10)
                : Auth::user()->operator->tasks()->has('process')->has('distribution.order')->where('completed', false)->whereBetween('created_at', [$minDate, $parsedMaxDate])->orderByDesc('created_at')->paginate(10)
            )
            : null;
        $view = $page == "not-completed" ? 'work-show-not-completed' : 'work-show';
        return view("operator.$view", compact('tasks', 'minDate', 'maxDate', 'page'));
    }

    public function distributionShow(Distribution $distribution)
    {
        $distribution->load(
            'tasks.process.product.stones.stoneClass',
            'tasks.process.product.stones.settingType',
            'tasks.process.product.stones.stoneType'
        );
        return view('operator.work-show', compact('distribution'));
    }

    //? Aggiorna la quantità di lavorazione completata
    public function updateCompletedQuantity(Request $request, Task $task)
    {
        Validator::make($request->all(), [
            'completedQuantity' => ['required', 'numeric', 'min:0', 'max:' . $task->distribution->quantity],
        ])->validate();

        $task->update(['completedQuantity' => $request->completedQuantity]);

        //? Se la quantità lavorata del task è uguale alla quantità totale della lavorazione, aggiorno lo stato della lavorazione e del task a completato
        if ($task->completedQuantity == $task->distribution->quantity) {
            $task->update(['completed' => true]);
            $task->distribution->checkIsCompletedAndUpdate();
            $task->distribution->order->checkIsCompletedAndUpdate();
            $task->distribution->order->commission->updateStatus();
        } else {
            $task->update(['completed' => false]);
            $task->distribution->update(['completed' => false]);
            $task->distribution->order->update(['completed' => false]);
            $task->distribution->order->commission->updateStatus();
        }

        //? Devo aggiornare la contabilità perché adesso questa viene calcolata solo per i task completati
        Auth::user()->operator->updateAccountability();

        return redirect()->back()->with('message', 'Quantità lavorazione aggiornata con successo.');
    }

    /**
     * Undo the completion of the processing quantity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function undoCompletedQuantity(Request $request, Task $task)
    {
        Validator::make($request->all(), [
            'undoQuantity' => ['required', 'numeric', 'min:1', 'max:' . $task->completedQuantity],
        ])->validate();

        $undoQuantity = $request->undoQuantity;

        // Calculate the new completed quantity
        $newCompletedQuantity = $task->completedQuantity - $undoQuantity;

        $task->update(['completedQuantity' => $newCompletedQuantity]); // Update the completed quantity

        // Update the status of the processing, task, order, and commission
        if ($newCompletedQuantity < $task->distribution->quantity) {
            $task->update(['completed' => false]);
            $task->distribution->update(['completed' => false]);
            $task->distribution->order->update(['completed' => false]);
            $task->distribution->order->commission->updateStatus();
        }

        // Update the accountability because now this is calculated only for completed tasks
        Auth::user()->operator->updateAccountability();

        return redirect()->back()->with('message', 'Completamento lavorazione annullato con successo.');
    }

    public function movementsDetail()
    {
        $operator = Auth::user()->operator;
        if (!$operator) {
            return redirect()->back()->withErrors('Attenzione! Pagina non accessibile, contattare un\'amministratore.');
        }
        return view('operator.movements-info', compact('operator'));
    }
}
