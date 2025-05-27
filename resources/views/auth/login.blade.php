<x-guest-layout>

{{-- 
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/nifty.min.css" rel="stylesheet">
    <link href="css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="plugins/pace/pace.min.css" rel="stylesheet">
    <script src="plugins/pace/pace.min.js"></script>



    <link href="css/demo/nifty-demo.min.css" rel="stylesheet"> --}}

 
     <div style="background-color: #295ce9; height: 100vh; display: flex; justify-content: center; align-items: center;">

        
             <div class="card shadow" style="width: 100%; max-width: 400px; padding: 30px; ">
                    <h4 class="text-center mb-4" style="font-weight: bold; color: #333;">Login</h4>

                     <x-auth-session-status class="mb-4" :status="session('status')" />
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form  method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                 <label for="email" class="form-label" style="font-weight: 500;">Username</label>
		                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" autofocus>
		                </div>
                           <div class="mb-3">
                             <label for="password" class="form-label" style="font-weight: 500;">Password</label>
		                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
		                </div>
                           <div class="mb-3 text-center">
                                <button class="btn btn-primary" style="border-radius: 8px;" type="submit">Log In</button>
                            </div>
                        </form>
                  
            
        </div>
     </div>
    {{-- <div class="relative min-h-screen flex ">
        <div
            class="flex flex-col sm:flex-row items-center md:items-start sm:justify-center md:justify-start flex-auto min-w-0 bg-white">
            <div class="sm:w-1/2 xl:w-3/5 h-full hidden md:flex flex-auto items-center justify-center p-10 overflow-hidden bg-yellow-900 text-white bg-no-repeat bg-cover relative"
                style="background-image: url({{ asset('assets/img/bg.jpg') }})">
                    <ul class="circles">
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                </div>
                <div
                    class="md:flex md:items-center md:justify-center w-full sm:w-auto md:h-full w-2/5 xl:w-2/5 p-8  md:p-10 lg:p-14 sm:rounded-lg md:rounded-none bg-white">
                    <div class="max-w-md w-full space-y-8">
                        <div class="text-center">
                            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                                Welcome!
                            </h2>
                            <p class="mt-2 text-sm text-gray-500">Please sign in to your account</p>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="h-px w-16 bg-gray-200"></span>
                            <span class="text-gray-300 font-normal">User Credentials</span>
                            <span class="h-px w-16 bg-gray-200"></span>
                        </div>
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />

                        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="remember" value="true">
                            <div class="relative">
                                <label class="ml-3 text-sm font-bold text-gray-700 tracking-wide">Username</label>
                                <input type="text" name="username" id="username"
                                    class="w-full text-base px-4 py-2 border-b border-gray-300 focus:outline-none rounded-2xl focus:border-indigo-500"
                                    autocomplete="off" placeholder="Username" required autofocus>


                            </div>
                            <div class="mt-8 content-center">
                                <label class="ml-3 text-sm font-bold text-gray-700 tracking-wide">
                                    Password
                                </label>
                                <input type="password" name="password" id="password"
                                    class="w-full content-center text-base px-4 py-2 border-b rounded-2xl border-gray-300 focus:outline-none focus:border-indigo-500"
                                    autocomplete="off" placeholder="Password" required>
                            </div>
                            <div>
                                <button
                                    class="w-full flex justify-center bg-gradient-to-r from-yellow-500 to-yellow-600  hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-4  rounded-full tracking-wide font-semibold  shadow-lg cursor-pointer transition ease-in duration-500">
                                    Sign in
                                </button>
                            </div>
                            <p
                                class=" flex flex-col items-center justify-center mt-10 text-center text-md text-gray-500">
                                <span>Don't have an account?</span>
                                <a href="javascript:;"
                                    class="text-indigo-400 hover:text-blue-500 no-underline hover:underline cursor-pointer transition ease-in duration-300">
                                    Contact IP Phone 1953</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <script src="js/jquery.min.js"></script>

    <script src="js/bootstrap.min.js"></script>

    <script src="js/nifty.min.js"></script>

    <script src="js/demo/bg-images.js"></script> --}}

</x-guest-layout>