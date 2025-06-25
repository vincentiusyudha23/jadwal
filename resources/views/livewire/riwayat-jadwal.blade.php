<div class="card-content" x-data="riwayatJadwal">
    <div class="mb-4 d-flex gap-2 position-relative">
        <div class="input-group">
            <input x-model="search" class="form-control" type="text" name="search" placeholder="Pencarian...">
        </div>
        <div class="d-flex gap-1">
            <button class="btn btn-sm btn-secondary opacity-75" x-on:click="openDatePicker">
                <i class="las la-calendar la-lg"></i>
            </button>
            <button x-show="isSelectDate" class="btn btn-sm btn-danger" x-on:click="clearSelectedDate">
                X
            </button>
        </div>
        <input x-ref="dateInput" type="text" class="position-absolute" style="opacity: 0; width: 1px; height: 1px; top: 100%; right: 0;">
    </div>
    <div wire:loading class="skeleton skeleton-line" style="--lines: 3; --c-w: 100%; --l-h: 30px;"></div>
    <div class="w-100 d-flex flex-column gap-3 list-riwayat">
        <template x-if="jadwalArr.length > 0">
            <template x-for="(item, index) in jadwalArr" :key="index">
                <a x-bind:href="item.route" x-text="item.text" class="text-gray-700 fw-bold w-100 bg-gray-300 p-2 rounded-2 list-riwayat-item"></a>
            </template>
        </template>
    </div>
    <template x-if="jadwalArr.length < 1">
        <div class="w-100 d-flex justify-content-center align-items-center" style="height: 100px;" wire:loading.class="d-none">
            <span>Tidak Ada Jadwal</span>
        </div>
    </template>
</div>

<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('riwayatJadwal', () => ({
            jadwal: @json($jadwals),
            jadwalArr: [],
            search: '',
            datePicker: null,
            selectedDate: {
                start: null,
                end: null
            },
            isSelectDate: false,
            get tanggal(){
                return _.filter(this.jadwal, (jadwal) => {
                    const formattedTanggal = new Date(jadwal.tanggal).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                    return formattedTanggal.includes(this.search);
                })
            },
            openDatePicker(){
                this.datePicker.open();
                // Trigger reposition setelah dibuka
                setTimeout(() => {
                    const calendar = document.querySelector('.flatpickr-calendar');
                    if (calendar) {
                        calendar.style.position = 'absolute';
                        calendar.style.top = '100%';
                        calendar.style.right = '0';
                        calendar.style.marginTop = '5px';
                    }
                }, 10);
            },
            clearSelectedDate(){
                this.selectedDate = null;
                this.datePicker.clear();
                this.jadwalArr = this.jadwal;
                this.isSelectDate = false;
            },
            init(){
                const $this = this;
                this.jadwalArr = this.jadwal
                this.$watch('tanggal', val => {
                    this.jadwalArr = val
                })

                this.datePicker = flatpickr(this.$refs.dateInput, {
                    mode: 'range',
                    dateFormat: 'd-m-Y',
                    allowInput: true,
                    position: "below", // Posisi calendar
                    appendTo: this.$refs.dateInput.parentElement, // Menempel pada parent
                    static: true,
                    locale: "id",
                    onChange: (selectedDates) => {
                        $this.isSelectDate = true;
                        let start = selectedDates[0];
                        let end = selectedDates[1];
                        
                        let jadwalData = $this.jadwal;

                        if(start && end){
                            $this.jadwalArr = jadwalData.filter(item => {
                                const itemDate = new Date(item.tanggal);
                                return itemDate >= start && (!end || itemDate <= end);
                            });
                        }
                    },
                    onOpen: () => {
                        const calendar = document.querySelector('.flatpickr-calendar');
                        calendar.style.position = 'absolute';
                        calendar.style.top = '100%';
                        calendar.style.right = '0';
                        calendar.style.marginTop = '5px';
                    }
                });
            }
        }))
    })
</script>
