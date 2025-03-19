<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-[sans-serif]">
    <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4">
        <div class="max-w-md w-full">
            
            <div class="p-8 rounded-2xl bg-white shadow">
                <h2 class="text-gray-800 text-center text-2xl font-bold">Register</h2>
                @if ($errors->any())
                    <div class="mt-4 bg-red-50 p-4 rounded-lg border border-red-200">
                        <ul class="text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="mt-8 space-y-4" method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <div>
                        <label for="id" class="text-gray-800 text-sm mb-2 block">ID</label>
                        <input type="text" id="id" name="id" value="{{ old('id') }}" required 
                               class="w-full text-gray-800 text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600" />
                    </div>
                    <div>
                        <label for="name" class="text-gray-800 text-sm mb-2 block">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                               class="w-full text-gray-800 text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600" />
                    </div>
                    <div>
                        <label for="email" class="text-gray-800 text-sm mb-2 block">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                               class="w-full text-gray-800 text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600" />
                    </div>
                    <div>
                        <label for="password" class="text-gray-800 text-sm mb-2 block">Password</label>
                        <input type="password" id="password" name="password" required 
                               class="w-full text-gray-800 text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600" />
                    </div>
                    <div>
                        <label for="password2" class="text-gray-800 text-sm mb-2 block">Confirm Password</label>
                        <input type="password" id="password2" name="password2" required 
                               class="w-full text-gray-800 text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600" />
                    </div>
                    <div class="mt-8">
                        <button type="submit" 
                                class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Register
                        </button>
                    </div>
                    <p class="text-gray-800 text-sm text-center mt-8">
                        Already have an account? 
                        <a href="javascript:void(0);" class="text-blue-600 hover:underline ml-1 font-semibold">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
