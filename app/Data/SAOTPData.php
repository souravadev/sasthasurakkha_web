<?php

namespace App\Data;

use App\Helpers\SALang;
use Exception;
use App\Data\SAEUserData;
use App\Helpers\SAUtility;
use Illuminate\Support\Facades\DB;

class SAOTPData {
    public $action_id = null;
    public $user_id = null; 
    public $purpose_id = null;
    public $otp = null;
    public $phone = null;
    public $email = null;
    public $expiry_at = null;


    public function __construct(
        $action_id = null,
        $user_id = null,
        $purpose_id = null,
        $otp = null,
    ) {
        $this->action_id = $action_id;
        $this->user_id = $user_id;
        $this->otp = $otp;
        $this->purpose_id = $purpose_id;
    }

    public function trigger($is_resend = false) {
        if(empty($this->user_id)) {
            throw new Exception(SALang::$invalid_user_id);
        }

        if(!$is_resend && empty($this->purpose_id)) {
            throw new Exception(SALang::$invalid_purpose_id);
        }

        $user_data = new SAEUserData(
            $this->user_id
        ); 

        $user = $user_data->fetch();

        if(empty($user)) {
            throw new Exception(SALang::$invalid_user);
        }

        if($is_resend) {
            $otp_pre_data = SAOTPData::fetch_by_action_id();

            if(empty($otp_pre_data)) {
                throw new Exception(SALang::$invalid_action_id);
            }

            $this->action_id = $otp_pre_data->id;
            $this->purpose_id = $otp_pre_data->purpose_id;
        }

        //generate OTP
        $this->user_id = $user->id;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->action_id ??= SAUtility::generate_action_id();
        $this->otp = SAUtility::generate_otp();
        $this->expiry_at = SAUtility::get_otp_expiry_time();

        if($is_resend) {
            SAOTPData::update();
        } else {
            SAOTPData::insert();
        }

        if($this->purpose_id == '4') {
            SAOTPData::send_login_otp();
        }
    }

    public function insert() {
        DB::table('otps')->insert([
            'id' => $this->action_id,
            'purpose_id' => $this->purpose_id,
            'user_id' => $this->user_id,
            'phone' => $this->phone,
            'email' => $this->email,
            'otp' => $this->otp,
            'expiry_at' => $this->expiry_at,
        ]);
    }

    public function update($is_verified = false, $is_used = false) {
        DB::table('otps')
        ->where('id', $this->action_id)
        ->update([
            'id' => $this->action_id,
            'purpose_id' => $this->purpose_id,
            'user_id' => $this->user_id,
            'phone' => $this->phone,
            'email' => $this->email,
            'otp' => $this->otp,
            'expiry_at' => $this->expiry_at,
            'is_verified' => $is_verified,
            'is_used' => $is_used
        ]);
    }

    public function fetch_by_action_id() {
        $otp_fq = DB::table('otps')
            ->select()
            ->where('id',$this->action_id)
            ->first();

        return $otp_fq;
    }

    public function verify() {
        if(empty($this->user_id)) {
            throw new Exception(SALang::$invalid_user_id);
        }

        if(empty($this->otp)) {
            throw new Exception(SALang::$invalid_otp);
        }

        if(empty($this->action_id)) {
            throw new Exception(SALang::$invalid_action_id);
        }

        if(empty($this->purpose_id)) {
            throw new Exception(SALang::$invalid_purpose_id);
        }

        $data = DB::table('otps')
            ->select()
            ->where('id', $this->action_id)
            ->where('user_id', $this->user_id)
            ->where('otp', $this->otp)
            ->where('purpose_id', $this->purpose_id)
            ->where('is_verified', false)
            ->first();

        if(empty($data)) {
            throw new Exception(SALang::$invalid_otp);
        }

        if($data->expiry_at < SAUtility::get_current_timestamp()) {
            throw new Exception(SALang::$otp_expired);
        }

        DB::table('otps')
        ->where('id', $data->id)
        ->update([
            'is_verified' => true,
            'is_used' => true
        ]);

        return $data;
    }



    public function send_login_otp() {
    
        if(empty($this->otp)) {
            throw new Exception(SALang::$invalid_otp);
        }

        if(empty($this->phone) && empty($this->email)) {
            throw new Exception(SALang::$invalid_phone_email);
        }

        if(!empty($this->phone)) {
            //send
        }

        if(!empty($this->email)) {
            //send
        }
    }
}