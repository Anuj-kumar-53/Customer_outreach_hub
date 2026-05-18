@extends('layouts.customer')

@section('title', __('Campaign Feed'))

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Campaign feed') }}</h1>
        <p class="mt-1 text-gray-500">{{ __('Browse active outreach campaigns from businesses. Newest first.') }}</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search + filters (GET query string; work together with pagination) --}}
    <div class="mb-8 rounded-2xl bg-white p-4 sm:p-6 shadow border border-gray-100">
        <form method="GET" action="{{ route('customer.campaign-feed') }}" class="space-y-4">
            <div class="grid gap-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-5">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                    <input type="text" name="search" id="search" value="{{ $search }}"
                        placeholder="{{ __('Search by title or category…') }}"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>
                <div class="lg:col-span-4">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Category') }}</label>
                    <select name="category" id="category"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="all" @selected($selectedCategory === 'all')>{{ __('All categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected($selectedCategory === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <p class="block text-sm font-medium text-gray-700 mb-1">{{ __('Sort') }}</p>
                    <p class="rounded-lg border border-dashed border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">
                        {{ __('Latest (newest first)') }}
                    </p>
                </div>
                <div class="lg:col-span-12 flex flex-wrap gap-2 pt-2">
                    <button type="submit"
                        class="inline-flex flex-1 min-w-[7rem] justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                        {{ __('Apply') }}
                    </button>
                    <a href="{{ route('customer.campaign-feed') }}"
                        class="inline-flex flex-1 min-w-[7rem] justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        {{ __('Reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    @if ($campaigns->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
            <p class="text-lg font-semibold text-gray-900">{{ __('No campaigns found') }}</p>
            <p class="mt-2 text-gray-500">{{ __('Try adjusting your search or category filter.') }}</p>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($campaigns as $campaign)
                <x-campaign-post :campaign="$campaign" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $campaigns->links() }}
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('click', async function (e) {
                // Handle copy referral URL
                const copyBtn = e.target.closest('.copy-referral-btn');
                if (copyBtn) {
                    const text = copyBtn.getAttribute('data-copy-url');
                    try {
                        await navigator.clipboard.writeText(text);
                        const originalText = copyBtn.innerHTML;
                        copyBtn.innerHTML = `<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Copied!`;
                        setTimeout(() => { copyBtn.innerHTML = originalText; }, 2000);
                    } catch (err) {
                        alert(@json(__('Could not copy — select the link manually.')));
                    }
                    return;
                }

                // Close dropdowns if clicking outside
                if (!e.target.closest('.post-menu-dropdown') && !e.target.closest('.post-menu-btn')) {
                    document.querySelectorAll('.post-menu-dropdown').forEach(d => d.classList.add('hidden'));
                }
                if (!e.target.closest('.share-dropdown') && !e.target.closest('.share-toggle-btn')) {
                    document.querySelectorAll('.share-dropdown').forEach(d => d.classList.add('hidden'));
                }

                // Handle post menu toggle
                const menuBtn = e.target.closest('.post-menu-btn');
                if (menuBtn) {
                    const dropdown = menuBtn.nextElementSibling;
                    document.querySelectorAll('.post-menu-dropdown').forEach(d => { if (d !== dropdown) d.classList.add('hidden') });
                    dropdown.classList.toggle('hidden');
                    return;
                }

                // Handle report button
                const reportBtn = e.target.closest('.report-btn');
                if (reportBtn) {
                    const post = reportBtn.closest('.campaign-post');
                    post.querySelector('.report-form-container').classList.remove('hidden');
                    reportBtn.closest('.post-menu-dropdown').classList.add('hidden');
                    return;
                }

                // Handle cancel report
                const cancelReportBtn = e.target.closest('.cancel-report-btn');
                if (cancelReportBtn) {
                    cancelReportBtn.closest('.report-form-container').classList.add('hidden');
                    return;
                }

                // Handle read more toggle
                const readMoreBtn = e.target.closest('.read-more-btn');
                if (readMoreBtn) {
                    const desc = readMoreBtn.previousElementSibling;
                    if (desc.classList.contains('line-clamp-2')) {
                        desc.classList.remove('line-clamp-2');
                        readMoreBtn.textContent = @json(__('Show less'));
                    } else {
                        desc.classList.add('line-clamp-2');
                        readMoreBtn.textContent = @json(__('...more'));
                    }
                    return;
                }

                // Handle share toggle
                const shareToggleBtn = e.target.closest('.share-toggle-btn');
                if (shareToggleBtn) {
                    const dropdown = shareToggleBtn.nextElementSibling;
                    document.querySelectorAll('.share-dropdown').forEach(d => { if (d !== dropdown) d.classList.add('hidden') });
                    dropdown.classList.toggle('hidden');
                    return;
                }

                // Handle comment section toggle
                const toggleCommentBtn = e.target.closest('.toggle-comment-btn');
                if (toggleCommentBtn) {
                    const post = toggleCommentBtn.closest('.campaign-post');
                    const commentsSection = post.querySelector('.comments-section');
                    commentsSection.classList.toggle('hidden');
                    if (!commentsSection.classList.contains('hidden')) {
                        commentsSection.querySelector('input[name="comment"]')?.focus();
                    }
                    return;
                }
            });

            // Handle AJAX form submissions for Like, Save, Comment, Delete Comment, Report
            document.addEventListener('submit', async function(e) {
                if (e.target.matches('.like-form, .save-form, .comment-form, .delete-comment-form, .report-form')) {
                    e.preventDefault();
                    const form = e.target;
                    const post = form.closest('.campaign-post');
                    
                    const isLike = form.matches('.like-form');
                    const isSave = form.matches('.save-form');
                    const isComment = form.matches('.comment-form');
                    const isDeleteComment = form.matches('.delete-comment-form');
                    const isReport = form.matches('.report-form');

                    let submitBtn = form.querySelector('button[type="submit"]');

                    if (isLike || isSave) {
                        const wasActive = form.getAttribute(isLike ? 'data-liked' : 'data-saved') === 'true';
                        const icon = submitBtn.querySelector('svg');
                        
                        // Capture formData BEFORE modifying the form DOM
                        const formData = new FormData(form);
                        const submitAction = form.action;
                        fetch(submitAction, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} })
                            .catch(err => console.error(err));

                        if (isLike) {
                            form.setAttribute('data-liked', wasActive ? 'false' : 'true');
                            const countSpan = post.querySelector('.like-count');
                            let count = parseInt(countSpan.getAttribute('data-count'));
                            
                            if (wasActive) {
                                form.querySelector('input[name="_method"]')?.remove();
                                submitBtn.classList.remove('text-sky-600', 'bg-gray-100');
                                submitBtn.classList.add('text-gray-500');
                                icon.classList.remove('fill-current');
                                icon.classList.add('fill-none', 'stroke-current', 'stroke-2');
                                count--;
                            } else {
                                form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="DELETE">');
                                submitBtn.classList.remove('text-gray-500');
                                submitBtn.classList.add('text-sky-600', 'bg-gray-100');
                                icon.classList.remove('fill-none', 'stroke-current', 'stroke-2');
                                icon.classList.add('fill-current');
                                count++;
                            }
                            countSpan.setAttribute('data-count', count);
                            countSpan.textContent = count + (count === 1 ? ' Like' : ' Likes');
                        } else if (isSave) {
                            form.setAttribute('data-saved', wasActive ? 'false' : 'true');
                            const countSpan = post.querySelector('.save-count-text');
                            let countMatch = countSpan.textContent.match(/\d+/);
                            let count = countMatch ? parseInt(countMatch[0]) : 0;

                            if (wasActive) {
                                form.querySelector('input[name="_method"]')?.remove();
                                submitBtn.classList.remove('text-amber-600', 'bg-gray-100');
                                submitBtn.classList.add('text-gray-500');
                                icon.classList.remove('fill-current');
                                icon.classList.add('fill-none', 'stroke-current', 'stroke-2');
                                count = Math.max(0, count - 1);
                            } else {
                                form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="DELETE">');
                                submitBtn.classList.remove('text-gray-500');
                                submitBtn.classList.add('text-amber-600', 'bg-gray-100');
                                icon.classList.remove('fill-none', 'stroke-current', 'stroke-2');
                                icon.classList.add('fill-current');
                                count++;
                            }
                            countSpan.textContent = count + (count === 1 ? ' Save' : ' Saves');
                        }
                    }
                    
                    if (isComment) {
                        const input = form.querySelector('input[name="comment"]');
                        const commentText = input.value;
                        if (!commentText.trim()) return;
                        
                        // Capture formData BEFORE clearing the input
                        const formData = new FormData(form);
                        const submitAction = form.action;
                        input.value = '';
                        
                        // Optimistic append
                        const list = post.querySelector('.comments-list');
                        const noComments = list.querySelector('.no-comments-text');
                        if (noComments) noComments.remove();
                        
                        const userName = @json(auth()->user()->name);
                        const initial = userName.charAt(0).toUpperCase();
                        
                        const html = `
                            <div class="comment-item flex gap-3 opacity-50 transition-opacity" data-pending="true">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center text-gray-500 font-semibold text-xs border border-gray-300 mt-0.5">
                                    ${initial}
                                </div>
                                <div class="flex-1">
                                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl rounded-tl-none px-3.5 py-2 inline-block">
                                        <p class="text-[13px] font-semibold text-gray-900">${userName}</p>
                                        <p class="text-sm text-gray-800 mt-0.5">${commentText.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</p>
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 px-1">
                                        <span class="text-[11px] font-medium text-gray-500">Just now</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        list.insertAdjacentHTML('afterbegin', html);
                        
                        // Update counter
                        const countSpan = post.querySelector('.comment-count-text');
                        let countMatch = countSpan.textContent.match(/\d+/);
                        let count = countMatch ? parseInt(countMatch[0]) + 1 : 1;
                        countSpan.textContent = count + (count === 1 ? ' Comment' : ' Comments');

                        try {
                            const res = await fetch(submitAction, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'} });
                            if (res.ok) {
                                list.querySelector('[data-pending="true"]')?.classList.remove('opacity-50');
                            }
                        } catch (err) {
                            console.error(err);
                        }
                    }

                    if (isDeleteComment) {
                        const item = form.closest('.comment-item');
                        item.style.display = 'none'; // hide immediately
                        
                        // Update counter
                        const countSpan = post.querySelector('.comment-count-text');
                        let countMatch = countSpan.textContent.match(/\d+/);
                        let count = countMatch ? Math.max(0, parseInt(countMatch[0]) - 1) : 0;
                        countSpan.textContent = count + (count === 1 ? ' Comment' : ' Comments');

                        fetch(form.action, { method: 'POST', body: new FormData(form), headers: {'X-Requested-With': 'XMLHttpRequest'} })
                            .catch(err => { item.style.display = ''; console.error(err); }); // revert on error
                    }

                    if (isReport) {
                        const container = form.closest('.report-form-container');
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Submitting...';
                        
                        fetch(form.action, { method: 'POST', body: new FormData(form), headers: {'X-Requested-With': 'XMLHttpRequest'} })
                            .then(() => {
                                container.innerHTML = `<div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-100 text-sm font-medium">Thank you. The issue has been reported.</div>`;
                                setTimeout(() => { container.classList.add('hidden'); }, 3000);
                            })
                            .catch(err => {
                                submitBtn.disabled = false;
                                submitBtn.textContent = 'Submit report';
                            });
                    }
                }
            });
        </script>
    @endpush
@endsection
