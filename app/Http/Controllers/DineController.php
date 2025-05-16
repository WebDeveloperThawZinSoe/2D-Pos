<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReDine;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DineController extends Controller
{

      //index
      public function index(){
        return view("web.dine.index");
     }

    public function agents()
    {
        $parent_id = Auth::id();
        $clients = User::where('manager_id', $parent_id)->get();
        return view('web.dine.agents', compact('clients'));
    }

    public function storeAgent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:6',
            'commission' => 'nullable|numeric',
            'rate' => 'nullable|numeric',
            'max_limit' => 'nullable|numeric',
            'end_am' => 'nullable|date_format:H:i', // Validate as time format
            'end_pm' => 'nullable|date_format:H:i', // Validate as time format
        ]);

        $user = new User();
        $user->manager_id = Auth::id();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->plain_password = $request->password;
        $user->commission = $request->commission ?? 0;
        $user->rate = $request->rate ?? 80;
        $user->max_limit = $request->max_limit ?? 100000;
        $user->end_am = $request->end_am ?? '11:30';
        $user->end_pm = $request->end_pm ?? '15:50';
        $user->assignRole('user'); // Assuming you're using Spatie

        $user->save();

        return redirect()->route('dine.agents')->with('success', 'Agent created successfully.');
    }

    public function editAgent($id)
    {
        $agent = User::findOrFail($id);
        $parent_id = Auth::id();
        $clients = User::where('manager_id', $parent_id)->get();
        return view('web.dine.edit_agents', compact('agent',"clients"));
    }

    public function updateAgent(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->plain_password = $request->password;
        }

        $user->commission = $request->commission ?? 0;
        $user->rate = $request->rate ?? 80;
        $user->max_limit = $request->max_limit ?? 100000;
        $user->end_am = $request->end_am ?? '11:30';
        $user->end_pm = $request->end_pm ?? '15:50';

        $user->save();

        return redirect()->route('dine.agents')->with('success', 'Agent updated successfully.');
    }

    public function deleteAgent($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('dine.agents')->with('success', 'Agent deleted.');
    }


    public function redine(){
        $parent_id = Auth::id();
        $clients = ReDine::where('manager_id', $parent_id)->get();
        return view('web.dine.redine', compact('clients'));
    }

    
    public function storeRedine(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'commission' => 'nullable|numeric',
            'rate' => 'nullable|numeric',
            'max_limit' => 'nullable|numeric',
        ]);

        $user = new ReDine();
        $user->manager_id = Auth::id();
        $user->name = $request->name;
        $user->commission = $request->commission ?? 0;
        $user->rate = $request->rate ?? 80;
        $user->save();

        return redirect()->route('dine.redine')->with('success', 'Dine created successfully.');
    }

    public function editRedine($id)
    {
        $agent = ReDine::findOrFail($id);
        $parent_id = Auth::id();
        $clients = ReDine::where('manager_id', $parent_id)->get();
        return view('web.dine.edit_redine', compact('agent',"clients"));
    }

    public function updateRedine(Request $request, $id)
    {
        $user = ReDine::findOrFail($id);

        $request->validate([
            'name' => 'required',
        ]);

        $user->name = $request->name;

        $user->commission = $request->commission ?? 0;
        $user->rate = $request->rate ?? 80;
        $user->save();

        return redirect()->route('dine.redine')->with('success', 'Dine updated successfully.');
    }

    public function deleteRedine($id)
    {
        $user = ReDine::findOrFail($id);
        $user->delete();
        return redirect()->route('dine.redine')->with('success', 'Dine deleted.');
    }

    public function buy_sell_log(){
        $date = session('selected_date');
        $section = session('selected_section');
        $buyOrders = Order::where("manager_id",Auth::user()->id)->where("status",1)->where("date",$date)->where("section",$section)->where("buy_sell_type","buy")->get();
        $sellOrders = Order::where("manager_id",Auth::user()->id)->where("status",1)->where("date",$date)->where("section",$section)->where("buy_sell_type","sell")->get();
        return view("web.dine.buy_sell_log",compact("buyOrders","sellOrders"));
    }


    public function report($type)
    {
        $managerId = Auth::user()->id;

        // Calculate date range based on $type
        $date = session('selected_date') ?? now()->toDateString();

        $startDate = $endDate = Carbon::parse($date);

        switch ($type) {
            case 'daily':
                $startDate = $endDate = Carbon::parse($date);
                break;

            case 'weekly':
                $startDate = Carbon::parse($date)->startOfWeek(Carbon::MONDAY);
                $endDate = Carbon::parse($date)->endOfWeek(Carbon::SUNDAY);
                break;

            case 'monthly':
                $startDate = Carbon::parse($date)->startOfMonth();
                $endDate = Carbon::parse($date)->endOfMonth();
                break;

            case 'yearly':
                $startDate = Carbon::parse($date)->startOfYear();
                $endDate = Carbon::parse($date)->endOfYear();
                break;

            default:
                abort(400, 'Invalid report type.');
        }

        // ✅ Buy Orders
        $buyOrders = Order::select('dine_id', DB::raw('SUM(price) as total_amount'), 're_dines.name as dine_name')
            ->join('re_dines', 'orders.dine_id', '=', 're_dines.id')
            ->where('orders.manager_id', $managerId)
            ->where('orders.status', 1)
            ->whereBetween('orders.date', [$startDate, $endDate])
            ->where('orders.buy_sell_type', 'buy')
            ->groupBy('dine_id', 're_dines.name')
            ->get();

        foreach ($buyOrders as $order) {
            $dine = \App\Models\ReDine::find($order->dine_id);

            $winningDetails = OrderDetail::where('manager_id', $managerId)
                ->where('dine_id', $order->dine_id)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('buy_sell_type', 'buy')
                ->where('win_lose', 1)
                ->get();

            $rate = $dine->rate ?? 0;
            $totalNumber = $winningDetails->sum('price');
            $dethPauk = $totalNumber * $rate;

            $order->deth_pauk = $dethPauk;
            $order->dine = $dine;
        }

        // ✅ Sell Orders
        $sellOrders = Order::select('user_id', DB::raw('SUM(price) as total_amount'), 'users.name as user_name')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.manager_id', $managerId)
            ->where('orders.status', 1)
            ->whereBetween('orders.date', [$startDate, $endDate])
            ->where('orders.buy_sell_type', 'sell')
            ->groupBy('user_id', 'users.name')
            ->get();

        foreach ($sellOrders as $order) {
            $user = \App\Models\User::find($order->user_id);

            $winningDetails = OrderDetail::where('manager_id', $managerId)
                ->where('user_id', $order->user_id)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('buy_sell_type', 'sell')
                ->where('win_lose', 1)
                ->get();

            $rate = $user->rate ?? 0;
            $totalNumber = $winningDetails->sum('price');
            $dethPauk = $totalNumber * $rate;

            $order->deth_pauk = $dethPauk;
            $order->user = $user;
        }

        return view("web.dine.report.data", compact("buyOrders", "sellOrders", "type", "startDate", "endDate"));
    }






}