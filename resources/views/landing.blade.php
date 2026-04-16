<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <title>FocusLife</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <style>
        .fade-up {
            animation: fadeUp 1s ease forwards;
            opacity: 0;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-white">

<!-- NAVBAR -->
<nav class="flex justify-between items-center px-8 py-6 max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold">FocusLife</h1>

    <div class="flex items-center gap-6">
        <a href="/login" class="hover:text-blue-500">Login</a>
        <a href="/register" class="bg-blue-600 px-5 py-2 rounded-xl text-white hover:bg-blue-700">
            Get Started
        </a>
        <button onclick="toggleDark()">🌙</button>
    </div>
</nav>

<!-- HERO -->
<section class="text-center py-28 px-6">
    <h1 class="text-6xl md:text-7xl font-bold mb-6 fade-up">
        Control Your Time.<br>
        <span class="text-blue-600">Design Your Life.</span>
    </h1>

    <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto mb-8 fade-up">
        Turn your goals into daily actions. Stay focused, build habits, and track your progress.
    </p>

    <div class="flex justify-center gap-4 fade-up">
        <a href="/register" class="bg-blue-600 px-8 py-4 rounded-xl text-lg hover:scale-105 transition">
            Start Free
        </a>

        <a href="#features" class="px-8 py-4 rounded-xl border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
            Learn More
        </a>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="py-24 max-w-7xl mx-auto px-6">

    <h2 class="text-4xl font-bold text-center mb-16 fade-up">
        Everything you need to stay productive
    </h2>

    <div class="grid md:grid-cols-3 gap-10">

        <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:scale-105 transition fade-up">
            <h3 class="text-xl font-semibold mb-3">🎯 Goals</h3>
            <p class="text-gray-500">Define long-term objectives and stay aligned.</p>
        </div>

        <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:scale-105 transition fade-up">
            <h3 class="text-xl font-semibold mb-3">🗓 Tasks</h3>
            <p class="text-gray-500">Plan your daily and weekly work easily.</p>
        </div>

        <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:scale-105 transition fade-up">
            <h3 class="text-xl font-semibold mb-3">⏱ Time</h3>
            <p class="text-gray-500">Track your time and improve productivity.</p>
        </div>

        <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:scale-105 transition fade-up">
            <h3 class="text-xl font-semibold mb-3">🔥 Focus</h3>
            <p class="text-gray-500">Work deeply without distractions.</p>
        </div>

        <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:scale-105 transition fade-up">
            <h3 class="text-xl font-semibold mb-3">📈 Stats</h3>
            <p class="text-gray-500">Analyze your performance over time.</p>
        </div>

        <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:scale-105 transition fade-up">
            <h3 class="text-xl font-semibold mb-3">✅ Habits</h3>
            <p class="text-gray-500">Build strong daily habits.</p>
        </div>

        
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-24 bg-gray-50 dark:bg-gray-900 text-center">

    <h2 class="text-4xl font-bold mb-16">How it works</h2>

    <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto px-6">

        <div class="fade-up">
            <h3 class="text-xl font-semibold mb-2">1. Set Goals</h3>
            <p class="text-gray-500">Define your direction.</p>
        </div>

        <div class="fade-up">
            <h3 class="text-xl font-semibold mb-2">2. Plan Tasks</h3>
            <p class="text-gray-500">Break goals into actions.</p>
        </div>

        <div class="fade-up">
            <h3 class="text-xl font-semibold mb-2">3. Track Progress</h3>
            <p class="text-gray-500">Improve every day.</p>
        </div>

    </div>
</section>

<!-- CTA -->
<section class="py-24 text-center">

    <h2 class="text-4xl font-bold mb-6">Start improving your life today</h2>

    <a href="/register" class="bg-blue-600 px-10 py-4 rounded-xl text-lg hover:bg-blue-700">
        Join FocusLife
    </a>

</section>

<!-- FOOTER -->
<footer class="text-center py-10 text-gray-500 text-sm">
    © 2026 FocusLife
</footer>

<script>
function toggleDark() {
    document.documentElement.classList.toggle('dark');
}
</script>

</body>
</html>