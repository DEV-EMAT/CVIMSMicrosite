<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-minimize">
            <button id="minimizeSidebar" class="btn btn-fill btn-icon cstm-round"><i class="ti-more-alt"></i></button>
        </div>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar bar1"></span>
                <span class="icon-bar bar2"></span>
                <span class="icon-bar bar3"></span>
            </button>
            <a class="navbar-brand" href="#">
               @yield('location')
            </a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#notifications" class="dropdown-toggle btn-rotate" data-toggle="dropdown"><i class="fa fa-gear cstmAlign"></i>&nbsp;Settings</a>
                    <ul class="dropdown-menu">
                        <li><a onclick="logout()" id="logout" class="btn-rotate pointer"><i class="fa fa-sign-out cstmAlign"></i>&nbsp;Log out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script>
    function logout(){
        swal({
              title: 'Are you sure?',
              text: "You will signout on this account!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, logout!'
            }).then((result) => {
              if (result.value) {
                // ajax delete data to database
                    $.ajax({
                        url : '{{ route('logout') }}',
                        type: "POST",
                        data:{ _token: "{{csrf_token()}}"},
                        dataType: "JSON",
                        success:function(response){
                            if(response){
                                sessionStorage.removeItem("firstLogIn");
                                window.location.reload();
                            }
                        }
                    });
                }
            });
    }
</script>