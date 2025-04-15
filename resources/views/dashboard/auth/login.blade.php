<!DOCTYPE html>
<html lang="en" dir="rtl" class="h-full bg-white">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
            rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                font-family: "Cairo", sans-serif;

            }
        </style>
    </head>

    <body class="h-full">
        <!--
  This example requires updating your template:

  ```
  <html class="h-full bg-white">
  <body class="h-full">
  ```
-->
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto w-[250px]" src={{ asset('assets/img/logo.jpg') }} alt="Your Company">
                <h2 class="mt-3 text-center text-2xl/9 font-bold tracking-tight text-gray-900">الدخول إلى حسابك الخاص
                </h2>
            </div>
            <div class="mt-2 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" action="{{ route('validate_login') }}" method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900">رقم الهوية</label>
                        <div class="mt-2">
                            {{-- {{ dd(old('identity_number')) }} --}}

                            <input type="text" autocomplete="off" value="{{ old('identity_number', '') }}"
                                name="identity_number" id="identity" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 border-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            @error('identity_number')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm/6 font-medium text-gray-900">كلمة المرور</label>

                        </div>
                        <div class="mt-2">
                            <input type="password" value="" autocomplete="off" name="password" id="password"
                                required
                                class="block w-full rounded-md border-2 bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">تسجيل
                            الدخول</button>
                    </div>
                </form>


            </div>
        </div>


    </body>

</html>
