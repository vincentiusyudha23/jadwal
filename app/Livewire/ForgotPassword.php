<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;

class ForgotPassword extends Component
{
    public $input1;
    public $input2;
    public $input3;
    public $input4;

    
    #[Validate('required|email|exists:users,email')]
    public $email = '';

    public $step = 1;

    public $title_message;

    public $new_password;

    public $msg_error;

    public function mount()
    {   
        if(session()->has('session_new_password') && session()->has('__admin_send_OTP') && session()->has('__admin_OTP')){
            $this->title_message = 'Masukkan Kata Sandi Baru.';
            $this->step = 3;
        }
        if(session()->has('__admin_email') && session()->has('__admin_send_OTP') && session()->has('__admin_OTP') && !session()->has('session_new_password')){
            $this->title_message = 'Cek Kode OTP di email anda!';
            $this->step = 2;
            $this->email = session('__admin_email');    
        }

        if(!session()->has('__admin_send_OTP') && !session()->has('__admin_OTP') && !session()->has('session_new_password')){
            $this->title_message = 'Masukkan email admin untuk memulihkan kata sandi';
            $this->step = 1;
        }
    }

    public function addToastError($msg)
    {
        $this->msg_error = $msg;

        $this->js(<<<'JS'
            toastr.error($wire.msg_error)
        JS);
    }

    public function sendOTP()
    {
        $this->validate();

        $admin = User::where('email', $this->email)->first();
        
        try{
            if($admin->hasRole('admin')){
                $this->title_message = 'Cek Kode OTP di email anda!';
                $this->step = 2;
                
                $otp = generateOtp();
                $expiredAt = now()->addMinutes(5);

                session([
                    '__admin_send_OTP' => true,
                    '__admin_OTP' => $otp,
                    '__admin_email' => $admin->email,
                    '__admin_OTP_expires' => $expiredAt
                ]);
        
                sendEmail([
                    'to' => $this->email,
                    'subject' => 'Kode OTP pemulihan kata sandi.',
                    'view' => 'otp',
                    'viewData' => [
                        'otp' => $otp
                    ]
                ]);

                $this->dispatch('start-timer');
        
                return true;
            }else{
                $this->addToastError('Email Tidak Valid!');
                return;
            }
        }catch(\Exception $e){
            if(env('APP_ENV') == 'local'){
                dd($e->getMessage());
            }
        }
    }

    public function verification()
    {
        $otp = [$this->input1,$this->input2,$this->input3,$this->input4];
        
        $fix_otp = implode('', $otp);
        
        $otp_real = session('__admin_OTP');
       

        if($otp_real == $fix_otp){
            if(session()->has('__admin_OTP_expires') && !Carbon::now()->lessThan(Carbon::parse(session('__admin_OTP_expires')))){
                $this->addToastError('OTP sudah kadaluarsa!');
                return;
            }
            $this->step = 3;
            $this->title_message = 'Masukkan Kata Sandi Baru.';
            session([
                'session_new_password' => true
            ]);
            return true;
        }else{
            $this->addToastError('OTP tidak valid!');
            return;
        }
    }

    public function saveNewPassword()
    {
        $email = session('__admin_email');

        $admin = User::where('email', $email)->first();
        
        if (!$admin) {
            $this->addToastError('Email tidak ditemukan!');
            return;
        }

        if (empty($this->new_password)) {
            $this->addToastError('Password baru tidak boleh kosong!');
            return;
        }

        $admin->update([
            'password' => Hash::make($this->new_password)
        ]);

        session()->forget(['__admin_send_OTP', '__admin_OTP', 'session_new_password', '__admin_email', '__admin_OTP_expires']);

        return redirect()->route('first_page')->with('success', 'Password berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.forgot-password');
    }
}
