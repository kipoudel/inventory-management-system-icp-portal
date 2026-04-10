<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Inventory System</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: radial-gradient(circle at top, #0f172a, #020617);
        }
    </style>
</head>

<body class="text-white min-h-screen flex items-center justify-center">

    <div class="max-w-5xl mx-auto px-6">

        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-bold">
                Smart Inventory & Order Management System
            </h1>
            <p class="text-gray-300 mt-4 text-lg">
                Laravel 13 + Vue.js Decoupled Architecture Assessment
            </p>
        </div>

        <!-- Cards -->
        <div class="grid md:grid-cols-2 gap-6">

            <!-- Backend -->
            <div class="bg-slate-900/70 p-6 rounded-2xl border border-slate-700">
                <h2 class="text-2xl font-semibold mb-3">🚀 Backend (Laravel API)</h2>

                <ul class="text-gray-300 space-y-2 text-sm">
                    <li>✔ Sanctum Authentication (SPA-ready)</li>
                    <li>✔ Fully decoupled REST API</li>
                    <li>✔ Product CRUD (SKU, Stock, Price, Category)</li>
                    <li>✔ Order processing with transactions</li>
                    <li>✔ Stock protection (no negative inventory)</li>
                    <li>✔ Search & filtering API</li>
                </ul>
            </div>

            <!-- Frontend -->
            <div class="bg-slate-900/70 p-6 rounded-2xl border border-slate-700">
                <h2 class="text-2xl font-semibold mb-3">🎨 Frontend (Vue SPA)</h2>

                <ul class="text-gray-300 space-y-2 text-sm">
                    <li>✔ Fully decoupled Vue.js application</li>
                    <li>✔ Authentication via API tokens</li>
                    <li>✔ Product dashboard with filters</li>
                    <li>✔ Cart & order creation flow</li>
                    <li>✔ Real-time stock updates</li>
                    <li>✔ State management (Pinia/Vuex)</li>
                </ul>
            </div>
        </div>

        <!-- Technical Requirements -->
        <div class="mt-10 bg-slate-900/70 p-6 rounded-2xl border border-slate-700">
            <h2 class="text-xl font-semibold mb-4">📌 Technical Requirements</h2>

            <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-300">

                <div>
                    <h3 class="text-white font-semibold mb-2">Security & Auth</h3>
                    <p>Sanctum authentication, CORS for separate frontend domain, secure API access.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Inventory System</h3>
                    <p>CRUD products, prevent negative stock, category-based structure.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Order Processing</h3>
                    <p>Transactional order creation, status handling, pivot table relations.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Architecture</h3>
                    <p>Service/Action classes, thin controllers, clean separation of concerns.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Data Layer</h3>
                    <p>Form Requests, API Resources, migrations, seeders, factories.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Testing</h3>
                    <p>Feature tests for stock safety, order creation, and validation logic.</p>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-10 text-gray-500 text-sm">
            Built with Laravel 13 • Vue.js SPA Architecture • TailwindCSS UI
        </div>

    </div>

</body>
</html>