<?php

namespace App\Data;

use App\Helpers\SALang;
use App\Helpers\SAUtility;
use Exception;
use Illuminate\Support\Facades\DB;

class SAEUserData {
    public $user_id = null;
    public $guid = null;
    public $full_name = null;
    public $email = null;
    public $phone = null;
    public $profile_image = null;
    public $is_email_verified = false;
    public $is_phone_verified = false;
    public $remember_token = null;
    public $is_cancel = false;

    public function __construct(
        $user_id = null,
        $guid = null,
        $full_name = null,
        $email = null,
        $phone = null,
        $profile_image = null,
        $is_email_verified = false,
        $is_phone_verified = false,
        $remember_token = null,
        $is_cancel = false
    ) {
        $this->user_id = $user_id;
        $this->guid = $guid;
        $this->full_name = $full_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->profile_image = $profile_image;
        $this->is_email_verified = $is_email_verified;
        $this->is_phone_verified = $is_phone_verified;
        $this->remember_token = $remember_token;
        $this->is_cancel = $is_cancel;
    }

    public function insert() {
        //create user id
        $this->user_id ??= SAUtility::generate_user_id();
        $this->remember_token ??= SAUtility::generate_remember_token($this->user_id);

        if(empty($this->full_name)) {
            throw new Exception(SALang::$please_enter_name);
        }

        if(empty($this->phone)) {
            throw new Exception(SALang::$please_enter_phone);
        }

        $user_data = $this->fetch();

        if(!empty($user_data)) {
            throw new Exception(SALang::$user_phone_already_exists);
        }

        $result = DB::table('eusers')->insert([
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'profile_image' => $this->profile_image,
            'is_email_verified' => $this->is_email_verified,
            'is_phone_verified' => $this->is_phone_verified,
            'is_cancel' => $this->is_cancel,
            'remember_token' => $this->remember_token
        ]);

        if(!$result) {
            throw new Exception(SALang::$unable_to_create_new_user);
        }

        $new_user = $this->fetch();

        return $new_user;
        
    }

    public function fetch() {
        $user_data = DB::table('eusers')->select()
            ->where('user_id', $this->user_id)
            ->orWhere('guid', $this->guid)
            ->orWhere('phone', $this->phone)
            ->first();

        return $user_data;
    }
}