<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title>OurApp</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script defer src="https://use.fontawesome.com/releases/v5.5.0/js/all.js" integrity="sha384-GqVMZRt5Gn7tB9D9q7ONtcp4gtHIUEW/yG7h98J7IpE3kpi+srfFyyB/04OV6pG0" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com"/>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet"/>
        <link rel="stylesheet" href="/main.css"/>
    </head>
    <body>
        <header class="header-bar mb-3">
            <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between p-3">
                <h4 class="my-0 me-md-auto font-weight-normal"><a href="/" class="text-white text-decoration-none">OurApp</a></h4>
                @auth
                    <div class="flex-row my-3 my-md-0">
                        <a href="#" class="text-white me-2 header-search-icon" title="Search" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-search"></i></a>
                        <span class="text-white me-2 header-chat-icon" title="Chat" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-comment"></i></span>
                        <a href="/profile/{{ auth()->user()->username }}" class="me-2 text-decoration-none">
                            <img title="My Profile" data-toggle="tooltip" data-placement="bottom" style="width: 32px; height: 32px; border-radius: 16px"
                                 src="{{ auth()->user()->avatar }}"/>
                        </a>
                        <a class="btn btn-sm btn-success me-2" href="/create-post">Create Post</a>
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-secondary">Sign Out</button>
                        </form>
                    </div>
                @else
                    <form action="/login" method="POST" class="mb-0 pt-2 pt-md-0">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md me-0 pr-md-0 mb-3 mb-md-0">
                                <input name="loginusername" class="form-control form-control-sm input-dark" type="text" placeholder="Username" autocomplete="off"/>
                            </div>
                            <div class="col-md me-0 pr-md-0 mb-3 mb-md-0">
                                <input name="loginpassword" class="form-control form-control-sm input-dark" type="password" placeholder="Password"/>
                            </div>
                            <div class="col-md-auto">
                                <button class="btn btn-primary btn-sm">Sign In</button>
                            </div>
                        </div>
                    </form>
                @endauth
            </div>
        </header>
        <!-- header ends here -->

        @if(session()->has('success'))
            <div class="container container--narrow">
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="container container--narrow">
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{ $slot }}

        <!-- footer begins -->
        <footer class="border-top text-center small text-muted py-3">
            <p class="m-0">Copyright &copy; {{ date('Y') }} <a href="/" class="text-muted text-decoration-none">OurApp</a>. All rights reserved.</p>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <script>
          //$('[data-toggle="tooltip"]').tooltip()
        </script>
    </body>
</html>
