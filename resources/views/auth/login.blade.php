<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
        <x-slot name="title">Login</x-slot>

    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center d-flex justify-content-center mb-3">
                                        <a href="index.html"><img src="{{asset('images/logo-dark.webp') }}"alt="" width="250px" style="display: flex; justify-content: center;"></a>
                                    </div>
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                      <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Email</strong></label>
                                            <input id="email" name="email" type="email" class="form-control" placeholder="hello@example.com">
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />

                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <input id="password" name="password" type="password" class="form-control" placeholder="********">
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />

                                        </div>
                                        <div class="row d-flex justify-content-between mt-4 mb-2">
                                            <div class="mb-3">
                                               <div class="form-check custom-checkbox ms-1">
                                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                                    <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                                                </div>
                                            </div>
                                             @if (Route::has('password.request'))
                                            <div class="mb-3">
                                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="text-center">
                                         <x-primary-button class="ms-3">
                                                        {{ __('Log in') }}
                                                    </x-primary-button>   
                                                </div>
                                    </form>
                                    <div class="new-account mt-3">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    

</x-guest-layout>
