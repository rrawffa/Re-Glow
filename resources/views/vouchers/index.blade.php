
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vouchers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/vouchers.css') }}">
    <style>

        /* Custom colors for Tailwind to use in the code block */

        :root {

            --color-pink-soft: #F9B6C7; /* Main Pink Accent (Badge BG) */

            --color-dark-text: #20413A; /* Dark Text/Accent (Teal-ish) */

            --color-hero-bg: #F9FAFB; /* Off-White Background */

            --color-love-red: #FF0000; /* Red color for filled heart icon */

        }

        .text-dark-text { color: var(--color-dark-text); }

        .bg-pink-soft { background-color: var(--color-pink-soft); }

        .border-pink-soft { border-color: var(--color-pink-soft); }

        .text-love-red { color: var(--color-love-red); }



        /* Apply Poppins font globally */

        body {

            font-family: 'Poppins', sans-serif;

            background-color: white;

        }



        /* Utility classes for hover/active state matching the theme */

        .redeem-button:hover:not(:disabled) {

            opacity: 0.9;

        }



        /* Modal specific styling (backdrop) */

        .modal-backdrop {

            position: fixed;

            top: 0;

            left: 0;

            width: 100%;

            height: 100%;

            background-color: rgba(0, 0, 0, 0.5);

            display: none; /* Hidden by default */

            justify-content: center;

            align-items: center;

            z-index: 50;

            backdrop-filter: blur(5px);

        }



        /* Utility class to show the modal */

        .modal-active {

            display: flex;

        }



        /* Hide one icon while showing the other */

        .heart-icon.hidden {

            display: none;

        }

    </style>



</head>

<body class="bg-white">

 @include('layouts.navbar')

<main>

    <!-- VOUCHER SUBNAV AND POINTS (Combined as requested) -->

    <div class="border-b border-gray-200 bg-white sticky top-0 z-10">

        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center text-sm font-medium">

            <!-- Tabs - Added IDs for interactivity -->

            <div class="flex gap-8">

                <!-- Tab All Vouchers -->

                <a href="#" id="tab-all" class="py-4 border-b-2 border-pink-soft text-dark-text tab-item transition duration-300">

                    All Vouchers

                </a>

                <!-- Tab Favorite Vouchers -->

                <a href="#" id="tab-favorite" class="py-4 text-gray-500 hover:text-dark-text transition duration-300">

                    Favorite Vouchers

                </a>

            </div>

           

            <!-- User Points BADGE (Updated: Icon, Points, Pink BG) -->

            <div id="user-points-badge" class="bg-pink-soft px-4 py-2 rounded-full flex items-center gap-1.5 text-dark-text font-semibold text-base transition duration-300 hover:opacity-90">

                <!-- Coin Icon (Stacked, Dark Teal Color) -->

                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">

                    <path d="M18 10c0 1.1-.9 2-2 2H8c-1.1 0-2-.9-2-2s.9-2 2-2h8c1.1 0 2 .9 2 2zM18 14c0 1.1-.9 2-2 2H8c-1.1 0-2-.9-2-2s.9-2 2-2h8c1.1 0 2 .9 2 2zM19 18c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2s.9-2 2-2h10c1.1 0 2 .9 2 2z"/>

                </svg>

                <span id="user-points-display">450</span>

                <span>Points</span>

            </div>

        </div>

    </div>



    <!-- HERO SECTION -->

<section class="relative py-20 md:py-24 text-center bg-cover bg-center bg-no-repeat"

    style="background-image: url('{{ asset('images/dashboard-hero.jpg') }}'); background-color: #fff5f7;">

    <div class="absolute inset-0 bg-white/70"></div> <!-- Lapisan transparan biar teks tetap jelas -->

   

    <div class="relative z-10 max-w-3xl mx-auto px-6">

        <h2 class="text-2xl md:text-4xl font-semibold text-[#2F3E35] mb-4">

            Redeem Your Points for Exciting Rewards!

        </h2>

        <p class="text-gray-700 text-sm md:text-base leading-relaxed mb-6">

            Turn your recycling efforts into amazing rewards. Browse through our collection

            of vouchers from top brands and redeem them with your earned points.

        </p>

        <a href="#voucher-catalog"

           class="inline-block bg-pink-soft text-dark-text px-6 py-2.5 rounded-full font-semibold shadow-sm hover:opacity-90 transition">

            Browse Vouchers

        </a>

    </div>

