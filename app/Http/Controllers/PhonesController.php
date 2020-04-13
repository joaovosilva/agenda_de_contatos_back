<?php

namespace App\Http\Controllers;

use App\Phones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhonesController extends Controller
{
    // required fields
    protected $validation = [
        "type" => "required",
        "phone" => "required",
        "contact_fk" => "required"
    ];

    public function getContactPhones(int $contactId)
    {
        $phones = Phones::where('contact_fk', '=', $contactId)->get();

        return $phones;
    }

    // register a phone
    public function registerPhone(array $request)
    {
        $validation = Validator::make($request, $this->validation);

        if ($validation->fails()) {
            return ResponseController::returnApi(false, null, null, $validation->errors());
        }

        if (isset($request->phone_id)) {
            $validatePhone = Phones::find($request->phone_id);
        }

        if (isset($validatePhone)) {
            $request->phone_id = $validatePhone->phone_id;
        }

        $phone = $this->save($request);

        return $phone;
    }

    /**
     * Creating or update resource.
     *
     * @return Phone
     */
    public function save($data)
    {
        $phone = null;

        if (!isset($data['phone_id'])) {
            $phone = new Phones;
        } else {
            $phone = Phones::find($data['phone_id']);
        }

        if ($phone) {
            $phone->phone = $data['phone'];
            $phone->type = $data['type'];
            $phone->contact_fk = $data['contact_fk'];
            $phone->save();

            return $phone;
        } else {
            return false;
        }
    }

    /**
     * Delete phone
     *
     * @param $id
     * @return array
     */
    public function deletePhones($id)
    {
        $phones = Phones::where([
            ["contact_fk", "=", $id]
        ])->delete();

        if ($phones) {
            return $phones;
        } else {
            return ResponseController::returnApi(true, null, "Telefones não encontrado");
        }
    }
}
