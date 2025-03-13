 <!-- ========== Left Sidebar Start ========== -->
 <div class="leftside-menu leftside-menu-detached">

     <div class="leftbar-user">
         <a href="javascript: void(0);">
             <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" height="42"
                 class="shadow-sm rounded-circle">
             <span class="leftbar-user-name">Dominic Keller</span>
         </a>
     </div>

     <!--- Sidemenu -->
     <ul class="side-nav">

         <li class="side-nav-item side-nav-title">Navigation</li>

         <li class="side-nav-item">
             <a wire:navigate href="{{ route('dashboard') }}" class="side-nav-link">
                 <i class="uil-home-alt"></i>
                 <span class="float-end bg-success badge">4</span>
                 <span> Tableau de bord </span>
             </a>
         </li>

         <li class="side-nav-item side-nav-title">Apps</li>

         @if (Auth::user()->type_user === 'admin')
         <li class="side-nav-item">
             <a wire:navigate href="{{ route('events') }}" class="side-nav-link">
                 <i class="uil-store"></i>
                 <span> Evernements </span>
             </a>
         </li>

         <li class="side-nav-item">
             <a wire:navigate href="{{ route('tickets') }}" class="side-nav-link">
                 <i class="uil-store"></i>
                 <span> Tickets </span>
             </a>
         </li>
         @endif


         <li class="side-nav-item">
             <a wire:navigate href="{{ route('scanners') }}" class="side-nav-link">
                 <i class="uil-store"></i>
                 <span> Scanner </span>
             </a>
         </li>

         @if (Auth::user()->type_user === 'admin')
         <li class="side-nav-item">
             <a wire:navigate href="{{ route('users') }}" class="side-nav-link">
                 <i class="uil-store"></i>
                 <span> Utilisateurs </span>
             </a>
         </li>
         @endif

     </ul>
 </div>
 <!-- Left Sidebar End -->
