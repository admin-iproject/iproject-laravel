<!-- Top Navigation Bar with Folder Tab -->
<nav class="top-nav-wrapper fixed top-0 left-0 right-0 z-50">
    
    <!-- Logo Folder Tab (grey with curved corner) -->
    <div class="logo-folder-tab">
    <a href="{{ route('dashboard') }}" class="flex items-center h-full">
        <img src="/storage/iPROJECTlogo.png" alt="iPROJECT - The Fine Art of Project Management" class="h-10">
    </a>
	</div>
    
    <!-- Blue Navigation Bar -->
    <div class="top-nav-blue">
        
        <div class="flex items-center space-x-1" style="margin-left: 270px;">
            
            <!-- Left Side: Menu Items (with left margin to clear folder tab) -->
            <div class="flex items-center space-x-1" >
                
                <!-- Dashboards Dropdown -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="top-nav-menu-item flex items-center space-x-1">
                        <span>DASHBOARD</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Main Dashboard</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Project Dashboard</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Ticket Dashboard</a>
                    </div>
                </div>
                
                <!-- My Dropdown -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="top-nav-menu-item flex items-center space-x-1">
                        <span>PROJECTS</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Projects</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Tickets</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Files</a>
                    </div>
                </div>
				
                <!-- Tickets Dropdown -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="top-nav-menu-item flex items-center space-x-1">
                        <span>TICKETS</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Calendar</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Team Calendar</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Company Calendar</a>
                    </div>
                </div>
				
				<!-- Files Dropdown -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="top-nav-menu-item flex items-center space-x-1">
                        <span>FILES</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Calendar</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Team Calendar</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Company Calendar</a>
                    </div>
                </div>
				
                <!-- Calendar Dropdown -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="top-nav-menu-item flex items-center space-x-1">
                        <span>CALENDAR</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Calendar</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Team Calendar</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Company Calendar</a>
                    </div>
                </div>
                <!-- Users Dropdown -->
            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <button @click="open = !open" class="top-nav-menu-item flex items-center space-x-1">
                    <span>USERS</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" 
                     x-transition
                     class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                    <a href="{{ route('users.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">All Users</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Active Users</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Inactive Users</a>
                    <a href="{{ route('users.create') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Add New User</a>
                </div>
            </div>
            </div>
            
            <!-- Right Side: Search & Icons -->
            <div class="flex items-center space-x-3">
                
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" 
                           placeholder="SMART SEARCH" 
                           class="w-60 bg-white border-1 border-white text-gray-900 placeholder-gray-400 rounded px-4 py-1 transition-all">
                </div>
                
                <!-- Quick Add -->
                <button class="top-nav-icon-btn" title="Quick Add">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
                
                <!-- Favorites -->
                <button class="top-nav-icon-btn" title="Favorites">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </button>
                
                <!-- Recent Items -->
                <button class="top-nav-icon-btn" title="Recent Items">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
                
                <!-- Help -->
                <button class="top-nav-icon-btn" title="Help">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
                
                <!-- User Menu -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 top-nav-icon-btn">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                        
                        {{-- Smart Company Link: Super Admin sees list, others see their company --}}
                        @if(Auth::user()->hasRole('super_admin'))
                            <a href="{{ route('companies.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Companies</a>
                        @else
                            <a href="{{ route('companies.show', Auth::user()->company_id) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Company</a>
                        @endif
                        
                        <hr class="my-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </div>
    
    <!-- Thin Grey Strip (matches page background) -->
    <div class="top-nav-grey-strip"></div>
    
</nav>