<?php

namespace App\Http\Controllers;

use App\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller
{
    // required fields
    protected $validation = [
        "name" => "required",
        "user_fk" => "required",
        "phones" => "required",
        "addresses" => "required"
    ];

    public function getContactById(Request $request, $id)
    {
        if (!ResponseController::validationUser()) {
            return ResponseController::returnApi(false, null, "Autenticação Invállida");
        }

        $contact = Contacts::where('contact_id', '=', $id)->get()[0];

        if ($contact) {
            $phonesController = new PhonesController();
            $phones = $phonesController->getContactPhones($contact->contact_id);

            $addressesController = new AddressesController();
            $addresses = $addressesController->getContactAddresses($contact->contact_id);

            $contact->phones = $phones;
            $contact->addresses = $addresses;

            return ResponseController::returnApi(true, $contact);
        } else {
            return ResponseController::returnApi(true, null, "Contato não encontrado");
        }
    }

    public function getUserContacts(Request $request, $id)
    {
        if (!ResponseController::validationUser()) {
            return ResponseController::returnApi(false, null, "Autenticação Invállida");
        }

        $contacts = Contacts::where('user_fk', '=', $id)->get();

        if ($contacts) {
            foreach ($contacts as $contact) {
                $phonesController = new PhonesController();
                $phones = $phonesController->getContactPhones($contact->contact_id);

                $addressesController = new AddressesController();
                $addresses = $addressesController->getContactAddresses($contact->contact_id);

                $contact->phones = $phones;
                $contact->addresses = $addresses;
            }

            return ResponseController::returnApi(true, $contacts);
        } else {
            return ResponseController::returnApi(true, null, "Contato não encontrado");
        }
    }

    // register a contact
    public function registerContact(Request $request)
    {
        if (!ResponseController::validationUser()) {
            return ResponseController::returnApi(false, null, "Autenticação Invállida");
        }

        $validation = Validator::make($request->all(), $this->validation);

        if ($validation->fails()) {
            return ResponseController::returnApi(false, null, null, $validation->errors());
        }

        if (isset($request->contact_id)) {
            $validateContact = Contacts::find($request->contact_id);
        }        

        if (isset($validateContact)) {
            $request->contact_id = $validateContact->contact_id;
        }

        $contact = $this->save($request);

        $phoneArray = [];
        foreach ($request->phones as $phone) {
            $phonesController = new PhonesController();
            $phone['contact_fk'] = $contact->contact_id;
            $newPhone = $phonesController->registerPhone($phone);
            array_push($phoneArray, $newPhone);
        }

        $addressArray = [];
        foreach ($request->addresses as $address) {
            $addressesController = new AddressesController();
            $address['contact_fk'] = $contact->contact_id;
            $newAddress = $addressesController->registerAddress($address);
            array_push($addressArray,  $newAddress);
        }

        $contact->phones = $phoneArray;
        $contact->addresses = $addressArray;

        return ResponseController::returnApi(true, $contact);
    }

    /**
     * Creating or update resource.
     *
     * @return Contact
     */
    public function save($data)
    {
        $contact = null;

        if ($data->contact_id == null || $data->contact_id == "") {
            $contact = new Contacts;
        } else {
            $contact = Contacts::find($data->contact_id);
        }

        if ($contact) {
            $contact->name = $data->name;
            $contact->user_fk = $data->user_fk;
            $contact->company = $data->company;
            $contact->role = $data->role;
            $contact->save();

            return $contact;
        } else {
            return false;
        }
    }

    /**
     * Delete contact
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function deleteContact(Request $request, $id)
    {
        if (!ResponseController::validationUser()) {
            return ResponseController::returnApi(false, null, "Autenticação Invállida");
        }

        $contact = Contacts::find($id);
        if ($contact) {
            $phonesController = new PhonesController();
            $phonesController->deletePhones($id);

            $addressesController = new AddressesController();
            $addressesController->deleteAddresses($id);

            $contact->delete();

            return ResponseController::returnApi(true, null, "Contato excluido com sucesso");
        } else {
            return ResponseController::returnApi(true, null, "Contato não encontrado");
        }
    }
}
