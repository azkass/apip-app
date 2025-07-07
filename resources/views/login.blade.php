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
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css');
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm mx-auto bg-white px-5 py-8 sm:px-8 sm:py-10 rounded-xl shadow-lg">
        <div class="text-center mb-6">
            <h1 class="text-2xl sm:text-3xl font-semibold text-black">Sign in</h1>
        </div>
        <div class="flex justify-center">
            <a href="/auth/redirect" class="flex items-center justify-center w-full bg-gray-200 hover:bg-gray-300 text-black p-3 rounded-lg transition-colors duration-200">
                <i class="fa-brands fa-google mr-2"></i>
                <span>Sign In with Google</span>
            </a>
        </div>
    </div>
</body>
</html>
