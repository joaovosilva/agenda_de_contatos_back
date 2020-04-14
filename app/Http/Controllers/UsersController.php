<?php

namespace App\Http\Controllers;

use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    // required fields
    protected $validation = [
        "email" => "required",
        "password" => "required",
        "name" => "required"
    ];

    public function getUserById($id) {
        $user = Users::where('user_id', '=', $id)->get()[0];

        if ($user) {
            return $user;
        } else {
            return ResponseController::returnApi(true, null, "Usuário não encontrado");
        }
    }

    // register an user
    public function registerUser(Request $request)
    {
        $validation = Validator::make($request->all(), $this->validation);

        if ($validation->fails()) {
            return ResponseController::returnApi(false, null, null, $validation->errors());
        }

        $validateEmails = Users::where([
            ["email", "=", $request->email]
        ])->get();

        if (count($validateEmails) > 0) {
            return ResponseController::returnApi(false, null, "E-mail já cadastrado");
        }

        $user = $this->save($request);

        return ResponseController::returnApi(true, $user);
    }

    // create or update an user
    public function save($data)
    {
        $user = null;

        if ($data->id == null || $data->id == "") {
            $user = new Users;
        } else {
            $user = Users::find($data->id);
        }

        if ($user) {
            $user->name = $data->name;
            $user->email = $data->email;
            $user->password = bcrypt($data->password);
            $user->save();

            return $user;
        } else {
            return false;
        }
    }

    // retrieve all users
    public function getAllUsers()
    {
        if (!ResponseController::validationUser()) {
            return ResponseController::returnApi(false, null, "Autenticação Invállida");
        }

        $users = Users::all();

        return ResponseController::returnApi(true, $users);
    }

    // public function index(Request $request) {
    //     if ($request) {
    //         $search = trim($request->get('contactSearch'));
    //         $contacts = DB::table('tb_contacts')->
    //             where('name', 'like', '%'.$search.'%');
    //     }
    // }
}
