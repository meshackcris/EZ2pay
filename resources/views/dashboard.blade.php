<x-app-layout>
        <x-slot name="title">Admin Dashboard</x-slot>
    
            <!-- row -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-3 col-sm-6">
                                <div class="card overflow-hidden">
                                    <div class="card-header border-0">
                                        <div class="d-flex">
                                            <span class="mt-2 dash">
                                                <svg width="32" height="40" viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.812 34.64L3.2 39.6C2.594 40.054 1.784 40.128 1.106 39.788C0.428 39.45 0 38.758 0 38V2C0 0.896 0.896 0 2 0H30C31.104 0 32 0.896 32 2V38C32 38.758 31.572 39.45 30.894 39.788C30.216 40.128 29.406 40.054 28.8 39.6L22.188 34.64L17.414 39.414C16.634 40.196 15.366 40.196 14.586 39.414L9.812 34.64ZM28 34V4H4V34L8.8 30.4C9.596 29.802 10.71 29.882 11.414 30.586L16 35.172L20.586 30.586C21.29 29.882 22.404 29.802 23.2 30.4L28 34ZM14 20H18C19.104 20 20 19.104 20 18C20 16.896 19.104 16 18 16H14C12.896 16 12 16.896 12 18C12 19.104 12.896 20 14 20ZM10 12H22C23.104 12 24 11.104 24 10C24 8.896 23.104 8 22 8H10C8.896 8 8 8.896 8 10C8 11.104 8.896 12 10 12Z" fill="#717579"/>
                                                </svg>
                                            </span>
                                            <div class="invoices">
                                                <h4>${{ number_format($totalDeposits) }}</h4>
                                                <span>Total Amount Deposited</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                            
                                        <div id="totalInvoices"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card overflow-hidden">
                                    <div class="card-header border-0">
                                        <div class="d-flex">
                                            <span class="mt-1 dashs">
                                                <svg width="58" height="58" viewBox="0 0 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.812 48.64L11.2 53.6C10.594 54.054 9.78401 54.128 9.10602 53.788C8.42802 53.45 8.00002 52.758 8.00002 52V16C8.00002 14.896 8.89602 14 10 14H38C39.104 14 40 14.896 40 16V52C40 52.758 39.572 53.45 38.894 53.788C38.216 54.128 37.406 54.054 36.8 53.6L30.188 48.64L25.414 53.414C24.634 54.196 23.366 54.196 22.586 53.414L17.812 48.64ZM36 48V18H12V48L16.8 44.4C17.596 43.802 18.71 43.882 19.414 44.586L24 49.172L28.586 44.586C29.29 43.882 30.404 43.802 31.2 44.4L36 48ZM22 34H26C27.104 34 28 33.104 28 32C28 30.896 27.104 30 26 30H22C20.896 30 20 30.896 20 32C20 33.104 20.896 34 22 34ZM18 26H30C31.104 26 32 25.104 32 24C32 22.896 31.104 22 30 22H18C16.896 22 16 22.896 16 24C16 25.104 16.896 26 18 26Z" fill="#44814E"/>
                                                    <circle cx="43.5" cy="14.5" r="12.5" fill="#09BD3C" stroke="white" stroke-width="4"/>
                                                </svg>
                                            </span>
                                            <div class="invoices">
                                                <h4>${{ number_format($totalWithdrawal) }}</h4>
                                                <span>Total Withdrawal</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                            
                                        <div id="paidinvoices"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card overflow-hidden">
                                    <div class="card-header border-0">
                                        <div class="d-flex">
                                            <span class="mt-1 dashs">
                                                <svg width="58" height="58" viewBox="0 0 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.812 48.64L11.2 53.6C10.594 54.054 9.78401 54.128 9.10602 53.788C8.42802 53.45 8.00002 52.758 8.00002 52V16C8.00002 14.896 8.89602 14 10 14H38C39.104 14 40 14.896 40 16V52C40 52.758 39.572 53.45 38.894 53.788C38.216 54.128 37.406 54.054 36.8 53.6L30.188 48.64L25.414 53.414C24.634 54.196 23.366 54.196 22.586 53.414L17.812 48.64ZM36 48V18H12V48L16.8 44.4C17.596 43.802 18.71 43.882 19.414 44.586L24 49.172L28.586 44.586C29.29 43.882 30.404 43.802 31.2 44.4L36 48ZM22 34H26C27.104 34 28 33.104 28 32C28 30.896 27.104 30 26 30H22C20.896 30 20 30.896 20 32C20 33.104 20.896 34 22 34ZM18 26H30C31.104 26 32 25.104 32 24C32 22.896 31.104 22 30 22H18C16.896 22 16 22.896 16 24C16 25.104 16.896 26 18 26Z" fill="#44814E"/>
                                                    <circle cx="43.5" cy="14.5" r="12.5" fill="#FD5353" stroke="white" stroke-width="4"/>
                                                </svg>

                                            </span>
                                            <div class="invoices">
                                                <h4>{{ $pendingWithdrawals }}</h4>
                                                <span>Pending Withdrawals</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="unpaidinvoices"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card overflow-hidden">
                                    <div class="card-header border-0">
                                        <div class="d-flex">
                                            <span class="mt-1 dashs">
                                                <svg width="58" height="58" viewBox="0 0 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.812 48.64L11.2 53.6C10.594 54.054 9.784 54.128 9.106 53.788C8.428 53.45 8 52.758 8 52V16C8 14.896 8.896 14 10 14H38C39.104 14 40 14.896 40 16V52C40 52.758 39.572 53.45 38.894 53.788C38.216 54.128 37.406 54.054 36.8 53.6L30.188 48.64L25.414 53.414C24.634 54.196 23.366 54.196 22.586 53.414L17.812 48.64ZM36 48V18H12V48L16.8 44.4C17.596 43.802 18.71 43.882 19.414 44.586L24 49.172L28.586 44.586C29.29 43.882 30.404 43.802 31.2 44.4L36 48ZM22 34H26C27.104 34 28 33.104 28 32C28 30.896 27.104 30 26 30H22C20.896 30 20 30.896 20 32C20 33.104 20.896 34 22 34ZM18 26H30C31.104 26 32 25.104 32 24C32 22.896 31.104 22 30 22H18C16.896 22 16 22.896 16 24C16 25.104 16.896 26 18 26Z" fill="#44814E"/>
                                                    <circle cx="43.5" cy="14.5" r="12.5" fill="#FFAA2B" stroke="white" stroke-width="4"/>
                                                </svg>


                                            </span>
                                            <div class="invoices">
                                                <h4>{{ $pendingDeposits }}</h4>
                                                <span>Pending deposits</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="totalinvoicessent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-5 col-xxl-12 col-md-5">
                                                <h4 class="fs-20 text-black mb-4 font-w700">Data</h4>
                                                <div class="row">
                                                    <div class="d-flex col-xl-12 col-xxl-6  col-md-12 col-6 mb-4">
                                                        <svg class="me-3" width="14" height="54" viewBox="0 0 14 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="-6.10352e-05" width="14" height="54" rx="7" fill="#AC39D4"/>
                                                        </svg>
                                                        <div>
                                                            <p class="fs-14 mb-2">Total users</p>
                                                            <span class="fs-16 font-w600 text-light"><span class="text-black me-2 font-w700">{{ $totalUsers }}</span></span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex col-xl-12 col-xxl-6 col-md-12 col-6 mb-4">
                                                        <svg class="me-3" width="14" height="54" viewBox="0 0 14 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="-6.10352e-05" width="14" height="54" rx="7" fill="#40D4A8"/>
                                                        </svg>
                                                        <div>
                                                            <p class="fs-14 mb-2">Cancelled Withdrawals</p>
                                                            <span class="fs-16 font-w600 text-light"><span class="text-black me-2 font-w700">{{ $failedWithdrawals }}</span></span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex col-xl-12 col-xxl-6 col-md-12 col-6 mb-4">
                                                        <svg class="me-3" width="14" height="54" viewBox="0 0 14 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="-6.10352e-05" width="14" height="54" rx="7" fill="#1EB6E7"/>
                                                        </svg>
                                                        <div>
                                                            <p class="fs-14 mb-2">Cancelled Deposits</p>
                                                            <span class="fs-16 font-w600 text-light"><span class="text-black me-2 font-w700">{{ $failedDeposits }}</span></span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-xl-7  col-xxl-12 col-md-7">
                                                <div class="row">
                                                    <div class="col-sm-6 mb-4">
                                                        <div class="bg-gradient1 rounded text-center p-3">
                                                            <div class="d-inline-block position-relative donut-chart-sale mb-3">
                                                                <span class="donut1" data-peity='{ "fill": ["rgb(255, 255, 255)", "rgba(255, 255, 255, 0.2)"],   "innerRadius": 33, "radius": 10}'></span>
                                                                <small class="text-white pt-3">{{ $eftPercentage }}%</small>
                                                            </div>
                                                            <span class="fs-14 text-white d-block pt-2">EFT</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 mb-4">
                                                        <div class="bg-gradient2 rounded text-center p-3">
                                                            <div class="d-inline-block position-relative donut-chart-sale mb-3">
                                                                <span class="donut1" data-peity='{ "fill": ["rgb(255, 255, 255)", "rgba(255, 255, 255, 0.2)"],   "innerRadius": 33, "radius": 10}'></span>
                                                                <small class="text-white pt-3">{{ $meftPercentage }}%</small>
                                                            </div>
                                                            <span class="fs-14 text-white d-block pt-2">Manual EFT</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 mb-sm-0 mb-4">
                                                        <div class="rounded text-center p-3 bg-gradient3">
                                                            <div class="d-inline-block position-relative donut-chart-sale mb-3">
                                                                <span class="donut1" data-peity='{ "fill": ["rgb(255, 255, 255)", "rgba(234, 234, 234, 0.2)"],   "innerRadius": 33, "radius": 10}'></span>
                                                                <small class="text-white pt-3">{{ $mwirePercentage }}%</small>
                                                            </div>
                                                            <span class="fs-14 text-white d-block pt-2">Manual Wire</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 mb-sm-0 mb-4">
                                                        <div class="rounded text-center p-3 bg-gradient4">
                                                            <div class="d-inline-block position-relative donut-chart-sale mb-3">
                                                                <span class="donut1" data-peity='{ "fill": ["rgb(255, 255, 255)", "rgba(255, 255, 255, 0.2)"],   "innerRadius": 33, "radius": 10}'></span>
                                                                <small class="text-white pt-3">{{ $ccPercentage }}%</small>
                                                            </div>
                                                            <span class="fs-14 text-white d-block pt-2">Credit Card</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
            <div class="col-xl-6">
                
                <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="btn-group">
        <a href="?type=deposit&range={{ $range }}" class="btn {{ $type === 'deposit' ? 'btn-primary' : 'btn-outline-primary' }}">Deposit</a>
        <a href="?type=withdrawal&range={{ $range }}" class="btn {{ $type === 'withdrawal' ? 'btn-primary' : 'btn-outline-primary' }}">Withdrawals</a>
    </div>
    <div class="btn-group">
        <a href="?type={{ $type }}&range=24h" class="btn {{ $range === '24h' ? 'fw-bold text-primary' : '' }}">24Hr</a>
        <a href="?type={{ $type }}&range=7d" class="btn {{ $range === '7d' ? 'fw-bold text-primary' : '' }}">7 Days</a>
        <a href="?type={{ $type }}&range=1m" class="btn {{ $range === '1m' ? 'fw-bold text-primary' : '' }}">1 Month</a>
    </div>
</div>

<canvas id="mainChart" height="150"></canvas>



            </div>
                </div>
            </div>

<script>
    const ctx = document.getElementById('mainChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: '{{ ucfirst($type) }}',
                data: @json($data),
                borderColor: 'rgba(99, 102, 241, 1)',
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                tension: 0.4,
                fill: true,
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '$' + value.toLocaleString()
                    }
                }
            }
        }
    });
</script>

</x-app-layout>