</section>



    <!-- VOUCHER CATALOG (Updated: Centered Title & Subtitle) -->

    <section id="voucher-catalog" class="py-14 px-6 max-w-7xl mx-auto">

        <div class="text-center mb-10">

            <!-- Main Title: Available Vouchers (Semi Bold, Dark Teal Text) -->

            <h3 class="text-4xl font-semibold text-dark-text mb-2">

                Available Vouchers

            </h3>

            <!-- Subtitle: Centered, smaller, not bold -->

            <p class="text-lg text-gray-600">

                choose from our selection of partner rewards

            </p>

        </div>



        <div id="voucher-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

            <!-- Vouchers will be injected here by JavaScript -->

        </div>



        <!-- Load More -->

        <div class="text-center mt-12">

            <button id="load-more-btn" class="bg-dark-text/10 text-dark-text px-8 py-3 rounded-full font-bold hover:bg-dark-text/20 transition">

                Load More

            </button>

        </div>

    </section>

</main>



<!-- VOUCHER DETAIL MODAL -->

<div id="voucher-detail-modal" class="modal-backdrop transition-opacity duration-300" role="dialog" aria-modal="true">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl m-4 transform transition-transform duration-300 scale-95 opacity-0 overflow-y-auto max-h-[90vh]">

       

        <!-- Modal Content Header -->

        <div class="flex justify-between items-center p-6 border-b border-gray-100 sticky top-0 bg-white z-10">

            <h4 class="text-xl font-semibold text-dark-text" id="modal-voucher-name">Detail Voucher</h4>

            <button id="close-modal-btn" class="text-gray-400 hover:text-dark-text transition" aria-label="Close modal">

                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>

            </button>

        </div>



        <!-- Modal Content Body -->

        <div class="p-6">

            <div id="modal-voucher-image" class="h-48 bg-pink-soft rounded-lg mb-4 overflow-hidden">

                <!-- Image goes here -->

            </div>

           

            <p id="modal-voucher-brand" class="text-gray-500 text-sm mb-3"></p>

            <h2 id="modal-voucher-title" class="text-3xl font-bold text-dark-text mb-4"></h2>

           

            <!-- Key Info -->

            <div class="flex flex-wrap gap-4 text-sm mb-6">

                <div class="flex items-center space-x-1 text-dark-text">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 10v-2"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 100-18 9 9 0 000 18z"></path></svg>

                    <span id="modal-voucher-points" class="font-semibold text-pink-soft"></span>

                    <span>Points</span>

                </div>

                <div class="flex items-center space-x-1 text-gray-500">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-4 18h4M4 11h16m-2 2h-4m2 2v-4m-4 4v-4m-4 4v-4m-4 4v-4"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 19H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2z"></path></svg>

                    <span id="modal-voucher-expires"></span>

                </div>

            </div>



            <!-- Description & Terms (Mock Content) -->

            <h5 class="text-lg font-semibold text-dark-text mb-2">Deskripsi Voucher</h5>

            <p class="text-gray-700 mb-6">

                Voucher ini memberikan Anda diskon spesial untuk semua produk *skincare* dari Brand A.

                Gunakan kode ini saat *checkout* untuk menikmati penghematan.

                Voucher hanya berlaku untuk satu kali transaksi dan tidak dapat digabungkan dengan promosi lainnya.

                Segera tukarkan poin Anda sebelum stok habis!

            </p>



            <h5 class="text-lg font-semibold text-dark-text mb-2">Syarat & Ketentuan</h5>

            <ul class="list-disc list-inside text-gray-700 space-y-1 text-sm mb-8">

                <li>Berlaku untuk pembelian minimal Rp 100.000.</li>

                <li>Tidak termasuk biaya pengiriman.</li>

                <li>Hanya berlaku di website resmi Brand A.</li>

                <li>Tidak dapat ditransfer atau diuangkan.</li>

                <li id="modal-voucher-stock-terms">Sisa stok: </li>

            </ul>



        </div>

       



        <!-- Modal Footer - Action Button -->

        <div class="p-6 pt-0 flex justify-end">

            <button id="modal-redeem-button"

                class="redeem-button px-8 py-3 rounded-full font-bold text-center transition w-full sm:w-auto"

                >

                Redeem Now

            </button>

        </div>

    </div>

