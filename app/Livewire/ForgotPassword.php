<?php

namespace App\Livewire;

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

    public function mount()
    {   
        if(session()->has('session_new_password') && session()->has('__admin_send_OTP') && session()->has('__admin_OTP')){
            $this->title_message = 'Masukkan Kata Sandi Baru.';
            $this->step = 3;
        }
        if(session()->has('__admin_send_OTP') && session()->has('__admin_OTP') && !session()->has('session_new_password')){
            $this->title_message = 'Cek Kode OTP di email anda!';
            $this->step = 2;    
        }

        if(!session()->has('__admin_send_OTP') && !session()->has('__admin_OTP') && !session()->has('session_new_password')){
            $this->title_message = 'Masukkan email admin untuk memulihkan kata sandi';
            $this->step = 1;
        }
    }

    public function sendOTP()
    {
        $this->validate();

        $admin = User::where('email', $this->email)->first()->hasRole('admin');
        
        try{
            if($admin){
                $this->title_message = 'Cek Kode OTP di email anda!';
                $this->step = 2;

                $otp = generateOtp();
        
                session([
                    '__admin_send_OTP' => true,
                    '__admin_OTP' => $otp,
                    '__admin_email' => $this->email
                ]);
        
                sendEmail([
                    'to' => $this->email,
                    'subject' => 'Kode OTP pemulihan kata sandi.',
                    'view' => 'otp',
                    'viewData' => [
                        'otp' => $otp
                    ]
                ]);
        
                return true;
            }else{
                $this->addError('email', 'Bukan email admin!');
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
            $this->step = 3;
            $this->title_message = 'Masukkan Kata Sandi Baru.';
            session([
                'session_new_password' => true
            ]);
            return true;
        }else{
            $this->addError('verification', 'OTP tidak valid!');
            return;
        }
    }

    public function saveNewPassword()
    {
        $email = session('__admin_email');

        $admin = User::where('email', $email)->first();
        
        if (!$admin) {
            $this->addError('email', 'Email tidak ditemukan!');
            return;
        }

        if (empty($this->new_password)) {
            $this->addError('new_password', 'Password baru tidak boleh kosong!');
            return;
        }

        $admin->update([
            'password' => Hash::make($this->new_password)
        ]);

        session()->forget(['__admin_send_OTP', '__admin_OTP', 'session_new_password', '__admin_email']);

        return redirect()->route('first_page')->with('success', 'Password berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.forgot-password');
    }
}
