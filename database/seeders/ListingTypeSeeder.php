<?php

namespace Database\Seeders;

use App\Models\Listing\ListingType;
use Illuminate\Database\Seeder;

class ListingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [



            [
                'name' => 'Hotel',
                'slug' => 'hotel',
                'icon' => 'fa-hotel',
            ],
            [
                'name' => 'Resort',
                'slug' => 'resort',
                'icon' => 'fa-umbrella-beach',
            ],
            [
                'name' => 'Hostel',
                'slug' => 'hostel',
                'icon' => 'fa-bed',
            ],
            [
                'name' => 'Hostal / Guesthouse',
                'slug' => 'hostal-guesthouse',
                'icon' => 'fa-home',
            ],
            [
                'name' => 'Vacation Rental',
                'slug' => 'vacation-rental',
                'icon' => 'fa-building',
            ],
            [
                'name' => 'Apartment',
                'slug' => 'apartment',
                'icon' => 'fa-building',
            ],
            [
                'name' => 'Villa',
                'slug' => 'villa',
                'icon' => 'fa-house-user',
            ],
            [
                'name' => 'Rural House / Cottage',
                'slug' => 'rural-house',
                'icon' => 'fa-home',
            ],
            [
                'name' => 'Rural Hotel',
                'slug' => 'rural-hotel',
                'icon' => 'fa-hotel',
            ],
            [
                'name' => 'Farm Stay / Agritourism',
                'slug' => 'farm-stay',
                'icon' => 'fa-tractor',
            ],
            [
                'name' => 'Camping',
                'slug' => 'camping',
                'icon' => 'fa-campground',
            ],
            [
                'name' => 'Glamping',
                'slug' => 'glamping',
                'icon' => 'fa-campground',
            ],
            [
                'name' => 'Albergue / Lodge',
                'slug' => 'albergue-lodge',
                'icon' => 'fa-house',
            ],
            [
                'name' => 'Spa Resort',
                'slug' => 'spa-resort',
                'icon' => 'fa-spa',
            ],
            [
                'name' => 'Balneario / Thermal Spa',
                'slug' => 'balneario',
                'icon' => 'fa-hot-tub',
            ],

            /*
            |--------------------------------------------------------------------------
            | FOOD & DRINK — المطاعم والأكل
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Restaurant',
                'slug' => 'restaurant',
                'icon' => 'fa-utensils',
            ],
            [
                'name' => 'Café / Coffee Shop',
                'slug' => 'cafe',
                'icon' => 'fa-mug-hot',
            ],
            [
                'name' => 'Bar / Pub',
                'slug' => 'bar-pub',
                'icon' => 'fa-wine-glass-alt',
            ],
            [
                'name' => 'Wine Bar',
                'slug' => 'wine-bar',
                'icon' => 'fa-wine-glass',
            ],
            [
                'name' => 'Gastro Bar',
                'slug' => 'gastro-bar',
                'icon' => 'fa-glass-martini-alt',
            ],
            [
                'name' => 'Winery / Bodega',
                'slug' => 'winery',
                'icon' => 'fa-wine-bottle',
            ],
            [
                'name' => 'Vineyard',
                'slug' => 'vineyard',
                'icon' => 'fa-seedling',
            ],
            [
                'name' => 'Wine Route',
                'slug' => 'wine-route',
                'icon' => 'fa-route',
            ],
            [
                'name' => 'Cooking Class',
                'slug' => 'cooking-class',
                'icon' => 'fa-utensil-spoon',
            ],
            [
                'name' => 'Food Market',
                'slug' => 'food-market',
                'icon' => 'fa-store',
            ],
            [
                'name' => 'Street Food',
                'slug' => 'street-food',
                'icon' => 'fa-hotdog',
            ],
            [
                'name' => 'Gourmet Products',
                'slug' => 'gourmet-products',
                'icon' => 'fa-cheese',
            ],
            [
                'name' => 'Local / Traditional Products',
                'slug' => 'local-products',
                'icon' => 'fa-box-open',
            ],
            [
                'name' => 'Bakery / Pastry',
                'slug' => 'bakery-pastry',
                'icon' => 'fa-birthday-cake',
            ],
            [
                'name' => 'Food Producer / Farm Shop',
                'slug' => 'food-producer',
                'icon' => 'fa-apple-alt',
            ],

            /*
            |--------------------------------------------------------------------------
            | SIGHTSEEING — الأماكن والمعالم
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Destination / Locality',
                'slug' => 'destination',
                'icon' => 'fa-map-marker-alt',
            ],
            [
                'name' => 'City',
                'slug' => 'city',
                'icon' => 'fa-city',
            ],
            [
                'name' => 'Village',
                'slug' => 'village',
                'icon' => 'fa-home',
            ],
            [
                'name' => 'Historical Site',
                'slug' => 'historical-site',
                'icon' => 'fa-landmark',
            ],
            [
                'name' => 'Historical Monument',
                'slug' => 'historical-monument',
                'icon' => 'fa-monument',
            ],
            [
                'name' => 'Castle',
                'slug' => 'castle',
                'icon' => 'fa-chess-rook',
            ],
            [
                'name' => 'Cathedral',
                'slug' => 'cathedral',
                'icon' => 'fa-church',
            ],
            [
                'name' => 'Church',
                'slug' => 'church',
                'icon' => 'fa-church',
            ],
            [
                'name' => 'Monastery',
                'slug' => 'monastery',
                'icon' => 'fa-place-of-worship',
            ],
            [
                'name' => 'Museum',
                'slug' => 'museum',
                'icon' => 'fa-landmark',
            ],
            [
                'name' => 'Art Gallery',
                'slug' => 'art-gallery',
                'icon' => 'fa-paint-brush',
            ],
            [
                'name' => 'Theatre / Performance',
                'slug' => 'theatre',
                'icon' => 'fa-theater-masks',
            ],
            [
                'name' => 'Concert / Live Music',
                'slug' => 'concert',
                'icon' => 'fa-music',
            ],
            [
                'name' => 'Festival & Event',
                'slug' => 'festival',
                'icon' => 'fa-calendar-check',
            ],
            [
                'name' => 'Local Festival',
                'slug' => 'local-festival',
                'icon' => 'fa-calendar-day',
            ],
            [
                'name' => 'Cultural Workshop',
                'slug' => 'cultural-workshop',
                'icon' => 'fa-tools',
            ],
            [
                'name' => 'Archaeological Site',
                'slug' => 'archaeological-site',
                'icon' => 'fa-monument',
            ],
            [
                'name' => 'Religious Site',
                'slug' => 'religious-site',
                'icon' => 'fa-pray',
            ],

            /*
            |--------------------------------------------------------------------------
            | TOURS — الجولات والزيارات
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'City Tour',
                'slug' => 'city-tour',
                'icon' => 'fa-city',
            ],
            [
                'name' => 'Historical Tour',
                'slug' => 'historical-tour',
                'icon' => 'fa-landmark',
            ],
            [
                'name' => 'Walking Tour',
                'slug' => 'walking-tour',
                'icon' => 'fa-walking',
            ],
            [
                'name' => 'Guided Tour',
                'slug' => 'guided-tour',
                'icon' => 'fa-map-signs',
            ],
            [
                'name' => 'Private Tour',
                'slug' => 'private-tour',
                'icon' => 'fa-user-tie',
            ],
            [
                'name' => 'Cultural Tour',
                'slug' => 'cultural-tour',
                'icon' => 'fa-globe-europe',
            ],
            [
                'name' => 'Food Tour',
                'slug' => 'food-tour',
                'icon' => 'fa-utensils',
            ],
            [
                'name' => 'Wine Tour',
                'slug' => 'wine-tour',
                'icon' => 'fa-wine-glass-alt',
            ],
            [
                'name' => 'Nature Tour',
                'slug' => 'nature-tour',
                'icon' => 'fa-leaf',
            ],
            [
                'name' => 'Night Tour',
                'slug' => 'night-tour',
                'icon' => 'fa-moon',
            ],
            [
                'name' => 'Boat Tour / Cruise',
                'slug' => 'boat-tour',
                'icon' => 'fa-ship',
            ],
            [
                'name' => 'Day Trip / Excursion',
                'slug' => 'excursion',
                'icon' => 'fa-bus',
            ],
            [
                'name' => 'Train / Rail Tour',
                'slug' => 'rail-tour',
                'icon' => 'fa-train',
            ],
            [
                'name' => 'Pilgrimage Trip',
                'slug' => 'pilgrimage',
                'icon' => 'fa-pray',
            ],
            [
                'name' => 'Educational Trip',
                'slug' => 'educational',
                'icon' => 'fa-graduation-cap',
            ],
            [
                'name' => 'Honeymoon / Romantic Package',
                'slug' => 'romantic',
                'icon' => 'fa-heart',
            ],

            /*
            |--------------------------------------------------------------------------
            | NATURE & HIKING — الطبيعة والمشي
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Hiking',
                'slug' => 'hiking',
                'icon' => 'fa-hiking',
            ],
            [
                'name' => 'Trekking',
                'slug' => 'trekking',
                'icon' => 'fa-hiking',
            ],
            [
                'name' => 'Nature Trail',
                'slug' => 'nature-trail',
                'icon' => 'fa-route',
            ],
            [
                'name' => 'Bicycle Tour',
                'slug' => 'bike-tour',
                'icon' => 'fa-bicycle',
            ],
            [
                'name' => 'Mountain Biking / BTT',
                'slug' => 'mountain-biking',
                'icon' => 'fa-biking',
            ],
            [
                'name' => 'Cycling Route',
                'slug' => 'cycling-route',
                'icon' => 'fa-bicycle',
            ],
            [
                'name' => 'National Park',
                'slug' => 'national-park',
                'icon' => 'fa-tree',
            ],
            [
                'name' => 'Natural Park',
                'slug' => 'natural-park',
                'icon' => 'fa-tree',
            ],
            [
                'name' => 'Nature Reserve',
                'slug' => 'nature-reserve',
                'icon' => 'fa-leaf',
            ],
            [
                'name' => 'Beach',
                'slug' => 'beach',
                'icon' => 'fa-umbrella-beach',
            ],
            [
                'name' => 'Lake',
                'slug' => 'lake',
                'icon' => 'fa-water',
            ],
            [
                'name' => 'Waterfall',
                'slug' => 'waterfall',
                'icon' => 'fa-water',
            ],
            [
                'name' => 'Cave / Speleology',
                'slug' => 'caving',
                'icon' => 'fa-mountain',
            ],
            [
                'name' => 'Bird Watching',
                'slug' => 'bird-watching',
                'icon' => 'fa-dove',
            ],
            [
                'name' => 'Wildlife Watching',
                'slug' => 'wildlife-watching',
                'icon' => 'fa-paw',
            ],
            [
                'name' => 'Wildlife Safari',
                'slug' => 'safari',
                'icon' => 'fa-paw',
            ],

            /*
            |--------------------------------------------------------------------------
            | ADVENTURE — المغامرات
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Adventure',
                'slug' => 'adventure',
                'icon' => 'fa-mountain',
            ],
            [
                'name' => 'Multi-Adventure',
                'slug' => 'multi-adventure',
                'icon' => 'fa-mountain',
            ],
            [
                'name' => 'Rock Climbing',
                'slug' => 'climbing',
                'icon' => 'fa-mountain',
            ],
            [
                'name' => 'Mountaineering',
                'slug' => 'mountaineering',
                'icon' => 'fa-mountain',
            ],
            [
                'name' => 'Canyoning',
                'slug' => 'canyoning',
                'icon' => 'fa-water',
            ],
            [
                'name' => 'Via Ferrata',
                'slug' => 'via-ferrata',
                'icon' => 'fa-mountain',
            ],
            [
                'name' => 'Paintball',
                'slug' => 'paintball',
                'icon' => 'fa-crosshairs',
            ],
            [
                'name' => 'Horse Riding / Equestrian',
                'slug' => 'horse-riding',
                'icon' => 'fa-horse',
            ],
            [
                'name' => 'Karting',
                'slug' => 'karting',
                'icon' => 'fa-car-side',
            ],
            [
                'name' => 'Adventure Park',
                'slug' => 'adventure-park',
                'icon' => 'fa-tree',
            ],
            [
                'name' => 'Zipline',
                'slug' => 'zipline',
                'icon' => 'fa-wind',
            ],

            /*
            |--------------------------------------------------------------------------
            | WATER — الأنشطة المائية
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Water Sports',
                'slug' => 'water-sports',
                'icon' => 'fa-water',
            ],
            [
                'name' => 'Surfing',
                'slug' => 'surfing',
                'icon' => 'fa-water',
            ],
            [
                'name' => 'Windsurfing',
                'slug' => 'windsurfing',
                'icon' => 'fa-wind',
            ],
            [
                'name' => 'Kitesurfing',
                'slug' => 'kitesurfing',
                'icon' => 'fa-wind',
            ],
            [
                'name' => 'Paddle Surf / SUP',
                'slug' => 'paddle-surf',
                'icon' => 'fa-water',
            ],
            [
                'name' => 'Kayaking / Canoeing',
                'slug' => 'kayaking',
                'icon' => 'fa-ship',
            ],
            [
                'name' => 'Rafting',
                'slug' => 'rafting',
                'icon' => 'fa-water',
            ],
            [
                'name' => 'Scuba Diving',
                'slug' => 'scuba-diving',
                'icon' => 'fa-swimmer',
            ],
            [
                'name' => 'Snorkeling',
                'slug' => 'snorkeling',
                'icon' => 'fa-swimmer',
            ],
            [
                'name' => 'Sailing',
                'slug' => 'sailing',
                'icon' => 'fa-ship',
            ],
            [
                'name' => 'Fishing',
                'slug' => 'fishing',
                'icon' => 'fa-fish',
            ],
            [
                'name' => 'Boat Rental',
                'slug' => 'boat-rental',
                'icon' => 'fa-ship',
            ],

            /*
            |--------------------------------------------------------------------------
            | AIR — الأنشطة الجوية
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Hot Air Balloon',
                'slug' => 'hot-air-balloon',
                'icon' => 'fa-hot-air-balloon',
            ],
            [
                'name' => 'Paragliding',
                'slug' => 'paragliding',
                'icon' => 'fa-parachute-box',
            ],
            [
                'name' => 'Skydiving',
                'slug' => 'skydiving',
                'icon' => 'fa-parachute-box',
            ],
            [
                'name' => 'Scenic Flight',
                'slug' => 'scenic-flight',
                'icon' => 'fa-helicopter',
            ],
            [
                'name' => 'Helicopter Tour',
                'slug' => 'helicopter-tour',
                'icon' => 'fa-helicopter',
            ],
            [
                'name' => 'Air Sports',
                'slug' => 'air-sports',
                'icon' => 'fa-wind',
            ],

            /*
            |--------------------------------------------------------------------------
            | SNOW — الثلج والشتاء
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Ski Resort / Ski Station',
                'slug' => 'ski-resort',
                'icon' => 'fa-skiing',
            ],
            [
                'name' => 'Skiing',
                'slug' => 'skiing',
                'icon' => 'fa-skiing',
            ],
            [
                'name' => 'Snowboarding',
                'slug' => 'snowboarding',
                'icon' => 'fa-snowboarding',
            ],
            [
                'name' => 'Snow Activities',
                'slug' => 'snow-activities',
                'icon' => 'fa-snowflake',
            ],

            /*
            |--------------------------------------------------------------------------
            | GOLF & SPORTS — الرياضة
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Golf Course',
                'slug' => 'golf',
                'icon' => 'fa-golf-ball',
            ],
            [
                'name' => 'Sports Club',
                'slug' => 'sports-club',
                'icon' => 'fa-futbol',
            ],
            [
                'name' => 'Fitness / Gym',
                'slug' => 'fitness-gym',
                'icon' => 'fa-dumbbell',
            ],
            [
                'name' => 'Tennis',
                'slug' => 'tennis',
                'icon' => 'fa-table-tennis',
            ],
            [
                'name' => 'Horse Riding',
                'slug' => 'horse-riding-sport',
                'icon' => 'fa-horse',
            ],

            /*
            |--------------------------------------------------------------------------
            | PARKS & FAMILY — العائلة والترفيه
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Theme Park',
                'slug' => 'theme-park',
                'icon' => 'fa-roller-coaster',
            ],
            [
                'name' => 'Amusement Park',
                'slug' => 'amusement-park',
                'icon' => 'fa-ticket-alt',
            ],
            [
                'name' => 'Water Park',
                'slug' => 'water-park',
                'icon' => 'fa-swimming-pool',
            ],
            [
                'name' => 'Zoo',
                'slug' => 'zoo',
                'icon' => 'fa-paw',
            ],
            [
                'name' => 'Aquarium',
                'slug' => 'aquarium',
                'icon' => 'fa-fish',
            ],
            [
                'name' => 'Family Activity',
                'slug' => 'family-activity',
                'icon' => 'fa-users',
            ],
            [
                'name' => 'Kids Activity',
                'slug' => 'kids-activity',
                'icon' => 'fa-child',
            ],
            [
                'name' => 'Park',
                'slug' => 'park',
                'icon' => 'fa-tree',
            ],

            /*
            |--------------------------------------------------------------------------
            | WELLNESS & NIGHTLIFE — الاسترخاء والحياة الليلية
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Spa & Wellness',
                'slug' => 'spa-wellness',
                'icon' => 'fa-spa',
            ],
            [
                'name' => 'Massage',
                'slug' => 'massage',
                'icon' => 'fa-hands',
            ],
            [
                'name' => 'Beauty & Aesthetics',
                'slug' => 'beauty-aesthetics',
                'icon' => 'fa-heart',
            ],
            [
                'name' => 'Thermal Baths',
                'slug' => 'thermal-baths',
                'icon' => 'fa-hot-tub',
            ],
            [
                'name' => 'Nightlife',
                'slug' => 'nightlife',
                'icon' => 'fa-moon',
            ],
            [
                'name' => 'Nightclub',
                'slug' => 'nightclub',
                'icon' => 'fa-music',
            ],
            [
                'name' => 'Live Music',
                'slug' => 'live-music',
                'icon' => 'fa-music',
            ],
            [
                'name' => 'Tablao / Flamenco Show',
                'slug' => 'tablao-flamenco',
                'icon' => 'fa-music',
            ],
            [
                'name' => 'Cinema',
                'slug' => 'cinema',
                'icon' => 'fa-film',
            ],
            [
                'name' => 'Bowling / Arcade',
                'slug' => 'bowling-arcade',
                'icon' => 'fa-bowling-ball',
            ],

            /*
            |--------------------------------------------------------------------------
            | SHOPPING — التسوق
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Shopping',
                'slug' => 'shopping',
                'icon' => 'fa-shopping-bag',
            ],
            [
                'name' => 'Local Shop',
                'slug' => 'local-shop',
                'icon' => 'fa-store',
            ],
            [
                'name' => 'Souvenir Shop',
                'slug' => 'souvenir-shop',
                'icon' => 'fa-gift',
            ],
            [
                'name' => 'Craft Shop',
                'slug' => 'craft-shop',
                'icon' => 'fa-palette',
            ],
            [
                'name' => 'Market',
                'slug' => 'market',
                'icon' => 'fa-store',
            ],

            /*
            |--------------------------------------------------------------------------
            | TRANSPORTATION — النقل
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Car Rental',
                'slug' => 'car-rental',
                'icon' => 'fa-car',
            ],
            [
                'name' => 'Bicycle Rental',
                'slug' => 'bike-rental',
                'icon' => 'fa-bicycle',
            ],
            [
                'name' => 'Motorcycle Rental',
                'slug' => 'motorcycle-rental',
                'icon' => 'fa-motorcycle',
            ],
            [
                'name' => 'Boat Rental',
                'slug' => 'boat-rental',
                'icon' => 'fa-ship',
            ],
            [
                'name' => 'Airport Transfer',
                'slug' => 'airport-transfer',
                'icon' => 'fa-plane-arrival',
            ],
            [
                'name' => 'Shuttle Service',
                'slug' => 'shuttle',
                'icon' => 'fa-shuttle-van',
            ],
            [
                'name' => 'Taxi / Private Driver',
                'slug' => 'taxi-private-driver',
                'icon' => 'fa-taxi',
            ],
            [
                'name' => 'Transportation Service',
                'slug' => 'transportation',
                'icon' => 'fa-bus',
            ],

            /*
            |--------------------------------------------------------------------------
            | TRAVEL SERVICES — خدمات السفر
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Travel Agency',
                'slug' => 'travel-agency',
                'icon' => 'fa-suitcase-rolling',
            ],
            [
                'name' => 'Tour Operator',
                'slug' => 'tour-operator',
                'icon' => 'fa-route',
            ],
            [
                'name' => 'Tour Guide',
                'slug' => 'tour-guide',
                'icon' => 'fa-user-tie',
            ],
            [
                'name' => 'Tourist Information',
                'slug' => 'tourist-information',
                'icon' => 'fa-info-circle',
            ],
            [
                'name' => 'Language School / Classes',
                'slug' => 'language-school',
                'icon' => 'fa-language',
            ],
            [
                'name' => 'Travel Insurance',
                'slug' => 'travel-insurance',
                'icon' => 'fa-shield-alt',
            ],

            /*
            |--------------------------------------------------------------------------
            | SERVICES — الخدمات العامة
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Health & Wellness Service',
                'slug' => 'health-wellness',
                'icon' => 'fa-heartbeat',
            ],
            [
                'name' => 'Physiotherapy',
                'slug' => 'physiotherapy',
                'icon' => 'fa-user-md',
            ],
            [
                'name' => 'Medical Service',
                'slug' => 'medical-service',
                'icon' => 'fa-medkit',
            ],
            [
                'name' => 'Pharmacy',
                'slug' => 'pharmacy',
                'icon' => 'fa-pills',
            ],
            [
                'name' => 'Laundry',
                'slug' => 'laundry',
                'icon' => 'fa-tshirt',
            ],
            [
                'name' => 'Cleaning Service',
                'slug' => 'cleaning-service',
                'icon' => 'fa-broom',
            ],
            [
                'name' => 'Workshop / Garage',
                'slug' => 'workshop-garage',
                'icon' => 'fa-tools',
            ],
            [
                'name' => 'Optician',
                'slug' => 'optician',
                'icon' => 'fa-glasses',
            ],
            [
                'name' => 'Locksmith',
                'slug' => 'locksmith',
                'icon' => 'fa-key',
            ],
            [
                'name' => 'Multiservice',
                'slug' => 'multiservice',
                'icon' => 'fa-concierge-bell',
            ],

            /*
            |--------------------------------------------------------------------------
            | BUSINESS & COMMERCIAL — الأعمال والتجارة
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Local Business',
                'slug' => 'local-business',
                'icon' => 'fa-store',
            ],
            [
                'name' => 'Food Store',
                'slug' => 'food-store',
                'icon' => 'fa-shopping-basket',
            ],
            [
                'name' => 'Specialty Store',
                'slug' => 'specialty-store',
                'icon' => 'fa-store-alt',
            ],
            [
                'name' => 'Artisan / Craftsperson',
                'slug' => 'artisan',
                'icon' => 'fa-hammer',
            ],
            [
                'name' => 'Local Producer',
                'slug' => 'local-producer',
                'icon' => 'fa-industry',
            ],

            /*
            |--------------------------------------------------------------------------
            | ROUTES — المسارات السياحية
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Tourist Route',
                'slug' => 'tourist-route',
                'icon' => 'fa-route',
            ],
            [
                'name' => 'Hiking Route',
                'slug' => 'hiking-route',
                'icon' => 'fa-hiking',
            ],
            [
                'name' => 'Cycling Route',
                'slug' => 'cycling-route',
                'icon' => 'fa-bicycle',
            ],
            [
                'name' => 'Wine Route',
                'slug' => 'wine-route',
                'icon' => 'fa-wine-glass',
            ],
            [
                'name' => 'Cultural Route',
                'slug' => 'cultural-route',
                'icon' => 'fa-landmark',
            ],
            [
                'name' => 'Historical Route',
                'slug' => 'historical-route',
                'icon' => 'fa-monument',
            ],
            [
                'name' => 'Pilgrimage Route',
                'slug' => 'pilgrimage-route',
                'icon' => 'fa-walking',
            ],
            [
                'name' => 'Scenic Route',
                'slug' => 'scenic-route',
                'icon' => 'fa-road',
            ],
        ];

        foreach ($types as $type) {
            ListingType::updateOrCreate(
                ['slug' => $type['slug']],
                [
                    'name' => $type['name'],
                    'icon' => $type['icon'],
                ]
            );
        }
    }
}
