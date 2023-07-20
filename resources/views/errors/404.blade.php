<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link href="{{ asset('/root.css') }}" rel="stylesheet">

    <!-- Ionic -->
    <script src="https://unpkg.com/flowbite@1.5.3/dist/datepicker.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


    @routes
    @vite('resources/js/app.js')
</head>

<body class="font-sans antialiased bg-primaryBackground overflow-hidden">
    <div class="h-screen lg:px-24 lg:py-24 md:py-20 md:px-44 px-4 py-24 items-center flex justify-center flex-col-reverse lg:flex-row md:gap-28 gap-16">
             <div class="xl:pt-24 w-full xl:w-1/2 relative pb-12 lg:pb-0">
                 <div class="relative">
                     <div class="absolute">
                         <div class="-z-10">
                             <h1 class="my-2 text-white font-bold text-2xl">
                                 Looks like you've found the
                                 doorway to the great nothing
                             </h1>
                             <p class="my-2 text-white">Sorry about that! Please visit our hompage to get where you need to go.</p>
                             <a href="/login">
                             <button class="sm:w-full lg:w-auto my-2 rounded md py-4 px-8 text-center bg-blue-700 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-700 focus:ring-opacity-50 dark:bg-darkSecondaryBackground">

                                 Take me there!</button></a>
                         </div>
                     </div>
                     <div>
                         <p class="text-9xl opacity-20 text-white">404</p>
                     </div>
                 </div>
             </div>
             <div>
                 <img src="{{ url('/img/error/connection-lost.png') }}" />
             </div>
         </div>
 </body>

</html>
