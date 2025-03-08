 <!-- ========== Left Sidebar Start ========== -->
 <div class="leftside-menu leftside-menu-detached">

     <div class="leftbar-user">
         <a href="javascript: void(0);">
             <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" height="42"
                 class="rounded-circle shadow-sm">
             <span class="leftbar-user-name">Dominic Keller</span>
         </a>
     </div>

     <!--- Sidemenu -->
     <ul class="side-nav">

         <li class="side-nav-item side-nav-title">Navigation</li>

         <li class="side-nav-item">
             <a wire:navigate href="{{ route('dashboard') }}" class="side-nav-link">
                 <i class="uil-home-alt"></i>
                 <span class="badge bg-success float-end">4</span>
                 <span> Tableau de bord </span>
             </a>
         </li>

         <li class="side-nav-item side-nav-title">Apps</li>

         <li class="side-nav-item">
             <a data-bs-toggle="collapse" href="#sidebarStudents" aria-expanded="false" aria-controls="sidebarEcommerce"
                 class="side-nav-link">
                 <i class="uil-store"></i>
                 <span> Etudiants </span>
                 <span class="menu-arrow"></span>
             </a>
             <div class="collapse" id="sidebarStudents">
                 <ul class="side-nav-second-level">
                     <li>
                         <a wire:navigate href="#">Listes des étudiants</a>
                     </li>
                     <li>
                         <a href="apps-ecommerce-products-details.html">Products Details</a>
                     </li>
                 </ul>
             </div>
         </li>

         <li class="side-nav-item">
             <a wire:navigate href="{{ route('dashboard') }}" class="side-nav-link">
                 <i class="uil-home-alt"></i>
                 <span class="badge bg-success float-end">4</span>
                 <span> Evernements </span>
             </a>
         </li>

     </ul>
 </div>
 <!-- Left Sidebar End -->
