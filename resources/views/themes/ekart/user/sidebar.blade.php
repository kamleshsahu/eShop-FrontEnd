<aside class="col-md-3 padding-bottom">
    <div class="card mb-3">
        <div class="card-body">
            <div class="profile-header-container">   
                <div class="profile-header-img"> 
                    <a class="navbar-brand ml-2" href="{{ route('home') }}">
                        <img src="{{ asset('images/headerlogo.png') }}"  alt="Logo">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="list-group">
        <a href="{{ route('my-account') }}" class="list-group-item"><em class="fa fa-user"></em><span class="side-menu">My Profile</span></a>
        <a href="{{ route('change-password') }}" class="list-group-item"><em class="fa fa-asterisk"></em><span class="side-menu">Change Password</span></a>
        <a href="{{ route('my-orders') }}" class="list-group-item"><em class="fa fa-cab"></em><span class="side-menu">My Orders</span></a>
        <a href="{{ route('notification') }}" class="list-group-item"><em class="fa fa-bell"></em><span class="side-menu">Notifications</span></a>
        <a href="{{ route('favourite') }}" class="list-group-item"><em class="fa fa-heart"></em><span class="side-menu">Favourite</span></a>
        <a href="{{ route('wallet-history') }}" class="list-group-item"><em class="fa fa-google-wallet"></em><span class="side-menu">Wallet History</span></a>
        <a href="{{ route('transaction-history') }}" class="list-group-item"><em class="fa fa-outdent"></em><span class="side-menu">Transaction History</span></a>
        <a href="{{ route('refer-earn') }}" class="list-group-item"><em class="fa fa-user-plus"></em><span class="side-menu">Refer & Earn</span></a>
        <a href="{{ route('addresses') }}" class="list-group-item"><em class="fa fa-wrench"></em><span class="side-menu">Manage Addresses</span></a>
        <a href="{{ route('logout') }}" class="list-group-item"><em class="fa fa-sign-out"></em><span class="side-menu">Logout</span></a>
    </div>
</aside>