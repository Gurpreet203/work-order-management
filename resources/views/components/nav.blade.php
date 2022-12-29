<nav class="nav">
    
    <div class="btn-group">
        <button class="btn-sm dropdown-toggle icon" id="nav-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="margin-left:0;">
            {{ Auth::user()->name }}
        </button>
        <ul class="dropdown-menu">
            <li>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <div class="drop-items-icon">
                    <i class="bi bi-box-arrow-left"></i>
                    <input type="submit" class="drop-items" value="Logout"> 
                </div>
                    
            </form>
            </li>
        </ul>
    </div>
    
</nav>