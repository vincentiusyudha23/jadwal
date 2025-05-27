<div class="card-content" x-data="riwayatJadwal">
    <div class="mb-4">
        <div class="input-group">
            <input x-model="search" class="form-control" type="text" name="search" placeholder="Pencarian...">
        </div>
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
            init(){
                this.jadwalArr = this.jadwal
                this.$watch('tanggal', val => {
                    
                    this.jadwalArr = val
                })
            }
        }))
    })
</script>
