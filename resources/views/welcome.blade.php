<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>RamShop platform</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap");

        html {
            font-family: "Roboto", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Odibee+Sans&family=Roboto:wght@400;700&display=swap');
        .money {
            font-family: 'Odibee Sans', cursive;
        }
    </style>
</head>

<body class="leading-normal tracking-normal text-indigo-400 mx-6 my-6 md:my-0 bg-cover bg-fixed max-h-screen overflow-hidden" style="background-image: url('storage/header.png');">
    <div class="h-full">
        <!--Nav-->
        <div class="w-full container mx-auto">
            <div class="w-full flex items-center justify-between">
                <a class="flex items-center text-indigo-400 no-underline hover:no-underline font-bold text-2xl lg:text-4xl" href="#">
                    Ram<span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 via-pink-500 to-purple-500">Shop</span>
                </a>
                <div class="flex w-1/2 justify-end content-center">
                    <a class="inline-block text-blue-300 no-underline hover:text-pink-500 hover:text-underline text-center h-10 p-2 md:h-auto md:p-4 transform hover:scale-125 duration-300 ease-in-out" href="https://t.me/brevis_ng">
                        <img src="storage/telegram.png" class="w-8 h-8 object-contain">
                    </a>
                </div>
            </div>
        </div>

        <!--Main-->
        <div class="container pt-16 md:pt-28 mx-auto flex flex-wrap flex-col md:flex-row items-center">
            <!--Left Col-->
            <div class="flex flex-col w-full xl:w-2/5 justify-center lg:items-start overflow-y-hidden">
                <h1 class="my-4 text-3xl md:text-5xl text-white opacity-75 font-bold leading-tight text-center md:text-left">
                    Sử dụng
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 via-pink-500 to-purple-500">
                        Nền tảng mới
                    </span>
                    để tăng trưởng kinh doanh
                </h1>
                <p class="leading-normal text-base md:text-2xl mb-8 text-center md:text-left">
                    RamShop tự hào tiên phong tích hợp nền tảng WebApp vào Telegram.
                </p>

                <form class="bg-gray-900 opacity-75 w-full shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label class="block text-blue-300 py-2 font-bold mb-2" for="emailaddress">
                            RamShop mang đến hệ thống quản lý bán hàng và nền tảng WebApp trên ứng dụng Telegram. Mang lại trải nghiệm liền mạch cho người dùng.
                        </label>
                    </div>
                    <div class="flex items-center justify-between pt-4">
                        <a href="http://t.me/clovertele_bot/ramshop" target="_blank" class="bg-gradient-to-r from-purple-800 to-green-500 hover:from-pink-500 hover:to-green-500 text-white font-bold py-2 px-4 rounded focus:ring transform transition hover:scale-105 duration-300 ease-in-out" type="button">
                            Demo
                        </a>
                        <a href="https://t.me/brevis_ng" target="_blank" class="bg-gradient-to-r from-purple-800 to-green-500 hover:from-pink-500 hover:to-green-500 text-white font-bold py-2 px-4 rounded focus:ring transform transition hover:scale-105 duration-300 ease-in-out" type="button">
                            Liên Hệ
                        </a>
                    </div>
                </form>
            </div>

            <!--Right Col-->
            <div class="w-full xl:w-3/5 p-12 overflow-hidden">
                <img class="mx-auto w-full md:w-5/6 transform -rotate-6 transition hover:scale-105 duration-700 ease-in-out hover:rotate-6" src="storage/dashboard.png" />
            </div>

            <div class="mx-auto md:pt-16">
                <p class="text-blue-400 font-bold pb-8 lg:pb-6 text-center">
                    Chi phí cạnh tranh
                </p>
                <div class="flex w-full justify-center md:justify-start pb-24 lg:pb-0 fade-in gap-x-3">
                    <button class="px-10 py-2 rounded-lg shadow-lg transform hover:scale-125 duration-300 ease-in-out bg-indigo-500">
                        <p class="text-white text-2xl money">₱3999</p>
                        <p class="text-xs text-gray-300">/monthly</p>
                    </button>
                    <button class="px-10 py-2 rounded-lg shadow-lg transform hover:scale-125 duration-300 ease-in-out bg-indigo-500">
                        <p class="text-white text-2xl money">₱22999</p>
                        <p class="text-xs text-gray-300">/6 month</p>
                    </button>
                </div>
            </div>

            <!--Footer-->
            <div class="w-full pt-16 pb-4 text-sm text-center md:text-left fade-in">
                <a class="text-gray-500 no-underline hover:no-underline" href="#">&copy; RAMSHOP 2023</a>
                - Creator by
                <a class="text-gray-500 no-underline hover:no-underline" href="https://brevisnguyen.com">Brevis Nguyen</a>
            </div>
        </div>
    </div>
</body>

</html>
