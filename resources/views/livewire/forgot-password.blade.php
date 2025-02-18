<div class="w-100 h-100 d-flex justify-content-center align-items-center" x-data="forgotPassword">
    <div class="card bg-primary shadow text-white border-0 pt-3" style="width: 25rem;">
        <div class="d-flex justify-content-center flex-column align-items-center gap-2">
            <img src="{{ assets('img/logo-1.png') }}" alt="image" class="logo"/>
            <h6 class="fw-bold">PT. WIRA GRIYA</h6>
            <div class="text-center">
                <h5 class="m-0 p-0">Pemulihan Kata Sandi</h5>
                <small>{{ $title_message }}</small>
            </div>
        </div>
        @if ($step == 1)
            <div class="card-body" wire:key="email-form">
                <div class="mb-3">
                    <input type="email" wire:keydown.enter="sendOTP" id="email" name="email" wire:model="email" class="form-control" placeholder="Email...">
                    @error('email') <small>*{{ $message }}</small> @enderror
                </div>
                
                <button type="button" wire:click="sendOTP()" class="btn btn-light text-primary w-100">
                    <span wire:loading.remove class="fw-bold">
                        Kirim OTP
                    </span>
                    <span wire:loading>
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </span>
                </button>
            </div>
        @endif

        @if ($step == 2)
            <div class="card-body" wire:key="otp-form">
                <div class="mb-3">
                    <label class="form-label">OTP</label>
                    <div class="d-flex justify-content-between gap-2">
                        <input type="number" x-ref="otp1" x-on:paste="handlePasteOTP(event)" wire:keydown.enter="verification"  x-model="otpSteps.first" class="form-control Otp text-center" pattern="/d*" maxlength="1" name="Otp-1" id="Otp-1" wire:model="input1" autofocus="true" autocomplete="one-time-code">
                        <input type="number" x-ref="otp2" x-on:paste="handlePasteOTP(event)" wire:keydown.enter="verification"  x-model="otpSteps.second" class="form-control Otp text-center" pattern="/d*" maxlength="1" name="Otp-2" id="Otp-2" wire:model="input2">
                        <input type="number" x-ref="otp3" x-on:paste="handlePasteOTP(event)" wire:keydown.enter="verification"  x-model="otpSteps.third" class="form-control Otp text-center" pattern="/d*" maxlength="1" name="Otp-3" id="Otp-3" wire:model="input3">
                        <input type="number" x-ref="otp4" x-on:paste="handlePasteOTP(event)" wire:keydown.enter="verification"  x-model="otpSteps.fourth" class="form-control Otp text-center" pattern="/d*" maxlength="1" name="Otp-4" id="Otp-4" wire:model="input4">
                    </div>
                </div>
                <button type="button" wire:click="verification()" wire:loading.class="disabled" class="btn btn-light w-100 mb-3" id="btn-send-otp">
                    <span wire:loading.remove wire:target="verification" class="text-primary fw-bold">Verifikasi</span>
                    <span wire:loading wire:target="verification">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </span>
                </button>
                <div class="text-center">
                    <template x-if="!timerStarted">
                        <a href="javascript:void(0)" x-on:click="$wire.sendOTP()" wire:target="sendOTP" class="text-white">
                            Kirim Ulang OTP <span wire:loading wire:target="sendOTP">...</span>
                        </a>
                    </template>
                    <template x-if="timerStarted">
                        <span x-html="timerText"></span>
                    </template>
                </div>
            </div>
        @endif

        @if ($step == 3)
            <div class="card-body" wire:key="change-password-form">
                <div class="mb-3">
                    <label class="form-label" for="new_pass">New Password</label>
                    <input class="form-control" id="new_pass" name="new_password" wire:model="new_password" type="password">
                </div>
                <button type="button" wire:click="saveNewPassword()" class="btn btn-light w-100">
                    <span wire:loading.remove class="text-primary fw-bold">Simpan</span>
                     <span wire:loading>
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </span>
                </button>
            </div>
        @endif
    </div>
</div>

@script
    <script>
        Alpine.data('forgotPassword', () => ({
            otpSteps: {
                first: null,
                second: null,
                third: null,
                fourth: null
            },
            timerInterval : null,
            timer: 0,
            setTimerDefault : 60,
            get otp1Value() {
                return this.otpSteps.first;
            },
            get otp2Value() {
                return this.otpSteps.second;
            },
            get otp3Value() {
                return this.otpSteps.third;
            },
            get otp4Value() {
                return this.otpSteps.fourth;
            },
            get timerStarted(){
                return this.timerInterval !== null;
            },
            get timerText(){
                return `<span class="opacity-50">Kirim Ulang OTP </span>(${this.timer} s)`;
            },
            handlePasteOTP(event){
                const data = event.clipboardData.getData('Text');
                if (/^\d{4}$/.test(data)) {
                    this.otpSteps.first = data[0];
                    this.otpSteps.second = data[1];
                    this.otpSteps.third = data[2];
                    this.otpSteps.fourth = data[3];
                    this.$nextTick(() => this.$refs.otp4.focus());
                }
                event.preventDefault();
            },
            startTimer(){
                clearInterval(this.timerInterval)
                this.timerInterval = null
                
                if(localStorage.getItem('remainingTime')){
                    this.timer = parseInt(localStorage.getItem('remainingTime'))
                    console.log(this.timer);
                }else{
                    this.timer = this.setTimerDefault
                    localStorage.setItem('remainingTime', this.timer)
                }
                
                this.timerInterval = setInterval(() => {
                    if(this.timer <= 0){
                        clearInterval(this.timerInterval)
                        this.timerInterval = null
                        localStorage.removeItem('remainingTime');
                    }else{
                        this.timer--;
                        localStorage.setItem('remainingTime', this.timer)
                    }
                }, 1000);
            },
            async init(){

                let $this = this

                $wire.on('start-timer', () => {
                    this.startTimer();
                })

                if(localStorage.getItem('remainingTime')){
                    this.startTimer();
                }

                this.$watch('otp1Value', (val) => {
                    if(val)
                        $this.$refs.otp2?.focus()
                    else
                        $this.otpSteps.first = null
                })

                this.$watch('otp2Value', (val) => {
                    if(val)
                        $this.$refs.otp3?.focus()
                    else
                        $this.$refs.otp1?.focus()
                })

                this.$watch('otp3Value', (val) => {
                    if(val)
                        $this.$refs.otp4?.focus()
                    else
                        $this.$refs.otp2?.focus()
                })

                this.$watch('otp4Value', (val) => {
                    if(val) {
                        $this.$refs.otp4?.blur()
                        if($this.otpFilled)
                            this.checkOTP()
                    } else {
                        $this.$refs.otp3?.focus()
                    }
                })
            }
        }));
    </script>
@endscript

