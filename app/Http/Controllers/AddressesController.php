<?php

namespace App\Http\Controllers;

use App\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressesController extends Controller
{
    // required fields
    protected $validation = [
        "zip_code" => "required",
        "street" => "required",
        "number" => "required",
        "neighborhood" => "required",
        "state" => "required",
        "contact_fk" => "required",
    ];

    public function getContactAddresses(int $contactId)
    {
        $addresses = Addresses::where('contact_fk', '=', $contactId)->get();

        return $addresses;
    }

    // register an address
    public function registerAddress(array $request)
    {
        $validation = Validator::make($request, $this->validation);

        if ($validation->fails()) {
            return ResponseController::returnApi(false, null, null, $validation->errors());
        }

        if (isset($request->address_id)) {
            $validatePhone = Addresses::find($request->address_id);
        }

        if (isset($validatePhone)) {
            $request->address_id = $validatePhone->address_id;
        }

        $address = $this->save($request);

        return $address;
    }

    /**
     * Creating or update resource.
     *
     * @return Address
     */
    public function save($data)
    {
        $address = null;

        if (!isset($data['address_id'])) {
            $address = new Addresses;
        } else {
            $address = Addresses::find($data['address_id']);
        }

        if ($address) {
            $address->zip_code = $data['zip_code'];
            $address->street = $data['street'];
            $address->number = $data['number'];
            $address->neighborhood = $data['neighborhood'];
            if (isset($data['complement'])) {
                $address->complement = $data['complement'];
            }
            $address->city = $data['city'];
            $address->state = $data['state'];
            $address->contact_fk = $data['contact_fk'];
            $address->save();

            return $address;
        } else {
            return false;
        }
    }
}
