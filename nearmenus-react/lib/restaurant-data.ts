export const restaurantData = {
  "1": {
    id: 1,
    name: "Bella Vista Italian",
    location: "Downtown Miami, FL",
    rating: 4.8,
    reviews: 1247,
    images: [
      "/placeholder.svg?height=400&width=600&text=Restaurant+Interior",
      "/placeholder.svg?height=300&width=400&text=Dining+Room",
      "/placeholder.svg?height=300&width=400&text=Kitchen+View",
      "/placeholder.svg?height=300&width=400&text=Outdoor+Seating",
      "/placeholder.svg?height=300&width=400&text=Wine+Cellar",
      "/placeholder.svg?height=300&width=400&text=Private+Dining",
    ],
    description:
      "Experience authentic Italian cuisine at Bella Vista, where traditional recipes meet modern culinary techniques. Our chefs use only the finest imported ingredients and fresh local produce to create memorable dining experiences.",
    phone: "+1 (305) 555-0123",
    email: "info@bellavista.com",
    website: "www.bellavista.com",
    address: "123 Ocean Drive, Miami Beach, FL 33139",
    cuisine: "Italian",
    priceRange: "$$-$$$",
    hours: {
      "Monday - Thursday": "11:00 AM - 10:00 PM",
      "Friday - Saturday": "11:00 AM - 11:00 PM",
      Sunday: "12:00 PM - 9:00 PM",
    },
    specialOffer: "Happy Hour: 3-6 PM - 50% off appetizers and wine by the glass!",
    features: [
      "Outdoor Seating",
      "Wine Bar",
      "Private Dining",
      "Takeout",
      "Delivery",
      "Valet Parking",
      "Live Music",
      "Romantic Atmosphere",
    ],
    customerReviews: [
      {
        id: 1,
        name: "Sarah Johnson",
        rating: 5,
        date: "2024-01-15",
        comment:
          "Absolutely amazing! The fettuccine alfredo was perfection and the tiramisu was the best I've ever had. The atmosphere is romantic and the service was impeccable.",
        verified: true,
        helpful: 23,
        images: [
          "/placeholder.svg?height=200&width=300&text=Fettuccine+Alfredo",
          "/placeholder.svg?height=200&width=300&text=Tiramisu+Dessert",
        ],
      },
      {
        id: 2,
        name: "Mike Rodriguez",
        rating: 4,
        date: "2024-01-10",
        comment:
          "Great Italian food with authentic flavors. The margherita pizza was excellent, though the wait time was a bit long. Overall a wonderful dining experience.",
        verified: true,
        helpful: 18,
        images: ["/placeholder.svg?height=200&width=300&text=Margherita+Pizza"],
      },
      {
        id: 3,
        name: "Emily Chen",
        rating: 5,
        date: "2024-01-08",
        comment:
          "Perfect for date night! The osso buco was tender and flavorful. The wine selection is impressive and the staff really knows their stuff. Will definitely return!",
        verified: true,
        helpful: 31,
        images: [
          "/placeholder.svg?height=200&width=300&text=Osso+Buco",
          "/placeholder.svg?height=200&width=300&text=Wine+Selection",
          "/placeholder.svg?height=200&width=300&text=Date+Night+Setup",
        ],
      },
    ],
    menu: {
      appetizers: [
        {
          name: "Bruschetta Trio",
          description: "Three varieties: classic tomato basil, mushroom truffle, and ricotta honey",
          price: 14,
          popular: true,
        },
        {
          name: "Antipasto Platter",
          description: "Selection of cured meats, cheeses, olives, and marinated vegetables",
          price: 18,
        },
        {
          name: "Calamari Fritti",
          description: "Crispy fried squid rings with marinara and spicy aioli",
          price: 16,
        },
        {
          name: "Burrata Caprese",
          description: "Fresh burrata with heirloom tomatoes, basil, and balsamic glaze",
          price: 17,
          popular: true,
        },
      ],
      pasta: [
        {
          name: "Fettuccine Alfredo",
          description: "House-made fettuccine in creamy parmesan sauce",
          price: 22,
          popular: true,
        },
        {
          name: "Spaghetti Carbonara",
          description: "Traditional Roman pasta with pancetta, eggs, and pecorino romano",
          price: 24,
        },
        {
          name: "Lobster Ravioli",
          description: "Handmade ravioli filled with lobster in pink vodka sauce",
          price: 32,
        },
        {
          name: "Penne Arrabbiata",
          description: "Spicy tomato sauce with garlic, red peppers, and fresh herbs",
          price: 20,
        },
      ],
      pizza: [
        {
          name: "Margherita",
          description: "San Marzano tomatoes, fresh mozzarella, basil, extra virgin olive oil",
          price: 18,
          popular: true,
        },
        {
          name: "Quattro Stagioni",
          description: "Four seasons pizza with artichokes, mushrooms, ham, and olives",
          price: 24,
        },
        {
          name: "Prosciutto e Rucola",
          description: "Prosciutto di Parma, arugula, cherry tomatoes, and parmesan",
          price: 26,
        },
        {
          name: "Funghi Truffle",
          description: "Wild mushrooms, truffle oil, mozzarella, and fresh thyme",
          price: 28,
        },
      ],
      mains: [
        {
          name: "Osso Buco",
          description: "Braised veal shank with saffron risotto and gremolata",
          price: 38,
        },
        {
          name: "Branzino",
          description: "Mediterranean sea bass with lemon herb crust and roasted vegetables",
          price: 34,
          popular: true,
        },
        {
          name: "Chicken Parmigiana",
          description: "Breaded chicken breast with marinara, mozzarella, and spaghetti",
          price: 28,
        },
        {
          name: "Veal Piccata",
          description: "Pan-seared veal with lemon caper sauce and garlic mashed potatoes",
          price: 36,
        },
      ],
      desserts: [
        {
          name: "Tiramisu",
          description: "Classic Italian dessert with espresso-soaked ladyfingers and mascarpone",
          price: 12,
          popular: true,
        },
        {
          name: "Panna Cotta",
          description: "Vanilla bean panna cotta with berry compote",
          price: 10,
        },
        {
          name: "Cannoli Siciliani",
          description: "Traditional Sicilian cannoli with ricotta filling and pistachios",
          price: 11,
        },
        {
          name: "Gelato Selection",
          description: "Three scoops of house-made gelato (vanilla, chocolate, pistachio)",
          price: 9,
        },
      ],
      beverages: [
        {
          name: "House Wine",
          description: "Red or white wine by the glass",
          price: 8,
        },
        {
          name: "Chianti Classico",
          description: "Premium Tuscan red wine",
          price: 12,
        },
        {
          name: "Prosecco",
          description: "Italian sparkling wine",
          price: 10,
        },
        {
          name: "Italian Soda",
          description: "San Pellegrino flavored sodas",
          price: 4,
        },
        {
          name: "Espresso",
          description: "Traditional Italian espresso",
          price: 3,
        },
        {
          name: "Cappuccino",
          description: "Espresso with steamed milk and foam",
          price: 5,
        },
      ],
    },
    policies: [
      "Reservations recommended for dinner service",
      "Happy Hour: Monday-Friday 3:00 PM - 6:00 PM",
      "Private dining room available for groups of 10+",
      "Corkage fee: $25 per bottle",
      "18% gratuity added to parties of 6 or more",
      "We accommodate dietary restrictions with advance notice",
    ],
  },
  "2": {
    id: 2,
    name: "Sakura Sushi House",
    location: "Brickell, Miami, FL",
    rating: 4.9,
    reviews: 892,
    images: [
      "/placeholder.svg?height=400&width=600&text=Sushi+Bar",
      "/placeholder.svg?height=300&width=400&text=Private+Room",
      "/placeholder.svg?height=300&width=400&text=Fresh+Fish",
    ],
    description: "Fresh sushi and traditional Japanese dishes prepared by master chefs with premium ingredients.",
    phone: "+1 (305) 555-0456",
    email: "reservations@sakurasushi.com",
    website: "www.sakurasushi.com",
    address: "456 Brickell Ave, Miami, FL 33131",
    cuisine: "Japanese",
    priceRange: "$$$-$$$$",
    hours: {
      "Monday - Thursday": "5:00 PM - 10:00 PM",
      "Friday - Saturday": "5:00 PM - 11:00 PM",
      Sunday: "5:00 PM - 9:00 PM",
    },
    specialOffer: "Omakase special: Chef's choice 8-course tasting menu",
    features: ["Sushi Bar", "Private Rooms", "Sake Selection", "Fresh Fish Daily"],
    customerReviews: [
      {
        id: 1,
        name: "David Kim",
        rating: 5,
        date: "2024-01-12",
        comment: "Best sushi in Miami! The omakase was incredible and the chef was very knowledgeable.",
        verified: true,
        helpful: 15,
        images: [],
      },
    ],
    menu: {
      appetizers: [
        {
          name: "Edamame",
          description: "Steamed soybeans with sea salt",
          price: 6,
        },
        {
          name: "Gyoza",
          description: "Pan-fried pork dumplings with ponzu sauce",
          price: 12,
        },
      ],
      sushi: [
        {
          name: "Dragon Roll",
          description: "Eel, cucumber, avocado with eel sauce",
          price: 16,
          popular: true,
        },
        {
          name: "Rainbow Roll",
          description: "California roll topped with assorted sashimi",
          price: 18,
        },
      ],
      sashimi: [
        {
          name: "Tuna Sashimi",
          description: "Fresh bluefin tuna, 6 pieces",
          price: 22,
        },
        {
          name: "Salmon Sashimi",
          description: "Norwegian salmon, 6 pieces",
          price: 18,
        },
      ],
      mains: [
        {
          name: "Chirashi Bowl",
          description: "Assorted sashimi over sushi rice",
          price: 28,
          popular: true,
        },
        {
          name: "Teriyaki Salmon",
          description: "Grilled salmon with teriyaki glaze",
          price: 26,
        },
      ],
      desserts: [
        {
          name: "Mochi Ice Cream",
          description: "Green tea, red bean, or vanilla",
          price: 8,
        },
      ],
      beverages: [
        {
          name: "Sake",
          description: "Premium Japanese rice wine",
          price: 12,
        },
        {
          name: "Green Tea",
          description: "Traditional Japanese green tea",
          price: 4,
        },
      ],
    },
    policies: ["Reservations required for omakase", "Fresh fish delivered daily", "Private rooms available for groups"],
  },
  "3": {
    id: 3,
    name: "The Burger Joint",
    location: "South Beach, Miami, FL",
    rating: 4.6,
    reviews: 2156,
    images: [
      "/placeholder.svg?height=400&width=600&text=Burger+Bar",
      "/placeholder.svg?height=300&width=400&text=Outdoor+Patio",
    ],
    description: "Gourmet burgers made with locally sourced beef and creative toppings in a casual setting.",
    phone: "+1 (305) 555-0789",
    email: "hello@burgerjoint.com",
    website: "www.burgerjoint.com",
    address: "789 Ocean Drive, Miami Beach, FL 33139",
    cuisine: "American",
    priceRange: "$-$$",
    hours: {
      "Monday - Sunday": "11:00 AM - 12:00 AM",
    },
    specialOffer: "Buy 2 burgers, get 1 free milkshake - Weekdays only!",
    features: ["Outdoor Seating", "Sports Bar", "Kids Menu", "Craft Beer", "Vegan Options"],
    customerReviews: [
      {
        id: 1,
        name: "John Smith",
        rating: 4,
        date: "2024-01-14",
        comment: "Great burgers and fries! The atmosphere is fun and casual.",
        verified: true,
        helpful: 8,
        images: [],
      },
    ],
    menu: {
      burgers: [
        {
          name: "Classic Cheeseburger",
          description: "Beef patty, cheese, lettuce, tomato, onion",
          price: 14,
          popular: true,
        },
        {
          name: "BBQ Bacon Burger",
          description: "Beef patty, bacon, BBQ sauce, onion rings",
          price: 16,
        },
      ],
      sides: [
        {
          name: "Truffle Fries",
          description: "Hand-cut fries with truffle oil and parmesan",
          price: 8,
          popular: true,
        },
        {
          name: "Onion Rings",
          description: "Beer-battered onion rings",
          price: 7,
        },
      ],
      drinks: [
        {
          name: "Chocolate Shake",
          description: "Thick chocolate milkshake",
          price: 6,
          popular: true,
        },
        {
          name: "Craft Beer",
          description: "Local craft beer selection",
          price: 5,
        },
      ],
      desserts: [
        {
          name: "Apple Pie",
          description: "Classic American apple pie",
          price: 7,
        },
      ],
      beverages: [
        {
          name: "Soda",
          description: "Coca-Cola products",
          price: 3,
        },
      ],
    },
    policies: ["Happy hour 3-6 PM weekdays", "Kids eat free on Sundays", "Outdoor seating available"],
  },
  "4": {
    id: 4,
    name: "Spice Garden Indian",
    location: "Coral Gables, FL",
    rating: 4.7,
    reviews: 743,
    images: [
      "/placeholder.svg?height=400&width=600&text=Indian+Dining",
      "/placeholder.svg?height=300&width=400&text=Buffet+Setup",
    ],
    description: "Authentic Indian flavors with traditional spices and modern presentation in an elegant setting.",
    phone: "+1 (305) 555-0321",
    email: "info@spicegarden.com",
    website: "www.spicegarden.com",
    address: "321 Miracle Mile, Coral Gables, FL 33134",
    cuisine: "Indian",
    priceRange: "$$-$$$",
    hours: {
      "Monday - Sunday": "12:00 PM - 10:00 PM",
    },
    specialOffer: "Lunch buffet: All-you-can-eat for $16.99 (Mon-Fri)",
    features: ["Lunch Buffet", "Vegetarian Options", "Catering", "Private Events"],
    customerReviews: [
      {
        id: 1,
        name: "Priya Patel",
        rating: 5,
        date: "2024-01-13",
        comment: "Authentic Indian food! The butter chicken is amazing.",
        verified: true,
        helpful: 12,
        images: [],
      },
    ],
    menu: {
      appetizers: [
        {
          name: "Samosas",
          description: "Crispy pastries filled with spiced potatoes",
          price: 8,
        },
        {
          name: "Pakoras",
          description: "Mixed vegetable fritters",
          price: 9,
        },
      ],
      curries: [
        {
          name: "Butter Chicken",
          description: "Tender chicken in creamy tomato sauce",
          price: 18,
          popular: true,
        },
        {
          name: "Lamb Vindaloo",
          description: "Spicy lamb curry with potatoes",
          price: 22,
        },
      ],
      rice: [
        {
          name: "Biryani",
          description: "Fragrant basmati rice with spices and meat",
          price: 16,
          popular: true,
        },
      ],
      bread: [
        {
          name: "Garlic Naan",
          description: "Fresh baked bread with garlic",
          price: 5,
          popular: true,
        },
      ],
      desserts: [
        {
          name: "Gulab Jamun",
          description: "Sweet milk dumplings in syrup",
          price: 6,
        },
      ],
      beverages: [
        {
          name: "Mango Lassi",
          description: "Yogurt drink with mango",
          price: 5,
        },
      ],
    },
    policies: ["Lunch buffet Monday-Friday", "Catering available", "Vegetarian and vegan options"],
  },
  "5": {
    id: 5,
    name: "Ocean Breeze Seafood",
    location: "Key Biscayne, FL",
    rating: 4.9,
    reviews: 1534,
    images: [
      "/placeholder.svg?height=400&width=600&text=Ocean+View",
      "/placeholder.svg?height=300&width=400&text=Fresh+Seafood",
    ],
    description: "Fresh seafood with ocean views, featuring daily catches and signature lobster dishes.",
    phone: "+1 (305) 555-0987",
    email: "reservations@oceanbreeze.com",
    website: "www.oceanbreeze.com",
    address: "987 Ocean Drive, Key Biscayne, FL 33149",
    cuisine: "Seafood",
    priceRange: "$$$-$$$$",
    hours: {
      "Tuesday - Sunday": "4:00 PM - 11:00 PM",
      Monday: "Closed",
    },
    specialOffer: "Fresh catch of the day - Market price",
    features: ["Ocean View", "Fresh Daily Catch", "Wine Pairing", "Romantic Setting"],
    customerReviews: [
      {
        id: 1,
        name: "Maria Garcia",
        rating: 5,
        date: "2024-01-11",
        comment: "Amazing seafood with a beautiful ocean view!",
        verified: true,
        helpful: 20,
        images: [],
      },
    ],
    menu: {
      appetizers: [
        {
          name: "Oysters",
          description: "Fresh local oysters, half dozen",
          price: 18,
        },
        {
          name: "Shrimp Cocktail",
          description: "Jumbo shrimp with cocktail sauce",
          price: 16,
        },
      ],
      mains: [
        {
          name: "Lobster Thermidor",
          description: "Lobster in creamy cognac sauce",
          price: 45,
          popular: true,
        },
        {
          name: "Grilled Mahi",
          description: "Fresh mahi-mahi with tropical salsa",
          price: 28,
        },
        {
          name: "Seafood Paella",
          description: "Traditional Spanish rice with mixed seafood",
          price: 32,
          popular: true,
        },
      ],
      desserts: [
        {
          name: "Key Lime Pie",
          description: "Traditional Florida dessert",
          price: 9,
        },
      ],
      beverages: [
        {
          name: "White Wine",
          description: "Crisp white wine selection",
          price: 10,
        },
      ],
    },
    policies: ["Reservations recommended", "Fresh catch daily", "Ocean view seating"],
  },
  "6": {
    id: 6,
    name: "Taco Libre Mexican",
    location: "Wynwood, Miami, FL",
    rating: 4.5,
    reviews: 967,
    images: [
      "/placeholder.svg?height=400&width=600&text=Mexican+Street+Art",
      "/placeholder.svg?height=300&width=400&text=Taco+Bar",
    ],
    description: "Vibrant Mexican street food with authentic flavors and creative cocktails in artistic Wynwood.",
    phone: "+1 (305) 555-0654",
    email: "hola@tacolibre.com",
    website: "www.tacolibre.com",
    address: "654 NW 2nd Ave, Miami, FL 33127",
    cuisine: "Mexican",
    priceRange: "$-$$",
    hours: {
      "Monday - Sunday": "11:00 AM - 2:00 AM",
    },
    specialOffer: "Taco Tuesday: $2 tacos all day!",
    features: ["Street Art Murals", "Craft Cocktails", "Live Music", "Late Night"],
    customerReviews: [
      {
        id: 1,
        name: "Carlos Rodriguez",
        rating: 4,
        date: "2024-01-10",
        comment: "Great tacos and amazing atmosphere in Wynwood!",
        verified: true,
        helpful: 10,
        images: [],
      },
    ],
    menu: {
      tacos: [
        {
          name: "Fish Tacos",
          description: "Grilled fish with cabbage slaw and chipotle mayo",
          price: 4,
          popular: true,
        },
        {
          name: "Carnitas",
          description: "Slow-cooked pork with onions and cilantro",
          price: 3,
          popular: true,
        },
      ],
      appetizers: [
        {
          name: "Guacamole",
          description: "Fresh avocado dip with tortilla chips",
          price: 8,
        },
        {
          name: "Queso Fundido",
          description: "Melted cheese with chorizo",
          price: 10,
        },
      ],
      mains: [
        {
          name: "Burrito Bowl",
          description: "Rice, beans, meat, and toppings",
          price: 12,
        },
      ],
      drinks: [
        {
          name: "Margaritas",
          description: "Classic lime margarita",
          price: 8,
          popular: true,
        },
      ],
      desserts: [
        {
          name: "Churros",
          description: "Fried dough with cinnamon sugar",
          price: 6,
        },
      ],
      beverages: [
        {
          name: "Horchata",
          description: "Sweet rice drink",
          price: 4,
        },
      ],
    },
    policies: ["Taco Tuesday specials", "Live music weekends", "Late night dining"],
  },
}
