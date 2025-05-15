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

    public function report_daily(){
        $date = session('selected_date');
        $section = session('selected_section');
        $buyOrders = Order::select('user_id', DB::raw('SUM(price) as total_amount'))
            ->where("manager_id", Auth::user()->id)
            ->where("status", 1)
            ->where("date", $date)
            ->where("section", $section)
            ->where("buy_sell_type", "buy")
            ->groupBy('user_id')
            ->get();

       $sellOrders = Order::select('dine_id', DB::raw('SUM(price) as total_amount'))
    ->where("manager_id", Auth::user()->id)
    ->where("status", 1)
    ->where("date", $date)
    ->where("section", $section)
    ->where("buy_sell_type", "sell")
    ->groupBy('dine_id')
    ->get();
        return view("web.dine.report.daily",compact("buyOrders","sellOrders"));
    }


}