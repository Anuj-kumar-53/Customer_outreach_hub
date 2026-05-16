<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Outreach Hub</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-200 selection:text-blue-900">

    <!-- 1. NAVBAR -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-500/30">
                        O
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">Outreach<span class="text-blue-600">Hub</span></span>
                </div>

             
                <div class="hidden md:flex space-x-8">
                  
                </div>

              
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:block text-slate-600 hover:text-blue-600 font-medium transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-5 py-2.5 rounded-full font-medium transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    Register Now
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. HERO SECTION -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] opacity-30 bg-gradient-to-b from-blue-400 to-purple-400 blur-[100px] rounded-full mix-blend-multiply"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-medium text-sm mb-6 border border-blue-200 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                The new standard for customer growth
            </div>
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight mb-8 max-w-4xl mx-auto leading-tight">
                Boost Your Business Reach Through <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Smart Referrals</span>
            </h1>
            <p class="text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Connect businesses and customers through social sharing and peer-to-peer referrals. Grow your audience organically with rewards they'll love.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-full font-medium transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Get Started
                    </a>
                @else
                    <a href="#how-it-works" class="w-full sm:w-auto px-8 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-full font-medium transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Get Started
                    </a>
                @endif
                <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-slate-50 text-slate-900 rounded-full font-medium transition-all shadow-sm border border-slate-200 hover:border-slate-300">
                    Explore Features
                </a>
            </div>
        </div>
    </section>

    <!-- 3. ABOUT SECTION -->
    <section id="about" class="py-20 bg-white relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-slate-900 mb-6">About the Platform</h2>
            <p class="text-xl text-slate-600 leading-relaxed max-w-3xl mx-auto">
                "This platform helps businesses promote offers and campaigns while allowing customers to share them with friends and earn rewards through referrals."
            </p>
        </div>
    </section>

    <!-- 4. FOR BUSINESSES & 5. FOR CUSTOMERS -->
    <section class="py-24 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16">
                
                <!-- For Businesses -->
                <div>
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">For Businesses</h2>
                        <p class="text-slate-600 text-lg">Empower your brand with organic growth tools.</p>
                    </div>
                    <div class="space-y-4">
                        <!-- Card 1 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-blue-100 p-3 rounded-xl text-blue-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Create Campaigns Easily</h3>
                                <p class="text-slate-600">Launch targeted referral campaigns in minutes with our intuitive builder.</p>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-purple-100 p-3 rounded-xl text-purple-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Reach More Customers Organically</h3>
                                <p class="text-slate-600">Leverage your existing customer base to find new high-quality leads.</p>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-green-100 p-3 rounded-xl text-green-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Track Campaign Performance</h3>
                                <p class="text-slate-600">Monitor engagement, shares, and conversions in real-time.</p>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-yellow-100 p-3 rounded-xl text-yellow-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Get Real-Time Analytics</h3>
                                <p class="text-slate-600">Access deep insights to optimize and improve your strategies continuously.</p>
                            </div>
                        </div>
                        <!-- Card 5 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-indigo-100 p-3 rounded-xl text-indigo-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Increase Sales through Referrals</h3>
                                <p class="text-slate-600">Drive revenue with high-converting peer-to-peer recommendations.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- For Customers -->
                <div>
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">For Customers</h2>
                        <p class="text-slate-600 text-lg">Discover, share, and get rewarded instantly.</p>
                    </div>
                    <div class="space-y-4">
                        <!-- Card 1 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-pink-100 p-3 rounded-xl text-pink-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Discover New Offers</h3>
                                <p class="text-slate-600">Find exclusive deals and campaigns from your favorite brands.</p>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-blue-100 p-3 rounded-xl text-blue-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Share with Friends</h3>
                                <p class="text-slate-600">Easily share exciting campaigns across your social networks.</p>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-emerald-100 p-3 rounded-xl text-emerald-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Earn Reward Points</h3>
                                <p class="text-slate-600">Collect points for successful referrals and redeem them for rewards.</p>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-purple-100 p-3 rounded-xl text-purple-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Track Your Referrals</h3>
                                <p class="text-slate-600">See exactly who signed up using your link and track your rewards progress.</p>
                            </div>
                        </div>
                        <!-- Card 5 -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1 flex items-start gap-4">
                            <div class="bg-orange-100 p-3 rounded-xl text-orange-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">Engage with Businesses</h3>
                                <p class="text-slate-600">Interact directly with brands through likes, comments, and shares.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 6. HOW IT WORKS -->
    <section id="how-it-works" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">How It Works</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">A seamless flow from campaign creation to rewarding successful referrals.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-8 relative">
                <!-- Connecting Line (Desktop only) -->
                <div class="hidden md:block absolute top-[2rem] left-[10%] right-[10%] h-[2px] bg-slate-200 z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-xl font-bold mb-4 shadow-lg shadow-blue-200">1</div>
                    <h3 class="font-semibold text-slate-900 mb-2">Create</h3>
                    <p class="text-sm text-slate-600">Business creates a campaign</p>
                </div>
                <!-- Step 2 -->
                <div class="relative z-10 text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-white text-blue-600 border-2 border-blue-600 flex items-center justify-center text-xl font-bold mb-4">2</div>
                    <h3 class="font-semibold text-slate-900 mb-2">Share</h3>
                    <p class="text-sm text-slate-600">Customers view and share it</p>
                </div>
                <!-- Step 3 -->
                <div class="relative z-10 text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-xl font-bold mb-4 shadow-lg shadow-blue-200">3</div>
                    <h3 class="font-semibold text-slate-900 mb-2">Join</h3>
                    <p class="text-sm text-slate-600">New users join via referral</p>
                </div>
                <!-- Step 4 -->
                <div class="relative z-10 text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-white text-blue-600 border-2 border-blue-600 flex items-center justify-center text-xl font-bold mb-4">4</div>
                    <h3 class="font-semibold text-slate-900 mb-2">Reward</h3>
                    <p class="text-sm text-slate-600">Rewards are generated</p>
                </div>
                <!-- Step 5 -->
                <div class="relative z-10 text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-xl font-bold mb-4 shadow-lg shadow-blue-200">5</div>
                    <h3 class="font-semibold text-slate-900 mb-2">Track</h3>
                    <p class="text-sm text-slate-600">Businesses track performance</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. FEATURES SECTION -->
    <section id="features" class="py-24 bg-slate-900 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- decorative blur -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-blue-600/20 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="text-center mb-16 relative z-10">
                <h2 class="text-3xl font-bold mb-4">Powerful Features</h2>
                <p class="text-slate-400 text-lg max-w-2xl mx-auto">Everything you need to build, launch, and scale your referral network.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative z-10">
                <!-- Feature 1 -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 p-8 rounded-2xl hover:bg-slate-800 transition-colors">
                    <div class="w-12 h-12 bg-blue-500/20 text-blue-400 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Campaign Management</h3>
                    <p class="text-slate-400">Design and launch highly customized referral campaigns with unique rules and tracking.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 p-8 rounded-2xl hover:bg-slate-800 transition-colors">
                    <div class="w-12 h-12 bg-purple-500/20 text-purple-400 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Referral System</h3>
                    <p class="text-slate-400">Robust tracking engine to accurately attribute every new sign-up and sale to the right referrer.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 p-8 rounded-2xl hover:bg-slate-800 transition-colors">
                    <div class="w-12 h-12 bg-yellow-500/20 text-yellow-400 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Reward Points</h3>
                    <p class="text-slate-400">Automated points distribution system to keep your customers engaged and motivated.</p>
                </div>
                <!-- Feature 4 -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 p-8 rounded-2xl hover:bg-slate-800 transition-colors">
                    <div class="w-12 h-12 bg-green-500/20 text-green-400 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Analytics Dashboard</h3>
                    <p class="text-slate-400">Detailed insights and charts to measure campaign ROI and top-performing advocates.</p>
                </div>
                <!-- Feature 5 -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 p-8 rounded-2xl hover:bg-slate-800 transition-colors md:col-span-2">
                    <div class="w-12 h-12 bg-pink-500/20 text-pink-400 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Social Interaction</h3>
                    <p class="text-slate-400 max-w-xl">Foster a community around your brand. Allow users to like, comment, and engage with businesses directly through interactive campaign pages.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. CALL TO ACTION -->
    <section id="contact" class="py-24 bg-gradient-to-br from-blue-600 to-purple-700 text-center relative overflow-hidden">
        <!-- Abstract shapes -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Join the platform and start growing today</h2>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                Ready to expand your reach? Build your first campaign or discover amazing deals.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-blue-600 hover:bg-slate-50 rounded-full font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Register Now
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-700/50 hover:bg-blue-700/70 text-white rounded-full font-bold transition-all border border-blue-500/50 backdrop-blur-sm">
                        Login
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 border-t border-slate-800 py-12 text-center text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center gap-2 mb-6 text-white">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center font-bold shadow-md">
                    O
                </div>
                <span class="font-bold text-lg">Outreach<span class="text-blue-500">Hub</span></span>
            </div>
            <p>&copy; {{ date('Y') }} Customer Outreach Hub. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
