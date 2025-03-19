<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-[sans-serif] bg-white text-gray-800">

    <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4">
        <div class="grid md:grid-cols-2 items-center gap-10 max-w-6xl max-md:max-w-md w-full">
            <div>
                <h2 class="lg:text-5xl text-3xl font-extrabold lg:leading-[55px]">
                    Ecrivez/Prenez une Image de votre Texte
                </h2>
                <p class="text-sm mt-6">Transmettez votre texte manuscrit ou votre dessin á un texte numérique!</p>
                <p class="text-sm mt-12">Don't have an account 
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline ml-1">S'inscrire</a>
                </p>
            </div>

            <form class="max-w-md md:ml-auto w-full" action="{{ route('login') }}" method="POST">
                @csrf

                <h3 class="text-3xl font-extrabold mb-8">Se Connecter</h3>

              
                @if ($errors->any())
                    <div class="bg-red-100 text-red-600 p-4 rounded-md mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <input name="email" type="email" autocomplete="email" required class="bg-gray-100 w-full text-sm px-4 py-3.5 rounded-md outline-blue-600 focus:bg-transparent" placeholder="Email address" value="{{ old('email') }}" />
                    </div>
                    <div>
                        <input name="password" type="password" autocomplete="current-password" required class="bg-gray-100 w-full text-sm px-4 py-3.5 rounded-md outline-blue-600 focus:bg-transparent" placeholder="Password" />
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                            <label for="remember-me" class="ml-3 block text-sm">Remember me</label>
                        </div>
                        <div class="text-sm">
                            <a href="javascript:void(0);" class="text-blue-600 hover:text-blue-500 font-semibold">Forgot your password?</a>
                        </div>
                    </div>
                </div>
                <div class="!mt-8">
                    <button class="w-full shadow-xl py-2.5 px-4 text-sm font-semibold rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none" type="submit">
                        Log in
                    </button>
                </div>
               
            </form>
        </div>
    </div>

</body>
</html>