</div>



{{-- Footer --}}

@include('layouts.footer')



<script>

    // Mock Voucher Data (from the Blade template)

    const vouchers = [

        {id: 1, name:'$10 Off Skincare',brand:'Brand A',points:200,expires:'31 Dec 2024',stock:12, image: 'https://placehold.co/400x180/F9B6C7/20413A?text=Skincare+Offer', isFavorite: false},

        {id: 2, name:'15% Off Your Next Purchase',brand:'Brand B',points:300,expires:'15 Nov 2024',stock:5, image: 'https://placehold.co/400x180/F9B6C7/20413A?text=Next+Purchase', isFavorite: true},

        {id: 3, name:'Free Lipstick',brand:'Brand C',points:250,expires:'31 Dec 2024',stock:0, image: 'https://placehold.co/400x180/F9B6C7/20413A?text=Free+Gift', isFavorite: false},

        {id: 4, name:'2-for-1 Mascara',brand:'Brand A',points:400,expires:'30 Oct 2024',stock:22, image: 'https://placehold.co/400x180/F9B6C7/20413A?text=Mascara+Deal', isFavorite: false},

        {id: 5, name:'$5 Off Foundation',brand:'Brand D',points:100,expires:'12 Dec 2024',stock:50, image: 'https://placehold.co/400x180/F9B6C7/20413A?text=Foundation+Promo', isFavorite: true},

        {id: 6, name:'20% Off Nail Polish',brand:'Brand B',points:350,expires:'01 Nov 2024',stock:8, image: 'https://placehold.co/400x180/F9B6C7/20413A?text=Nail+Polish', isFavorite: false},

        {id: 7, name:'Free Shipping',brand:'Brand C',points:150,expires:'31 Dec 2024',stock:100, image: 'https://placehold.co/400x180/F9B6C7/20413A?text=Shipping+Offer', isFavorite: false},

    ];

    let userPoints = 450;



    // Element references

    const gridContainer = document.getElementById('voucher-grid');

    const userPointsDisplay = document.getElementById('user-points-display');

    const modalBackdrop = document.getElementById('voucher-detail-modal');

    const modalContent = modalBackdrop.querySelector('.max-w-2xl');

    const closeModalBtn = document.getElementById('close-modal-btn');

    const modalRedeemButton = document.getElementById('modal-redeem-button');



    const tabAll = document.getElementById('tab-all');

    const tabFavorite = document.getElementById('tab-favorite');

   

    // State for filtering

    let currentFilter = 'all'; // 'all' or 'favorite'



    // Function to set the active filter and update UI

    const setFilter = (filter) => {

        currentFilter = filter;

       

        // Update tab styling

        if (filter === 'all') {

            // Activate All Vouchers tab

            tabAll.classList.add('border-b-2', 'border-pink-soft', 'text-dark-text');

            tabAll.classList.remove('text-gray-500');

            // Deactivate Favorite Vouchers tab

            tabFavorite.classList.remove('border-b-2', 'border-pink-soft', 'text-dark-text');

            tabFavorite.classList.add('text-gray-500', 'hover:text-dark-text');

        } else {

            // Activate Favorite Vouchers tab

            tabFavorite.classList.add('border-b-2', 'border-pink-soft', 'text-dark-text');

            tabFavorite.classList.remove('text-gray-500');

            // Deactivate All Vouchers tab

            tabAll.classList.remove('border-b-2', 'border-pink-soft', 'text-dark-text');

            tabAll.classList.add('text-gray-500', 'hover:text-dark-text');

        }



        renderVoucherGrid();

    };



    // Attach click listeners to tabs

    tabAll.addEventListener('click', (e) => {

        e.preventDefault();

        setFilter('all');

    });



    tabFavorite.addEventListener('click', (e) => {

        e.preventDefault();

        setFilter('favorite');

    });



    // --- Modal Functions (same as before) ---



    // Function to show the modal with data

    const showModal = (voucher) => {

        // Update modal content

        document.getElementById('modal-voucher-name').textContent = voucher.name;

        document.getElementById('modal-voucher-brand').textContent = `Brand: ${voucher.brand}`;

        document.getElementById('modal-voucher-title').textContent = voucher.name;

        document.getElementById('modal-voucher-points').textContent = voucher.points;

        document.getElementById('modal-voucher-expires').textContent = `Expires: ${voucher.expires}`;

        document.getElementById('modal-voucher-stock-terms').textContent = `Sisa stok: ${voucher.stock}`;

        document.getElementById('modal-voucher-image').innerHTML = `<img src="${voucher.image}" alt="${voucher.name}" class="w-full h-full object-cover">`;



        // Configure Redeem Button in Modal

        const isOutOfStock = voucher.stock <= 0;

        const isInsufficientPoints = userPoints < voucher.points;

        const isDisabled = isOutOfStock || isInsufficientPoints;



        modalRedeemButton.textContent = 'Redeem Now';

        modalRedeemButton.className = 'redeem-button px-8 py-3 rounded-full font-bold text-center transition w-full sm:w-auto mt-5';



        if (isOutOfStock) {

            modalRedeemButton.textContent = 'Stok Habis';

            modalRedeemButton.classList.add('bg-gray-300', 'text-white', 'cursor-not-allowed');

            modalRedeemButton.disabled = true;

        } else if (isInsufficientPoints) {

            modalRedeemButton.textContent = `Poin Kurang (${voucher.points - userPoints} lagi)`;

            modalRedeemButton.classList.add('bg-gray-300', 'text-white', 'cursor-not-allowed');

            modalRedeemButton.disabled = true;

        } else {

            // Can redeem

            modalRedeemButton.classList.add('bg-pink-soft', 'text-dark-text');

            modalRedeemButton.disabled = false;

            // Set data attribute for redemption process

            modalRedeemButton.setAttribute('data-voucher-id', voucher.id);

        }



        // Show the modal with transition

        modalBackdrop.classList.add('modal-active', 'opacity-100');

        modalContent.classList.remove('scale-95', 'opacity-0');

        modalContent.classList.add('scale-100', 'opacity-100');

    };



    // Function to hide the modal

    const hideModal = () => {

        modalContent.classList.remove('scale-100', 'opacity-100');

        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {

            modalBackdrop.classList.remove('modal-active', 'opacity-100');

        }, 300); // Wait for transition to finish

    };

   

    // Close modal on button click

    closeModalBtn.addEventListener('click', hideModal);

    // Close modal when clicking outside the content

    modalBackdrop.addEventListener('click', (e) => {

        if (e.target === modalBackdrop) {

            hideModal();

        }

    });



    // --- Redemption Logic (same as before) ---

    modalRedeemButton.addEventListener('click', (e) => {

        const voucherId = parseInt(e.target.getAttribute('data-voucher-id'));

        if (voucherId) {

            const voucher = vouchers.find(v => v.id === voucherId);

            if (voucher && userPoints >= voucher.points && voucher.stock > 0) {

                // Simulate successful redemption

                userPoints -= voucher.points;

                voucher.stock -= 1;

               

                // Update UI

                userPointsDisplay.textContent = userPoints;

               

                // Show confirmation message using a custom alert (not window.alert)

                hideModal();

                // Custom alert function (since window.alert is not allowed)

                const showCustomAlert = (message) => {

                    const existingAlert = document.getElementById('custom-alert');

                    if (existingAlert) existingAlert.remove();



                    const alertDiv = document.createElement('div');

                    alertDiv.id = 'custom-alert';

                    alertDiv.className = 'fixed top-4 right-4 bg-dark-text text-white p-4 rounded-lg shadow-xl transition-all duration-300 transform translate-x-full opacity-0 z-50';

                    alertDiv.textContent = message;

                    document.body.appendChild(alertDiv);

                   

                    // Animate in

                    setTimeout(() => {

                        alertDiv.classList.remove('translate-x-full', 'opacity-0');

                        alertDiv.classList.add('translate-x-0', 'opacity-100');

                    }, 10);



                    // Animate out and remove after 3 seconds

                    setTimeout(() => {

                        alertDiv.classList.remove('translate-x-0', 'opacity-100');

                        alertDiv.classList.add('translate-x-full', 'opacity-0');

                        setTimeout(() => alertDiv.remove(), 300);

                    }, 3000);

                };



                showCustomAlert(`SUCCESS! Anda telah menukarkan "${voucher.name}" untuk ${voucher.points} poin. Saldo baru Anda adalah ${userPoints} poin.`);

               

                // Re-render the grid to update the stock/button status of the redeemed item

                renderVoucherGrid();



            } else {

                 showCustomAlert('Error: Poin tidak cukup atau stok habis saat transaksi.');

            }

        }

    });



    // --- Catalog Render ---



    const createVoucherCard = (voucher) => {

        const isOutOfStock = voucher.stock <= 0;

        const isInsufficientPoints = userPoints < voucher.points;

        const isDisabled = isOutOfStock || isInsufficientPoints;



        let buttonText = 'Redeem';

        let buttonClass = 'bg-pink-soft text-dark-text hover:opacity-90';



        if (isOutOfStock) {

            buttonText = 'Out of Stock';

            buttonClass = 'bg-gray-300 text-white cursor-not-allowed';

        } else if (isInsufficientPoints) {

            buttonText = 'Insufficient Points';

            buttonClass = 'bg-gray-300 text-white cursor-not-allowed';

        }

       

        // Define SVG heart icons based on favorite status

        const heartIcons = voucher.isFavorite ? `

            <!-- Solid Red Heart (Active) -->

            <svg class="w-5 h-5 text-love-red heart-icon heart-solid" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>

            <!-- Outline Heart (Inactive, Hidden) -->

            <svg class="w-5 h-5 text-gray-400 heart-icon heart-outline hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>

        ` : `

            <!-- Outline Heart (Active) -->

            <svg class="w-5 h-5 text-gray-400 heart-icon heart-outline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>

            <!-- Solid Red Heart (Inactive, Hidden) -->

            <svg class="w-5 h-5 text-love-red heart-icon heart-solid hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>

        `;



        return `

            <div class="bg-white rounded-2xl shadow-md transition p-6 flex flex-col justify-between">

                <!-- Voucher Info -->

                <div>

                    <!-- Placeholder Image and Fav Toggle -->

                    <div class="h-40 bg-gray-100 rounded-xl mb-4 overflow-hidden relative">

                         <img src="${voucher.image}" alt="${voucher.name}" class="w-full h-full object-cover">

                         <button class="absolute top-3 right-3 bg-white/80 p-2 rounded-full shadow transition hover:bg-white toggle-favorite-btn"

                                 title="Add to Favorites" data-voucher-id="${voucher.id}">

                            ${heartIcons}

                        </button>

                    </div>



                    <h4 class="text-lg font-semibold text-dark-text mb-1">${voucher.name}</h4>

                    <p class="text-gray-500 mb-1">${voucher.brand}</p>

                    <p class="text-base mb-2">

                        <span class="font-bold text-pink-soft">${voucher.points}</span> Points

                    </p>

                    <p class="text-sm text-gray-400">Expires: ${voucher.expires}</p>

                    <p class="text-sm text-gray-400 mb-4">Remaining: ${voucher.stock}</p>

                </div>



                <!-- Button -->

                <button

                    class="redeem-button mt-2 py-2.5 rounded-full font-semibold text-center block w-full transition ${buttonClass}"

                    ${isDisabled ? 'disabled' : ''}

                    data-voucher-id="${voucher.id}">

                    ${buttonText}

                </button>

            </div>

        `;

    };



    const renderVoucherGrid = () => {

        gridContainer.innerHTML = ''; // Clear existing cards



        let filteredVouchers = vouchers;



        if (currentFilter === 'favorite') {

            filteredVouchers = vouchers.filter(v => v.isFavorite);

        }



        if (filteredVouchers.length === 0) {

            gridContainer.innerHTML = `

                <div class="col-span-full text-center py-10">

                    <p class="text-xl text-gray-500 font-semibold">Tidak ada Voucher yang tersedia.</p>

                    <p class="text-gray-400 mt-2">

                        ${currentFilter === 'favorite' ? 'Anda belum menambahkan voucher ke daftar favorit.' : 'Coba kembali nanti atau cek tab "Favorite Vouchers" Anda.'}

                    </p>

                </div>

            `;

        } else {

            filteredVouchers.forEach(voucher => {

                gridContainer.innerHTML += createVoucherCard(voucher);

            });

        }

       

        // Attach click listener for opening modal dynamically

        document.querySelectorAll('.redeem-button:not(:disabled)').forEach(button => {

            button.addEventListener('click', (e) => {

                const voucherId = parseInt(e.currentTarget.getAttribute('data-voucher-id'));

                const voucher = vouchers.find(v => v.id === voucherId);

                if (voucher) {

                    showModal(voucher);

                }

            });

        });



        // Attach click listener for favorite toggle

        document.querySelectorAll('.toggle-favorite-btn').forEach(button => {

            button.addEventListener('click', (e) => {

                // Prevent click event from bubbling up to the card (if the card itself were clickable)

                e.stopPropagation();

               

                const voucherId = parseInt(e.currentTarget.getAttribute('data-voucher-id'));

                const voucherIndex = vouchers.findIndex(v => v.id === voucherId);

               

                if (voucherIndex > -1) {

                    vouchers[voucherIndex].isFavorite = !vouchers[voucherIndex].isFavorite;

                   

                    // Toggle the displayed icons directly

                    const solidIcon = e.currentTarget.querySelector('.heart-solid');

                    const outlineIcon = e.currentTarget.querySelector('.heart-outline');



                    if (vouchers[voucherIndex].isFavorite) {

                        solidIcon.classList.remove('hidden');

                        outlineIcon.classList.add('hidden');

                    } else {

                        solidIcon.classList.add('hidden');

                        outlineIcon.classList.remove('hidden');

                    }



                    // If we are currently on the 'favorite' view, re-render to hide the removed item

                    if (currentFilter === 'favorite') {

                        renderVoucherGrid();

                    }

                }

            });

        });

    };



    // Render all vouchers on load

    setFilter('all');





    // Simple "Load More" functionality placeholder

    document.getElementById('load-more-btn').addEventListener('click', () => {

        // Custom alert function (since window.alert is not allowed)

        const showCustomAlert = (message) => {

            const existingAlert = document.getElementById('custom-alert');

            if (existingAlert) existingAlert.remove();



            const alertDiv = document.createElement('div');

            alertDiv.id = 'custom-alert';

            alertDiv.className = 'fixed top-4 right-4 bg-dark-text text-white p-4 rounded-lg shadow-xl transition-all duration-300 transform translate-x-full opacity-0 z-50';

            alertDiv.textContent = message;

            document.body.appendChild(alertDiv);

           

            // Animate in

            setTimeout(() => {

                alertDiv.classList.remove('translate-x-full', 'opacity-0');

                alertDiv.classList.add('translate-x-0', 'opacity-100');

            }, 10);



            // Animate out and remove after 3 seconds

            setTimeout(() => {

                alertDiv.classList.remove('translate-x-0', 'opacity-100');

                alertDiv.classList.add('translate-x-full', 'opacity-0');

                setTimeout(() => alertDiv.remove(), 300);

            }, 3000);

        };

        showCustomAlert('Simulasi memuat lebih banyak voucher...');

        // In a real app, this would fetch and append more items

    });

</script>

</body>

</html>