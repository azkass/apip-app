<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Default Title' }}</title>
    @vite('resources/css/app.css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </style>
</head>
<body>
    <div class="relative mx-auto w-full max-w-md bg-white px-6 pt-10 pb-8 mt-20 shadow-xl ring-1 ring-gray-900/5 sm:rounded-xl sm:px-10">
        <div class="w-full">
            <div class="text-center">
                <h1 class="text-3xl font-semibold text-gray-900">Sign in</h1>
                <p class="mt-2 text-gray-500">Sign in below to access your account</p>
            </div>
            <div class="mt-5">
                <form action="">
                    <div class="relative mt-6">
                        <input type="email" name="email" id="email" placeholder="Email Address" class="peer mt-1 w-full border-b-2 border-gray-300 px-0 py-1 placeholder:text-transparent focus:border-gray-500 focus:outline-hidden" autocomplete="NA" />
                        <label for="email" class="pointer-events-none absolute top-0 left-0 origin-left -translate-y-1/2 transform text-sm text-gray-800 opacity-75 transition-all duration-100 ease-in-out peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-focus:top-0 peer-focus:pl-0 peer-focus:text-sm peer-focus:text-gray-800">Email Address</label>
                    </div>
                    <div class="relative mt-6">
                        <input type="password" name="password" id="password" placeholder="Password" class="peer peer mt-1 w-full border-b-2 border-gray-300 px-0 py-1 placeholder:text-transparent focus:border-gray-500 focus:outline-hidden" />
                        <label for="password" class="pointer-events-none absolute top-0 left-0 origin-left -translate-y-1/2 transform text-sm text-gray-800 opacity-75 transition-all duration-100 ease-in-out peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-focus:top-0 peer-focus:pl-0 peer-focus:text-sm peer-focus:text-gray-800">Password</label>
                    </div>
                    <div class="my-6">
                        <button type="submit" class="w-full rounded-md bg-black px-3 py-4 text-white focus:bg-gray-600 focus:outline-hidden">Sign in</button>
                    </div>
                    <p class="text-center text-sm text-gray-500">Don't have an account yet?
                        <a href="#!"
                            class="font-semibold text-gray-600 hover:underline focus:text-gray-800 focus:outline-hidden">Sign
                            up
                        </a>.
                    </p>
                    <br>
                    <a href="/auth/redirect" class="bg-red-500 text-white p-2 rounded-lg">
                        Login dengan Google
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
