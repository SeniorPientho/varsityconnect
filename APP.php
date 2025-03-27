
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect - Student Housing Finder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#5D5CDE',
                        secondary: '#9F9EFF',
                        dark: {
                            bg: '#181818',
                            card: '#222222',
                            text: '#E1E1E1'
                        },
                        light: {
                            bg: '#FFFFFF',
                            card: '#F5F5F5',
                            text: '#333333'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom styles */
        .property-card:hover {
            transform: translateY(-3px);
            transition: all 0.3s ease;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(93, 92, 222, 0.2);
            border-radius: 50%;
            border-top-color: #5D5CDE;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Dark mode transitions */
        .dark .dark-transition {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
</head>
<body class="dark-transition bg-light-bg dark:bg-dark-bg text-light-text dark:text-dark-text min-h-screen">
    <!-- Theme Detection -->
    <script>
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (event.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>

    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <header class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-primary">Hello <?php session_start(); if(isset( $_SESSION["name"]))echo $_SESSION["name"];?></h1>
                    <h1 class="text-3xl font-bold text-primary">CampusConnect</h1>
                    <p class="text-gray-600 dark:text-gray-400">Find your perfect student housing</p>
                </div>
                <div class="flex space-x-2">
                    <button id="studentBtn" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">Student</button>
                    <button id="landlordBtn" class="bg-white dark:bg-dark-card text-primary border border-primary px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">Landlord</button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <!-- Student View -->
            <div id="studentView" class="block">
                <!-- Search & Filters -->
                <div class="mb-6 p-4 bg-light-card dark:bg-dark-card rounded-lg shadow">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-grow">
                            <input type="text" id="searchInput" placeholder="Search by location, price, amenities..." 
                                   class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                          bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                          focus:ring-primary">
                        </div>
                        <div class="flex space-x-2">
                            <select id="priceFilter" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                                          bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                                          focus:ring-primary">
                                <option value="">Price Range</option>
                                <option value="0-15999">ksh0 - ksh15000</option>
                                <option value="16000-25999">ksh16000 - ksh25000</option>
                                <option value="26000-29999">ksh26000 - ksh30000</option>
                                <option value="30000-50000">ksh30000+</option>
                            </select>
                            <select id="typeFilter" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                                        bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                                        focus:ring-primary">
                                <option value="">Property Type</option>
                                <option value="apartment">Apartment</option>
                                <option value="house">House</option>
                                <option value="dorm">Dorm</option>
                                <option value="shared">Shared Room</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Properties Grid -->
                <div id="propertyList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Properties will be loaded here dynamically -->
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="hidden flex justify-center my-8">
                    <div class="loading-spinner"></div>
                </div>
            </div>

            <!-- Landlord View -->
            <div id="landlordView" class="hidden">
                <div class="bg-light-card dark:bg-dark-card rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold mb-4 text-primary">List Your Property</h2>
                    <form id="propertyForm" class="space-y-4" action="addListings.php" method="post">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if(isset($_GET["error"])){
                            $fg='rgba(255,255,255,.9)';
                            $bg='rgba(255,0,0,.8)';
                            $message=$_GET["error"];
                            showNotification($fg,$bg,$message);
                            
                        }?>
                        <div>
                                <label for="propertyBedrooms"  class="block mb-1 font-medium">Hostel no</label>
                                <input type="number" name="hostelid" id="propertyBedrooms" min="0" required
                                       class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                              bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                              focus:ring-primary">
                            </div>
                            <div>
                           
                                <label for="propertyTitle" class="block mb-1 font-medium">Title</label>
                                <input type="text" name="title" id="propertyTitle" required
                                       class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                              bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                              focus:ring-primary">
                            </div>
                            <div>
                                <label for="propertyType" class="block mb-1 font-medium">Property Type</label>
                                <select id="propertyType"  name="type" required
                                        class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                               bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                               focus:ring-primary">
                                    <option value="">Select Type</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="house">House</option>
                                    <option value="dorm">Dorm</option>
                                    <option value="shared">Shared Room</option>
                                </select>
                            </div>
                            <div>
                                <label for="propertyPrice"  class="block mb-1 font-medium">Semester Rent (ksh.)</label>
                                <input type="number" name="price" id="propertyPrice" min="0" required
                                       class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                              bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                              focus:ring-primary">
                            </div>
                            <div>
                                <label for="propertyBedrooms"  class="block mb-1 font-medium">Bedrooms</label>
                                <input type="number" name="bedRooms" id="propertyBedrooms" min="0" required
                                       class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                              bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                              focus:ring-primary">
                            </div>
                            <div>
                                <label for="propertyLocation" class="block mb-1 font-medium">Location</label>
                                <input type="text"  name="location" id="propertyLocation" required
                                       class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                              bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                              focus:ring-primary">
                            </div>
                            <div>
                                <label for="propertyDistance"  class="block mb-1 font-medium">Distance from Campus (kilometres)</label>
                                <input  name="distance" id="propertyDistance" min="0" step="0.1" required
                                       class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                              bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                              focus:ring-primary">
                            </div>
                        </div>

                        <div>
                            <label for="propertyAmenities"  class="block mb-1 font-medium">Amenities (comma separated)</label>
                            <input type="text" name="amenities" id="propertyAmenities" placeholder="Wifi, Laundry, Parking, etc."
                                  class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                         bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                         focus:ring-primary">
                        </div>

                        <div>
                            <label for="propertyDescription" class="block mb-1 font-medium">Description</label>
                            <textarea id="propertyDescription"  name="description" rows="4" required
                                     class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                            bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                            focus:ring-primary"></textarea>
                        </div>

                        <div>
                            <label for="propertyColor"  class="block mb-1 font-medium">Card Color</label>
                            <input type="color" name="color" id="propertyColor" value="#5D5CDE"
                                   class="h-10 w-full rounded-md border border-gray-300 dark:border-gray-700 
                                          bg-white dark:bg-gray-800">
                        </div>

                        <div>
                            <label for="landlordContact" class="block mb-1 font-medium">Contact Information</label>
                            <input  name="contact" type="hidden" value='<?php echo $_SESSION['email']?>' id="landlordContact" placeholder="Your email address" required
                                   class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                          bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                          focus:ring-primary">
                        </div>

                        <div class="flex justify-end">
                            <button id="listpropertybtn" type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition" name="addListings">
                                List Property
                            </button>

                             
                        </div>
                    </form>
                </div>

                <!-- My Listings Section -->
                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4">My Listed Properties</h2>
                    <div id="myListings" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Landlord properties will be shown here -->
                    </div>
                </div>
            </div>

            <!-- Property Detail Modal -->
            <div id="propertyModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
                <div class="bg-white dark:bg-dark-card rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto m-4">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <h3 id="modalTitle" class="text-2xl font-bold text-primary"></h3>
                            <button id="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-xl font-bold">×</button>
                        </div>
                        <div id="modalContent" class="mt-4">
                            <!-- Property details will be displayed here -->
                        </div>
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h4 class="text-lg font-semibold mb-2">Contact Landlord</h4>
                            <form id="contactForm" class="space-y-4">
                                <input type="hidden" id="propertyId">
                                <div>
                                    <label for="studentName" class="block mb-1 font-medium">Your Name</label>
                                    <input type="text" id="studentName" required
                                           class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                                  bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                                  focus:ring-primary">
                                </div>
                                <div>
                                    <label for="studentEmail" class="block mb-1 font-medium">Your Email</label>
                                    <input type="email" id="studentEmail" required
                                           class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                                  bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                                  focus:ring-primary">
                                </div>
                                <div>
                                    <label for="studentMessage" class="block mb-1 font-medium">Message</label>
                                    <textarea id="studentMessage" rows="3" required
                                             class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 
                                                    bg-white dark:bg-gray-800 text-base focus:outline-none focus:ring-2 
                                                    focus:ring-primary"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                                        Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Toast -->
            <div id="notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-10 py-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300">
                <span id="notificationText"></span>
            </div>
        </main>
    </div>

    <script>
        // Sample property data (in a real app, this would come from a database)
        const sampleProperties = [
            {
                id: 1,
                title: "COZY HOSTELS",
                type: "apartment",
                price: 20000,
                bedrooms: 1,
                location: "ALONG KISUMU-BUSIA HIGHWAY OPPOSITE QJ'S RESTAURANT",
                distance: 1.2,
                amenities: ["Wifi", "Water", "Power"],
                description: "Perfect for privatelife students. This studio apartment is just a 5-minute walk from campus with all utilities included. Recently renovated with modern appliances.",
                contact: "kipchumbakirwa@gmail.com",
                color: "#5D5CDE"
            },
            {
                id: 2,
                title: "SPACE HOSTELS",
                type: "house",
                price: 28000,
                bedrooms: 2,
                location: "ALONG SIRIBA ROAD OPPOSITE THE SCHOOL BOTANIC GARDENS",
                distance: 1.2,
                amenities: ["Parking", "Backyard",  "Pets Allowed"],
                description: "Beautiful 2-bedroom house with a large backyard. Perfect for roommates. Pet-friendly with easy street parking. 15-minute walk to campus.",
                contact: "sandepetre@gmail.com",
                color: "#4F46E5"
            },
            {
                id: 3,
                title: "HOME AWAY FROM HOME",
                type: "dorm",
                price: 12000,
                bedrooms: 1,
                location: "ALONG NYAWITA MABUNGO ROAD",
                distance: 1.8,
                amenities: ["Tiled", "Study Room", "Wifi"],
                description: "tiled with wifi connect to provide conducive learning environment.",
                contact: "ngetichcurley@gmail.com",
                color: "#10B981"
            },
            {
                id: 4,
                title: "IMANI HOSTELS",
                type: "apartment",
                price: 40000,
                bedrooms: 3,
                location: "ALONG SIRIBA -MASENO SCHOOL ROAD",
                distance: 0.5,
                amenities: ["Wifi", "Pool", "Gym", "Parking", "Balcony"],
                description: "Luxury apartment perfect for a group of 3 students. Features include a swimming pool, gym, and secure parking. Just a 10-minute walk to campus.",
                contact: "premium@apartments.com",
                color: "#F59E0B"
            },
            {
                id: 5,
                title: "COZY NILES HOSTELS",
                type: "shared",
                price:22000 ,
                bedrooms: 2, // Shared room
                location: "ALONG MASENO POLICE ROAD OPPOSITE SCHOOL ACV HOSTELS",
                distance: 1.5,
                amenities: ["Wifi", "Bills Included"],
                description: "Shared room in a friendly student house. All bills included. Great community atmosphere with shared kitchen and living spaces.",
                contact: "roommates@studentlife.com",
                color: "#EC4899"
            },
            {
                id: 6,
                title: "DEGREE APPARTMENT",
                type: "apartment",
                price: 30000,
                bedrooms: 1,
                location: "ALONG SIRIBA ROAD OPPOSITE ACK CHURCH",
                distance: 0.8,
                amenities: ["Wifi", "Study Room", "Quiet Hours", "Parking"],
                description: "Perfect for  students who need a quiet place to study. Located in a peaceful neighborhood with enforced quiet hours.",
                contact: "grad.housing@example.com",
                color: "#8B5CF6"
            }
        ];

        // User state (in a real app, this would have proper authentication)
        let currentUser = {
            type: "student", // or "landlord"
            listings: [] // Properties listed by this landlord
        };

        // DOM Elements
        const studentBtn = document.getElementById('studentBtn');
        const landlordBtn = document.getElementById('landlordBtn');
        const studentView = document.getElementById('studentView');
        const landlordView = document.getElementById('landlordView');
        const propertyList = document.getElementById('propertyList');
        const myListings = document.getElementById('myListings');
        const searchInput = document.getElementById('searchInput');
        const priceFilter = document.getElementById('priceFilter');
        const typeFilter = document.getElementById('typeFilter');
        const propertyForm = document.getElementById('propertyForm');
        const propertyModal = document.getElementById('propertyModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const closeModal = document.getElementById('closeModal');
        const contactForm = document.getElementById('contactForm');
        const notification = document.getElementById('notification');
        const notificationText = document.getElementById('notificationText');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const listpropertybtn = document.getElementById('listpropertybtn');

        // Switch between Student and Landlord views
        studentBtn.addEventListener('click', () => {
           
        });
        listpropertybtn.addEventListener('click',() =>{
            fetch("addlistings.php",{
                method:"POST"
            })
        });

        <?php if(isset($_SESSION["name"]) && ($_SESSION["role"]=='landlord') ){
        ?>
            currentUser.type = "landlord";
            studentView.classList.add('hidden');
            studentBtn.classList.add('hidden');
            landlordView.classList.remove('hidden');
            landlordBtn.classList.remove('bg-white', 'dark:bg-dark-card', 'text-primary');
            landlordBtn.classList.add('hidden');
            studentBtn.classList.add('bg-white', 'dark:bg-dark-card', 'text-primary');
            studentBtn.classList.remove('bg-primary', 'text-white');
       
        <?php
    }else  if(isset($_SESSION["name"]) && ($_SESSION["role"]=='student')){?>
        currentUser.type = "student";
        studentView.classList.remove('hidden');
        landlordView.classList.add('hidden');
        landlordView.classList.add('hidden');
        landlordView.classList.add('hidden');
        studentBtn.classList.remove('bg-white', 'dark:bg-dark-card', 'text-primary');
        studentBtn.classList.add('hidden');
        landlordBtn.classList.add('hidden');
        landlordBtn.classList.remove('bg-primary', 'text-white');
        displayProperties();
        <?php
    }?>
        // Display properties based on filters
        function displayProperties() {
            const searchTerm = searchInput.value.toLowerCase();
            const priceRange = priceFilter.value;
            const propertyType = typeFilter.value;
            
            // Display loading indicator
            loadingIndicator.classList.remove('hidden');
            propertyList.innerHTML = '';
            
            // Simulate loading delay
            setTimeout(() => {
                let filteredProperties = [...sampleProperties];
                
                // Apply filters
                if (searchTerm) {
                    filteredProperties = filteredProperties.filter(property => 
                        property.title.toLowerCase().includes(searchTerm) || 
                        property.location.toLowerCase().includes(searchTerm) || 
                        property.description.toLowerCase().includes(searchTerm) || 
                        property.amenities.some(a => a.toLowerCase().includes(searchTerm))
                    );
                }
                
                if (propertyType) {
                    filteredProperties = filteredProperties.filter(property => property.type === propertyType);
                }
                
                if (priceRange) {
                    const [min, max] = priceRange.split('-');
                    if (min && max) {
                        filteredProperties = filteredProperties.filter(property =>( 
                            property.price >= Number(min)) && (property.price <= Number(max))
                        );
                    } else if (min) {
                        // For "1200+" case
                        filteredProperties = filteredProperties.filter(property => property.price >= Number(min));
                    }
                }
                
                // Render filtered properties
                propertyList.innerHTML = filteredProperties.length === 0 
                    ? '<p class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">No properties match your search criteria.</p>' 
                    : '';
                    
                filteredProperties.forEach(property => {
                    const propertyCard = createPropertyCard(property);
                    propertyList.appendChild(propertyCard);
                });
                
                loadingIndicator.classList.add('hidden');
            }, 300);
        }

        // Create a property card element
        function createPropertyCard(property) {
            const card = document.createElement('div');
            card.className = 'property-card bg-white dark:bg-dark-card rounded-lg shadow-md overflow-hidden hover:shadow-lg transition';
            
            // Card header with color from property
            const cardHeader = document.createElement('div');
            cardHeader.className = 'h-24 flex items-center justify-center text-white p-4';
            cardHeader.style.backgroundColor = property.color;
            
            const typeIcon = getPropertyTypeIcon(property.type);
            cardHeader.innerHTML = `
                <div class="text-center">
                    <div class="text-3xl mb-1">${typeIcon}</div>
                    <div class="font-medium">${capitalizeFirstLetter(property.type)}</div>
                </div>
            `;
            
            // Card content
            const cardContent = document.createElement('div');
            cardContent.className = 'p-4';
            cardContent.innerHTML = `
                <h3 class="text-lg font-bold mb-2 truncate">${property.title}</h3>
                <div class="flex justify-between items-baseline mb-3">
                    <span class="text-xl font-bold text-primary">Ksh${property.price}/sem</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">${property.bedrooms < 1 ? 'Shared' : property.bedrooms} BR</span>
                </div>
                <div class="flex items-center text-gray-600 dark:text-gray-400 text-sm mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    ${property.location}
                </div>
                <div class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    ${property.distance} kilometres from campus
                </div>
                <div class="text-sm truncate mb-4">
                    ${property.amenities.slice(0, 3).map(a => `<span class="inline-block bg-gray-100 dark:bg-gray-700 rounded-full px-2 py-1 text-xs mr-1 mb-1">${a}</span>`).join('')}
                    ${property.amenities.length > 3 ? `<span class="inline-block bg-gray-100 dark:bg-gray-700 rounded-full px-2 py-1 text-xs">+${property.amenities.length - 3} more</span>` : ''}
                </div>
                <button class="w-full bg-primary text-white py-2 rounded hover:bg-secondary transition view-property" data-id="${property.id}">
                    View Details
                </button>
            `;
            
            card.appendChild(cardHeader);
            card.appendChild(cardContent);
            
            card.querySelector('.view-property').addEventListener('click', () => {
                showPropertyDetails(property);
            });
            
            return card;
        }

        // Display landlord's listings
        function displayMyListings() {
            myListings.innerHTML = '';
            
            if (currentUser.listings.length === 0) {
                myListings.innerHTML = `
                    <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>You haven't listed any properties yet.</p>
                        <p class="mt-2">Use the form above to add your first property.</p>
                    </div>
                `;
                return;
            }
            
            currentUser.listings.forEach(property => {
                const propertyCard = createPropertyCard(property);
                myListings.appendChild(propertyCard);
            });
        }

        // Show property details in modal
        function showPropertyDetails(property) {
            modalTitle.textContent = property.title;
            
            modalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-2xl font-bold text-primary mb-2">Ksh ${property.price}<span class="text-sm font-normal">/sem</span></p>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </path>
                        </svg>
                        ${capitalizeFirstLetter(property.type)}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </path>
                        </svg>
                        ${property.bedrooms < 1 ? 'Shared Room' : `${property.bedrooms} Bedroom${property.bedrooms > 1 ? 's' : ''}`}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            ${property.location} (${property.distance} kilometres from campus)
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-1">Amenities:</h4>
                        <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4">
                            ${property.amenities.map(amenity => `<li>${amenity}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">${property.description}</p>
            `;
            
            document.getElementById('propertyId').value = property.id;
            
            propertyModal.classList.remove('hidden');
        }

        // Close the property modal
        closeModal.addEventListener('click', () => {
            propertyModal.classList.add('hidden');
        });

        // Close modal when clicking outside
        propertyModal.addEventListener('click', (e) => {
            if (e.target === propertyModal) {
                propertyModal.classList.add('hidden');
            }
        });

        // Handle property form submission
       propertyForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            //Get form values
            const newProperty = {
                 id: Date.now(), // Generate a unique ID
                title: document.getElementById('propertyTitle').value,
                type: document.getElementById('propertyType').value,
                price: Number(document.getElementById('propertyPrice').value),
                bedrooms: Number(document.getElementById('propertyBedrooms').value),
                location: document.getElementById('propertyLocation').value,
                distance: Number(document.getElementById('propertyDistance').value),
                amenities: document.getElementById('propertyAmenities').value.split(',').map(item => item.trim()).filter(item => item),
                description: document.getElementById('propertyDescription').value,
                 contact: document.getElementById('landlordContact').value,
                color: document.getElementById('propertyColor').value
             };
            
            // Add to user's listings
            currentUser.listings.push(newProperty);
            
            // Show success message
            showNotification("Property listed successfully!");
            
            // Reset form
            propertyForm.reset();
            document.getElementById('propertyColor').value = "#5D5CDE";
            
            // Update listings display
           displayMyListings();
        });

        // Handle contact form submission
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form values
            const propertyId = document.getElementById('propertyId').value;
            const studentName = document.getElementById('studentName').value;
            const studentEmail = document.getElementById('studentEmail').value;
            const studentMessage = document.getElementById('studentMessage').value;
            
            // In a real app, this would send the message to the landlord
            // For this demo, just show a success message
            let fg='rgba(255,255,255,.9)';
            let bg='rgba(255,0,0,.8)';
            let message="Message sent to landlord successfully!";
            showNotification(fg,bg,message);
           
            
            // Reset form and close modal
            contactForm.reset();
            propertyModal.classList.add('hidden');
        });

        <?php 
             function showNotification($fg,$bg,$message) {
 echo "<script>
let fg='$fg';
let bg='$bg';
let message='$message';
console.log('$message');
showNotification(fg,bg,message);
alert('$message');
            </script>";
             }
?>
        // Show notification toast
        function showNotification(fg,bg,message) {
            
        
            notificationText.textContent = message;
            notification.style.color=fg;
            notification.style.background=bg;
            notification.classList.add("translate-y-20","opacity-0");
            
            setTimeout(() => {
                notification.classList.remove("translate-y-20", "opacity-0");
            }, 5000);
        }
        
        // Filter properties when search/filter inputs change
        console.log(searchInput);
        document.addEventListener("DOMContentLoaded",function(){
        searchInput.addEventListener("input", displayProperties)});
        priceFilter.addEventListener("change", displayProperties);
        typeFilter.addEventListener("change", displayProperties);

        // Helper function: Get icon for property type
        function getPropertyTypeIcon(type) {
            switch (type) {
                case "apartment":
                    return '<svg xmlns=http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>';
                case "house":
                    return '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>';
                case "dorm":
                    return '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>';
                case"shared":
                    return '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>';
                default:
                    return '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>';
            }
        }

        // Helper function: Capitalize first letter
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Initialize the app
        displayProperties();
        </script>
</body>
</html>