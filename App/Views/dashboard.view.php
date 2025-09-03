<?php
/**
 * Home Page View
 *
 * Filename:        home.view.php
 * Location:        /App/Views
 * Project:         XXX-SaaS-Vanilla-MVC-YYYY-SN
 * Date Created:    23/08/2024
 *
 * Author:          Adrian Gould <Adrian.Gould@nmtafe.wa.edu.au>
 *
 */

loadPartial('header');
loadPartial('navigation');

?>

<main class="container mx-auto bg-gray-50 py-8 px-4 shadow shadow-black/25 title">
    <article>
        <header class="bg-gray-700 text-gray-200 -mx-4 -mt-8 p-8 text-2xl font-bold mb-2 ">
            <h1>Vanilla PHP MVC Demo</h1>
        </header>

        <section class="bg-gray-500
                        grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3
                        gap-8 -mx-4 -mt-2 px-4 py-0
                        border border-t-0 border-x-0 border-b-gray-300">

            <section class=" my-8
                            grid grid-cols-5 items-center justify-center
                            bg-gray-100 dark:bg-gray-800 shadow">
                <div class=" p-4 align-center text-center
                            col-span-2
                            text-amber-50 dark:text-amber-500
                            bg-amber-500 dark:bg-amber-800 mr-4">
                    <p class="text-center"><i class="fa fa-mug-saucer text-4xl"></i></p>
                    <h4 class="text-center mt-1 mb-0 font-medium ">
                        Jokes
                    </h4>
                </div>
                <div class="col-span-3">
                    <p class="text-4xl font-semibold text-amber-700 dark:text-amber-200">
                        <?= $jokeCount->total ?? "-" ?>
                    </p>
                </div>
            </section>


            <section class=" my-8
                            grid grid-cols-5 items-center justify-center
                            bg-gray-100 dark:bg-gray-800 shadow">
                <div class=" p-4 align-center text-center
                            col-span-2
                            text-green-50 dark:text-green-500
                            bg-green-500 dark:bg-green-800 mr-4">
                    <p class="text-center"><i class="fa fa-mug-saucer text-4xl"></i></p>
                    <h4 class="text-center mt-1 mb-0 font-medium ">
                        Categories
                    </h4>
                </div>
                <div class="col-span-3">
                    <p class="text-4xl font-semibold text-green-700 dark:text-green-200">
                        <?= $categoryCount->total ?? "-" ?>
                    </p>
                </div>
            </section>


            <section class=" my-8
                            grid grid-cols-5 items-center justify-center
                            bg-gray-100 dark:bg-gray-800 shadow">
                <div class=" p-4 align-center text-center
                            col-span-2
                            text-prussianblue-50 dark:text-prussianblue-500
                            bg-prussianblue-500 dark:bg-prussianblue-800 mr-4">
                    <p class="text-center"><i class="fa fa-users text-4xl"></i></p>
                    <h4 class="text-center mt-1 mb-0 font-medium text-prussianblue-900">
                        Users
                    </h4>
                </div>
                <div class="col-span-3 ">

                    <p class="text-4xl font-semibold text-prussianblue-700 dark:text-prussianblue-200">
                        <?= $userCount->total ?? "-" ?>
                    </p>
                </div>
            </section>

        </section>

        <section class="my-8 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-8 justify-center">
            <header class="col-span-1 md:col-span-3 lg:col-span-5">
                <h3 class="text-xl text-semi-bold px-2 mt-4">
                    Random Categories
                </h3>
            </header>
            <?php
            foreach ($categories ?? [] as $category):
                ?>
                <!--            article>(header>h4{Name})+(section>p{Description})+(footer>p{Price})-->
                <section class="bg-gray-100 border border-gray-400 shadow  flex flex-col overflow-hidden">

                    <a class="group relative inline-block overflow-hidden border bg-white border-gray-800 px-10 py-1 focus:ring-2focus:outline-hidden"
                       href="/categories/<?= $category->id ?>"
                    >
                        <span class="absolute inset-y-0 left-0 w-[2px] bg-gray-800 transition-all duration-500 ease-in-out group-hover:w-full"></span>
                        <span class="relative text-sm font-medium text-black transition-colors duration-500 group-hover:text-white">
                                <?= $category->title ?>
                            </span>
                    </a>

                </section>

            <?php
            endforeach
            ?>
        </section>

    </article>
</main>


<?php
loadPartial('footer');
?>
