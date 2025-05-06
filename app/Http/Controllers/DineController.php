<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
}
