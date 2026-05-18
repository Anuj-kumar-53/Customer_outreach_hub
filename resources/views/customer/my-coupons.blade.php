@extends('layouts.customer')

@section('title', __('My Coupons'))

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('My Coupons') }}</h1>
        <p class="mt-1 text-gray-500">{{ __('View and manage your purchased coupons here.') }}</p>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2"></div>

    @if ($userCoupons->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
            <p class="text-lg font-semibold text-gray-900">{{ __('You don\'t have any coupons yet') }}</p>
            <p class="mt-2 text-gray-500">{{ __('Head over to the Coupon Shop to purchase some.') }}</p>
            <a href="{{ route('customer.coupon-shop') }}" class="mt-6 inline-flex rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                {{ __('Go to Coupon Shop') }}
            </a>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($userCoupons as $uc)
                @php
                    $isExpired = $uc->status === 'expired';
                    $isUsed = $uc->status === 'used';
                    $isActive = $uc->status === 'active';
                @endphp
                
                <div class="user-coupon-card flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all {{ !$isActive ? 'opacity-60 grayscale-[0.5]' : 'hover:shadow-md' }}" data-id="{{ $uc->id }}">
                    <div class="h-28 {{ $isActive ? 'bg-gradient-to-br from-emerald-400 to-teal-500' : 'bg-gray-300' }} flex flex-col items-center justify-center p-4 text-center relative">
                        @if ($isUsed)
                            <span class="absolute top-3 right-3 bg-white/20 text-white text-[10px] uppercase font-bold px-2 py-0.5 rounded-full backdrop-blur-sm">{{ __('Used') }}</span>
                        @elseif ($isExpired)
                            <span class="absolute top-3 right-3 bg-red-500/80 text-white text-[10px] uppercase font-bold px-2 py-0.5 rounded-full backdrop-blur-sm">{{ __('Expired') }}</span>
                        @else
                            <span class="absolute top-3 right-3 bg-black/20 text-white text-[10px] uppercase font-bold px-2 py-0.5 rounded-full backdrop-blur-sm">{{ __('Active') }}</span>
                        @endif
                        <h3 class="text-lg font-bold text-white leading-tight drop-shadow-sm">{{ $uc->coupon->title }}</h3>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <p class="text-sm text-gray-600 flex-1">{{ $uc->coupon->description }}</p>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">{{ __('Purchased') }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ $uc->created_at->format('M j, Y') }}</span>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">{{ __('Expires') }}</span>
                                <span class="text-sm font-medium {{ $isExpired ? 'text-rose-600' : 'text-gray-700' }}">{{ $uc->expiry_date ? $uc->expiry_date->format('M j, Y') : 'Never' }}</span>
                            </div>
                        </div>

                        <button type="button" 
                            class="use-coupon-btn mt-5 w-full rounded-xl px-4 py-2.5 text-sm font-semibold shadow-sm transition-colors disabled:cursor-not-allowed
                                {{ $isActive ? 'bg-sky-600 text-white hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2' : 'bg-gray-100 text-gray-500 border border-gray-200' }}"
                            data-url="{{ route('customer.user-coupons.use', $uc) }}"
                            {{ !$isActive ? 'disabled' : '' }}>
                            @if ($isUsed)
                                {{ __('Already Used') }}
                            @elseif ($isExpired)
                                {{ __('Expired') }}
                            @else
                                {{ __('Use Coupon') }}
                            @endif
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $userCoupons->links() }}
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

            document.querySelectorAll('.use-coupon-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    if (this.disabled) return;
                    if (!confirm(@json(__('Are you sure you want to use this coupon now?')))) return;
                    
                    const url = this.getAttribute('data-url');
                    const card = this.closest('.user-coupon-card');
                    const header = card.querySelector('.h-28');
                    const badge = card.querySelector('.absolute.top-3.right-3');
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
                            // Generate random coupon code
                            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                            let code = 'COUP-';
                            for (let i = 0; i < 8; i++) {
                                code += chars.charAt(Math.floor(Math.random() * chars.length));
                            }
                            
                            // Copy to clipboard
                            navigator.clipboard.writeText(code).then(() => {
                                showToast(`Coupon code copied: ${code}`, 'success');
                            }).catch(() => {
                                showToast(`Coupon code: ${code}`, 'success');
                            });
                            
                            // Visual updates for Used state
                            card.classList.add('opacity-60', 'grayscale-[0.5]');
                            card.classList.remove('hover:shadow-md');
                            
                            header.classList.remove('bg-gradient-to-br', 'from-emerald-400', 'to-teal-500');
                            header.classList.add('bg-gray-300');
                            
                            badge.textContent = 'USED';
                            badge.classList.remove('bg-black/20');
                            badge.classList.add('bg-white/20');
                            
                            this.innerHTML = 'Already Used';
                            this.classList.remove('bg-sky-600', 'text-white', 'hover:bg-sky-700');
                            this.classList.add('bg-gray-100', 'text-gray-500', 'border', 'border-gray-200');
                        } else {
                            showToast(data.message || 'Something went wrong', 'error');
                            this.disabled = false;
                            this.innerHTML = originalText;
                        }
                    } catch (error) {
                        showToast('Failed to use coupon.', 'error');
                        this.disabled = false;
                        this.innerHTML = originalText;
                    }
                });
            });
        </script>
    @endpush
@endsection
