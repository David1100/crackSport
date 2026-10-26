<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() { return response()->json(User::latest()->get()); }
    public function store(Request $r) {
        $data = $r->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required']);
        $data['password'] = bcrypt($data['password']);
        return response()->json(User::create($data), 201);
    }
    public function update(Request $r, $id) {
        $u = User::findOrFail($id);
        $data = $r->validate(['name'=>'sometimes','email'=>'sometimes|email|unique:users,email,'.$id,'password'=>'sometimes']);
        if (isset($data['password'])) $data['password']=bcrypt($data['password']);
        $u->update($data);
        return response()->json($u);
    }
    public function destroy($id) {
        User::findOrFail($id)->delete();
        return response()->json(['deleted'=>true]);
    }
}