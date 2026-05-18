@extends('layouts.customer')

@section('title', __('Coupon Shop'))

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Coupon Shop') }}</h1>
            <p class="mt-1 text-gray-500">{{ __('Exchange your reward points for exclusive coupons.') }}</p>
        </div>
        <div class="flex items-center gap-2 rounded-2xl bg-white px-5 py-3 shadow-sm border border-gray-100">
            <span class="text-sm font-medium text-gray-600">{{ __('Your Balance:') }}</span>
            <span class="text-lg font-bold text-amber-600" id="shop-points-display">{{ Auth::user()->reward_points ?? 0 }} {{ __('pts') }}</span>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2"></div>

    @if ($coupons->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
            <p class="text-lg font-semibold text-gray-900">{{ __('No coupons available') }}</p>
            <p class="mt-2 text-gray-500">{{ __('Check back later for exciting rewards.') }}</p>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($coupons as $coupon)
                <div class="flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-shadow hover:shadow-md">
                    <div class="h-32 bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center p-6 text-center">
                        <h3 class="text-xl font-bold text-white leading-tight drop-shadow-sm">{{ $coupon->title }}</h3>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <p class="text-sm text-gray-600 flex-1">{{ $coupon->description }}</p>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('Cost') }}</span>
                                <span class="text-lg font-bold text-amber-600">{{ $coupon->cost }} {{ __('pts') }}</span>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('Valid For') }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ $coupon->validity_days }} {{ __('Days') }}</span>
                            </div>
                        </div>

                        <button type="button" 
                            class="buy-coupon-btn mt-5 w-full rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-black focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            data-url="{{ route('customer.coupons.buy', $coupon) }}"
                            data-cost="{{ $coupon->cost }}">
                            {{ __('Buy Now') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $coupons->links() }}
        </div>
    @endif

    @push('scripts')
        <script>
            function showToast(message, type = 'success') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `px-4 py-3 rounded-xl shadow-lg border text-sm font-medium transition-all transform translate-y-0 opacity-100 flex items-center gap-2 ${
                    type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200'
                }`;
                toast.innerHTML = `
                    <svg class="w-5 h-5 ${type === 'success' ? 'text-emerald-500' : 'text-rose-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' 
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>'}
                    </svg>
                    ${message}
                `;
                container.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            document.querySelectorAll('.buy-coupon-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    if (this.disabled) return;
                    
                    const url = this.getAttribute('data-url');
                    const cost = parseInt(this.getAttribute('data-cost'));
                    const originalText = this.innerHTML;
                    
                    this.disabled = true;
                    this.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...`;

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await res.json();
                        
                        if (res.ok) {
                            showToast(data.message, 'success');
                            // Update points display everywhere
                            document.getElementById('shop-points-display').textContent = data.new_points + ' pts';
                            const navPoints = document.getElementById('nav-reward-points');
                            if (navPoints) navPoints.textContent = data.new_points + ' pts';
                        } else {
                            showToast(data.message || 'Something went wrong', 'error');
                        }
                    } catch (error) {
                        showToast('Failed to purchase coupon.', 'error');
                    } finally {
                        this.disabled = false;
                        this.innerHTML = originalText;
                    }
                });
            });
        </script>
    @endpush
@endsection
